<?php

return array(
	'zfcuser' => array(
        'UserEntityClass' => '\CanariumCore\Entity\User',
        'EnableDefaultEntities' => false,

		'user_entity_class' => '\CanariumCore\Entity\User',
		'enable_default_entities' => false,
        'auth_adapters' => array( 100 => 'ZfcUser\Authentication\Adapter\Db' ),
        'use_redirect_parameter_if_present' => true,
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
        'default_role' => 'guest',
        'authenticated_role' => 'user',
        // Using the authentication identity provider, which basically reads the roles from the auth service's identity
        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',

        'role_providers'        => array(
            // using an object repository (entity repository) to load all roles into our ACL
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                'object_manager'    => 'doctrine.entitymanager.orm_default',
                'role_entity_class' => 'CanariumCore\Entity\Role',
             ),
            'BjyAuthorize\Provider\Role\Config' => array(
                'guest' => array(),
                'user'  => array('children' => array(
                    'admin' => array(),
                )),
            ),
        ),

        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'admin' => array(),
                'owner' => array(),
            ),
        ),

        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    // allow guests and users (and admins, through inheritance)
                    // the "wear" privilege on the resource "pants"
                    array(array('admin'), 'admin', array()),
                    array(array('owner'), 'owner', array()),
                ),

                // Don't mix allow/deny rules if you are using role inheritance.
                // There are some weird bugs.
                'deny' => array(
                    // ...
                ),
            ),
        ),

        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array('controller' => 'zfcuser', 'roles' => array('user', 'admin')),
                array('controller' => 'zfcuser', 'action'=>'login', 'roles' => array('guest')),
                array('controller' => 'zfcuser', 'action'=>'logout', 'roles' => array('admin','owner','user','guest')),
                array('controller' => 'zfcuser', 'action'=>'index', 'roles' => array('admin','owner','user')),
                array('controller' => 'zfcuser', 'action'=>'register', 'roles' => array('guest')),

                array('controller' => 'CanariumCore\Controller\Index', 'roles' => array('admin','owner', 'guest')),
                array('controller' => 'User', 'roles' => array('admin','owner')),
                array('controller' => 'Admin\CanariumCore', 'roles' => array('admin','owner')),
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
    'db' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=skeleton;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
            'zfcuser_zend_db_adapter' => 'Zend\Db\Adapter\Adapter'
        ),
		'factories' => array(
			'admin' => 'CanariumCore\Navigation\Service\AdminNavigationFactory',
			'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
             'Zend\Db\Adapter\Adapter'
                    => 'Zend\Db\Adapter\AdapterServiceFactory'
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
    //jhu-zdt-logger
    'jhu' => array(
        'zdt_logger' => array(
            /**
             * The logger that will be used. This module will only add a writer to it
             * so if you already have a logger in your application, you can set it here.
             *
             * The logger you'll set here has to be available thru the service manager
             * and be an instance or extend Zend\Log\Logger.
             */
            'logger' => 'Zend\Log\Logger'
        )
    )
);
