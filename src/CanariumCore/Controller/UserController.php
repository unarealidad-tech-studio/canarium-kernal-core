<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CanariumCore\Controller;

use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class UserController extends \ZfcUser\Controller\UserController
{
	public function submissionListAction(){

	}

    public function loginAction()
    {
        /**
         * Workaround for the remember me problem
         */
        $post = $this->getRequest()->getPost();
        if (!$post->get('remember_me')) {
            $post->set('remember_me', '0');
        }

        return parent::loginAction();
    }

    public function authenticateUserAction()
    {
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $hydrator = new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($entityManager);

        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $entityArray = $hydrator->extract($this->zfcUserAuthentication()->getIdentity());
            return new JsonModel(array(
                'user' => $entityArray
            ));
        }

        $adapter = $this->zfcUserAuthentication()->getAuthAdapter();
        $redirect = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));

        $result = $adapter->prepareForAuthentication($this->getRequest());

        // Return early if an adapter returned a response
        if ($result instanceof Response) {
            return $result;
        }

        $auth = $this->zfcUserAuthentication()->getAuthService()->authenticate($adapter);

        if ($auth->isValid()) {
            $entityArray = $hydrator->extract($this->zfcUserAuthentication()->getIdentity());
            return new JsonModel(array(
                'user' => $entityArray
            ));
        } else {
            $adapter->resetAdapters();
            return new JsonModel(array(
                'error' => $this->failedLoginMessage
            ));
        }
    }

	public function submittedFormAction(){
		$id = (int) $this->params()->fromRoute('id', 0);

		$objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$form = new \Form\Form\FieldsetForm($objectManager);

		$data = $objectManager->getRepository('Form\Entity\ParentData')->find($id);

		$view = new ViewModel();
		$view->data = $data;
		return $view;
	}

    public function logoutAction()
    {
        $current_user = $this->zfcUserAuthentication()->getIdentity();

        $this->zfcUserAuthentication()->getAuthAdapter()->resetAdapters();
        $this->zfcUserAuthentication()->getAuthAdapter()->logoutAdapters();
        $this->zfcUserAuthentication()->getAuthService()->clearIdentity();

        $redirect_route = $this->getOptions()->getLogoutRedirectRoute();

        $redirect = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect', false));

        if ($this->getOptions()->getUseRedirectParameterIfPresent() && $redirect) {
            $redirect_route = $redirect;
        }

        $delete_user = $this->params()->fromQuery('delete_user', false);

        if ($delete_user) {
            try {
                $entity_manager = $this->getServiceLocator()
                    ->get('Doctrine\ORM\EntityManager');
                $entity_manager->remove($current_user);
                $entity_manager->flush();
            } catch (\Exception $e) {
                //just ignore since this is maybe a constraint error
            }
        }

        $logout_third_party = $this->getServiceLocator()
            ->get('canariumcore_module_options')
            ->isLogoutThirdPartyLoginToo();

        if ($logout_third_party) {
            $uri = $this->getRequest()->getUri();
            $base = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
            return $this->redirect()->toUrl(
                'https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue='.
                $base.$this->url()->fromRoute($redirect_route)
            );
        } else {
            return $this->redirect()->toRoute($redirect_route);
        }
    }

}
