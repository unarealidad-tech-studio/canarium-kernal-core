<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CanariumCore;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\Router\Http\RouteMatch;

use BjyAuthorize\View\RedirectionStrategy;

use ZF\Apigility\Provider\ApigilityProviderInterface;

class Module implements ApigilityProviderInterface
{
    protected $event;

    public function onBootstrap(MvcEvent $e)
    {
        $this->event         = $e;
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

		$sm = $e->getApplication()->getServiceManager();
		$app = $e->getApplication();

        $config = $sm->get('canariumcore_module_options');

        if ($config->isLoginOnDeniedAccess()) {
            $strategy = new RedirectionStrategy();
            $eventManager->attach($strategy);
        }

        // Check for registration limit
        $self = $this;
        $app->getEventManager()->attach( 'dispatch',
            function($e) use ($sm, $self) {
                $errorMessage = $sm->get('Request')->getQuery('error');
                $routeMatch = $e->getRouteMatch();
                $controllerName = $routeMatch->getParam('controller');
                $controllerAction = $routeMatch->getParam('action');

                if ($controllerName == 'zfcuser' && $controllerAction == 'register' &&
                    $errorMessage != 'Maximum user limit has been reached') {
                    $service = $sm->get('canariumcore_user_service');
                    if ($service->isMaximumUserReached()) {
                        $self->redirectToRegistrationError();
                    }
                }

            }, 100
        );


        $app->getEventManager()->attach('render', array($this, 'setLayoutTitle'));

        $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
        $viewModel->site_name = $config->getSiteName();

        if ($config->getIsAuthenticationRequired()) {
            $app->getEventManager()->attach(
                'route',
                array($this, 'checkIfAuthenticated'),
                -100
            );
        }

		$userService = $sm->get('zfcuser_user_service');
		$userService->getEventManager()->attach('register', function(\Zend\EventManager\Event $e) use ($sm, $self){
            $service = $sm->get('canariumcore_user_service');
            if ($service->isMaximumUserReached()) {
                $self->redirectToRegistrationError();
            }
        });

        $userService->getEventManager()->attach('register.post',
		function(\Zend\EventManager\Event $e) use ($sm) {
			$userForm = $e->getParam('user');
			$objectManager = $sm->get('doctrine.entitymanager.orm_default');

            $userEntityClass = $sm->get('zfcuser_user_service')->getOptions()->getUserEntityClass();
            $user = $objectManager->getRepository($userEntityClass)->find($userForm->getId());

			// role : user
			$role = $objectManager->getRepository('CanariumCore\Entity\Role')->find(1);
			$user->addRole($role);

			$objectManager->flush();
		});

        // Start layout variables
        $app = $e->getApplication();
        $app->getEventManager()->attach(
            'dispatch',
            function($e) {
                $routeMatch = $e->getRouteMatch();
                $viewModel = $e->getViewModel();
                $viewModel->setVariable('controller', $routeMatch->getParam('controller'));
                $viewModel->setVariable('action', $routeMatch->getParam('action'));
            },
            -100
        );
        // End layout variables

		// LOG ZFCUSER AUTH
		$zfcAuthEvents = $e->getApplication()->getServiceManager()->get('ZfcUser\Authentication\Adapter\AdapterChain')->getEventManager();
		$zfcAuthEvents->attach( 'authenticate', function( $authEvent ) use( $sm ){
			try
			{
                $objectManager = $sm->get('doctrine.entitymanager.orm_default');
                $userEntityClass = $sm->get('zfcuser_user_service')->getOptions()->getUserEntityClass();
                $user = $objectManager->getRepository($userEntityClass)->find($authEvent->getIdentity());
                $user->setLastLogin(new \DateTime('now'));
				$objectManager->flush();
				return true;
			}
			catch( \Exception $x ){ }
		});

		$userService = $sm->get('zfcuser_user_service');
		$authorize = $sm->get('BjyAuthorizeServiceAuthorize');
		try {
			$acl = $authorize->getAcl();
			$role = $authorize->getIdentity();

			\Zend\View\Helper\Navigation::setDefaultAcl($acl);
			\Zend\View\Helper\Navigation::setDefaultRole($role);
		} catch(\Exception $error){ }

		$eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'selectLayoutBasedOnRoute'));

        // Load the title maps
        $app = $e->getApplication();
        $app->getEventManager()->attach(
            'dispatch',
            function($e) use( $sm ) {
                $routeMatch = $e->getRouteMatch();
                $viewModel = $e->getViewModel();
                $viewModel->setVariable('controller', $routeMatch->getParam('controller'));
                $viewModel->setVariable('action', $routeMatch->getParam('action'));
                $options = $sm->get('canariumcore_module_options');
                $viewModel->setVariable('titleMaps', $options->getTitleMaps());
            },
            -100
        );

    }

    public function redirectToRegistrationError($message = 'Maximum user limit has been reached')
    {
        // $url = $this->event->getRouter()->assemble(array('action' => 'register'), array('name' => 'zfcuser/register'));
        $url = '?error='.urlencode($message);
        $response = $this->event->getResponse();
        $response->getHeaders()->addHeaderLine('Location', $url);
        $response->setStatusCode(302);
        $response->sendHeaders();
        exit;
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

	public function init(ModuleManager $moduleManager)
    {

	}

    public function getViewHelperConfig()
    {

    }

    /**
     * @param  \Zend\Mvc\MvcEvent $e The MvcEvent instance
     * @return void
     */
    public function setLayoutTitle($e)
    {
        $matches = $e->getRouteMatch();

        if (!$matches) {
            return;
        }

        $action = $matches->getParam('action');
        $action = str_replace('index', '', $action);
        $action = trim($action);

        $controller = $matches->getParam('controller');

        $config = $e->getApplication()->getServiceManager()->get('canariumcore_module_options');

        // Getting the view helper manager from the application service manager
        $viewHelperManager = $e->getApplication()->getServiceManager()->get('viewHelperManager');

        // Getting the headTitle helper from the view helper manager
        $headTitleHelper   = $viewHelperManager->get('headTitle');

        // Setting a separator string for segments
        $headTitleHelper->setSeparator(' - ');

        $headTitleHelper->append($config->getSiteName());

        if ($config->getVerboseTitle()) {
            $headTitleHelper->append($controller);
            if ($action) {
                $headTitleHelper->append($action);
            }
        }
    }

    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'canariumcore_user_service' => 'CanariumCore\Service\User',
                'canariumcore_app_service' => 'CanariumCore\Service\Application',
            ),
            'factories' => array(
                'canariumcore_module_options' => function ($sm) {
                    $config = $sm->get('Config');
                    return new Options\ModuleOptions(isset($config['canariumcore']) ? $config['canariumcore'] : array());
                },
                'canariumcore_user_form' => function ($sm) {
                    $form = $sm->get('zfcuser_register_form');
                    $userService = $sm->get('zfcuser_user_service');

                    $form->setHydrator($userService->getFormHydrator());
                    $form->get('submit')->setValue('Save');

                    // Add the roles
                    $objectManager = $sm->get('Doctrine\ORM\EntityManager');
                    $form->add(array(
                        'name' => 'role',
                        'type' => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
                        'options' => array(
                            'object_manager'    => $objectManager,
                            'target_class'      => 'CanariumCore\Entity\Role',
                            'property'          => 'roleId',
                            'label'             => 'Roles'
                        ),
                        'attributes' => array(
                            'empty_option' => '',
                            'allow_empty' => false,
                            'continue_if_empty' => false,
                        )
                    ));
                    return $form;
                },
            )
        );
    }

    /* Helpers*/

    public function checkIfAuthenticated($e)
    {
        $app = $e->getApplication();
        $routeMatch = $e->getRouteMatch();
        $sm = $app->getServiceManager();
        $auth = $sm->get('zfcuser_auth_service');
        $config = $sm->get('canariumcore_module_options');

        if ($routeMatch->getMatchedRouteName() == 'oauth2callback') {
            return;
        }

        $validRoutes = array('zfcuser/login', 'zfcuser/register');
        $validRoutes = array_merge($validRoutes, (array)$config->getIsAuthenticationWhitelist());
        
        if (!$auth->hasIdentity() && !in_array($routeMatch->getMatchedRouteName(), $validRoutes)) {

            //GENERATE THE URL FROM CURRENT ROUTE (YOUR blog ONE)
            $redirect = $e->getRouter()->assemble(
                $routeMatch->getParams(),
                array(
                    'name' => $routeMatch->getMatchedRouteName(),
                )
            );

            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine(
                'Location',
                $e->getRouter()->assemble(
                        array(),
                        array('name' => 'zfcuser/login')

                )
            );
            $response->setStatusCode(302);
            return $response;
        }

        if ($routeMatch->getMatchedRouteName() == 'zfcuser/login' || $routeMatch->getMatchedRouteName() == 'zfcuser/register') {
            $e->getViewModel()->setTemplate('layout/login');
        }

    }

	public function selectLayoutBasedOnRoute(MvcEvent $e)
    {
        $app    = $e->getParam('application');
        $sm     = $app->getServiceManager();

        $match      = $e->getRouteMatch();
        $controller = $e->getTarget();
        if (!$match instanceof RouteMatch
            || 0 !== strpos($match->getMatchedRouteName(), 'admin')
            || $controller->getEvent()->getResult()->terminate()
        ) {
            return;
        }


        $controller->layout('layout/admin');
    }
}
