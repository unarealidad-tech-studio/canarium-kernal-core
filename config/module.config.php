<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
	'zfcuser' => array(
        'UserEntityClass' => '\CanariumCore\Entity\User',
        'EnableDefaultEntities' => false,

		'user_entity_class' => '\CanariumCore\Entity\User',
		'enable_default_entities' => false,
    ),

	'doctrine' => array(
		'configuration' => array(
            'orm_default' => array(
				'datetime_functions' => array(
					'REGEXP'  => 'DoctrineExtensions\Query\Mysql\Regexp',
					'DATEDIFF'  => 'DoctrineExtensions\Query\Mysql\DateDiff',
					'DAY'  => 'DoctrineExtensions\Query\Mysql\Day',
					'WEEK'  => 'DoctrineExtensions\Query\Mysql\Week',
					'MONTH'  => 'DoctrineExtensions\Query\Mysql\Month',
					'YEAR'  => 'DoctrineExtensions\Query\Mysql\Year',
					'CONCAT_WS' => 'DoctrineExtensions\Query\Mysql\ConcatWs',
				),
				'string_functions' => array(
					'FIELD' => 'DoctrineExtensions\Query\Mysql\Field',
				),
			)
		),
        'driver' => array(
            'zfcuser_driver' =>array(
                 'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                 'cache' => 'array',
                 'paths' => array(__DIR__ .'/../src/CanariumCore/Entity')
            ),

            'orm_default' =>array(
                'drivers' => array(
                    'CanariumCore\Entity'  =>  'zfcuser_driver',
					'Gedmo\Loggable\Entity' => 'loggable_driver',
                ),
            ),
			'loggable_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    'vendor/gedmo/doctrine-extensions/lib/Gedmo/Loggable/Entity',
                ),
            ),
        )
    ),

	'bjyauthorize' => array(
        // Using the authentication identity provider, which basically reads the roles from the auth service's identity
        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',

        'role_providers'        => array(
            // using an object repository (entity repository) to load all roles into our ACL
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                'object_manager'    => 'doctrine.entitymanager.orm_default',
                'role_entity_class' => 'CanariumCore\Entity\Role',
             ),
        ),
    ),

	'view_helpers' => array(
      'invokables' => array(
         'url_title_filter' => 'CanariumCore\View\Helper\Filter\Url',
      ),
   ),

	'controllers' => array(
        'invokables' => array(
            'CanariumCore\Controller\Index' => 'CanariumCore\Controller\IndexController',
			'Admin\CanariumCore' 	=> 'CanariumCore\Controller\AdminController',
			'User' 				=> 'CanariumCore\Controller\UserController',
			'zfcuser' 	=> 'CanariumCore\Controller\UserController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'CanariumCore\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'canarium-core' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/canarium-core',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CanariumCore\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),

			'admin' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        'controller'    => 'Admin\CanariumCore',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                ),
            ),

			'oauth2callback' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/oauth2callback',
                    'defaults' => array(
                        'controller'    => 'CanariumCore\Controller\Index',
                        'action'        => 'oauth2callback',
                    ),
                ),
                'may_terminate' => true,
            ),

			'zfcuser' => array(
                'type' => 'Segment',
                'priority' => 1000,
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        'controller' => 'zfcuser',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
				'child_routes' => array(
                    'updateprofile' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/update-profile',
                            'defaults' => array(
                                'controller' => 'zfcuser',
                                'action'     => 'update-profile',
                            ),
                        ),
                    ),
				),
			),


        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
		'factories' => array(
			'admin' => 'CanariumCore\Navigation\Service\AdminNavigationFactory',
			'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
		),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),

    'view_manager' => array(
		'strategies' => array(
           'ViewJsonStrategy',
        ),
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
			'layout/admin'           => __DIR__ . '/../view/layout/admin.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
			'partial/paginator' => __DIR__ . '/../view/partial/paginator.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
