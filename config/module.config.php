<?php
return array(
    'zfcuser' => array(
        'UserEntityClass' => '\\CanariumCore\\Entity\\User',
        'EnableDefaultEntities' => false,
        'user_entity_class' => '\\CanariumCore\\Entity\\User',
        'enable_default_entities' => false,
        'auth_adapters' => array(
            100 => 'ZfcUser\\Authentication\\Adapter\\Db',
        ),
        'use_redirect_parameter_if_present' => true,
    ),
    'doctrine' => array(
        'configuration' => array(
            'orm_default' => array(
                'datetime_functions' => array(
                    'REGEXP' => 'DoctrineExtensions\\Query\\Mysql\\Regexp',
                    'DATEDIFF' => 'DoctrineExtensions\\Query\\Mysql\\DateDiff',
                    'DAY' => 'DoctrineExtensions\\Query\\Mysql\\Day',
                    'WEEK' => 'DoctrineExtensions\\Query\\Mysql\\Week',
                    'MONTH' => 'DoctrineExtensions\\Query\\Mysql\\Month',
                    'YEAR' => 'DoctrineExtensions\\Query\\Mysql\\Year',
                    'CONCAT_WS' => 'DoctrineExtensions\\Query\\Mysql\\ConcatWs',
                ),
                'string_functions' => array(
                    'FIELD' => 'DoctrineExtensions\\Query\\Mysql\\Field',
                ),
            ),
        ),
        'driver' => array(
            'zfcuser_driver' => array(
                'class' => 'Doctrine\\ORM\\Mapping\\Driver\\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    0 => __DIR__ . '/../src/CanariumCore/Entity',
                ),
            ),
            'orm_default' => array(
                'drivers' => array(
                    'CanariumCore\\Entity' => 'zfcuser_driver',
                    'Gedmo\\Loggable\\Entity' => 'loggable_driver',
                ),
            ),
            'loggable_driver' => array(
                'class' => 'Doctrine\\ORM\\Mapping\\Driver\\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    0 => 'vendor/gedmo/doctrine-extensions/lib/Gedmo/Loggable/Entity',
                ),
            ),
        ),
    ),
    'bjyauthorize' => array(
        'default_role' => 'guest',
        'authenticated_role' => 'user',
        'identity_provider' => 'BjyAuthorize\\Provider\\Identity\\AuthenticationIdentityProvider',
        'role_providers' => array(
            'BjyAuthorize\\Provider\\Role\\ObjectRepositoryProvider' => array(
                'object_manager' => 'doctrine.entitymanager.orm_default',
                'role_entity_class' => 'CanariumCore\\Entity\\Role',
            ),
            'BjyAuthorize\\Provider\\Role\\Config' => array(
                'guest' => array(),
                'user' => array(
                    'children' => array(
                        'admin' => array(),
                    ),
                ),
            ),
        ),
        'resource_providers' => array(
            'BjyAuthorize\\Provider\\Resource\\Config' => array(
                'admin' => array(),
                'owner' => array(),
            ),
        ),
        'rule_providers' => array(
            'BjyAuthorize\\Provider\\Rule\\Config' => array(
                'allow' => array(
                    0 => array(
                        0 => array(
                            0 => 'admin',
                        ),
                        1 => 'admin',
                        2 => array(),
                    ),
                    1 => array(
                        0 => array(
                            0 => 'owner',
                        ),
                        1 => 'owner',
                        2 => array(),
                    ),
                ),
                'deny' => array(),
            ),
        ),
        'guards' => array(
            'BjyAuthorize\\Guard\\Controller' => array(
                0 => array(
                    'controller' => 'zfcuser',
                    'roles' => array(
                        0 => 'user',
                        1 => 'admin',
                    ),
                ),
                1 => array(
                    'controller' => 'zfcuser',
                    'action' => 'login',
                    'roles' => array(
                        0 => 'guest',
                    ),
                ),
                2 => array(
                    'controller' => 'zfcuser',
                    'action' => 'logout',
                    'roles' => array(
                        0 => 'admin',
                        1 => 'owner',
                        2 => 'user',
                        3 => 'guest',
                    ),
                ),
                3 => array(
                    'controller' => 'zfcuser',
                    'action' => 'index',
                    'roles' => array(
                        0 => 'admin',
                        1 => 'owner',
                        2 => 'user',
                    ),
                ),
                4 => array(
                    'controller' => 'zfcuser',
                    'action' => 'register',
                    'roles' => array(
                        0 => 'guest',
                    ),
                ),
                5 => array(
                    'controller' => 'ZF\\Apigility\\Doctrine\\Admin\\Controller\\DoctrineMetadataService',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                6 => array(
                    'controller' => 'ZF\\Apigility\\Doctrine\\Admin\\Controller\\DoctrineRestService',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                7 => array(
                    'controller' => 'ZF\\Apigility\\Doctrine\\Admin\\Controller\\DoctrineAutodiscovery',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                8 => array(
                    'controller' => 'ZF\\Apigility\\Documentation\\Controller',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                9 => array(
                    'controller' => 'ZF\\OAuth2\\Controller\\Auth',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                10 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\Module',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                11 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\Dashboard',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                12 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\App',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                13 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\ModuleCreation',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                14 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\AuthenticationType',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                15 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\RpcService',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                16 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\Authentication',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                17 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\RestService',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                18 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\DbAdapter',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                19 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\DoctrineAdapter',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                20 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\Source',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                21 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\ContentNegotiation',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                22 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\Hydrators',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                23 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\Authorization',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                24 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\InputFilter',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                25 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\Validators',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                26 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\Filters',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                27 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\Documentation',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                28 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\App',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                29 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\CacheEnabled',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                30 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\FsPermissions',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                31 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\Strategy',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                32 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\Package',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                33 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\AuthenticationType',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                34 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\DbAutodiscovery',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                35 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\Dashboard',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                36 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\Documentation',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                37 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\Filters',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                38 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\Hydrators',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                39 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\InputFilter',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                40 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\SettingsDashboard',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                41 => array(
                    'controller' => 'ZF\\Apigility\\Admin\\Controller\\Validators',
                    'roles' => array(
                        0 => 'admin',
                    ),
                ),
                42 => array(
                    'controller' => 'CanariumCore\\Controller\\Index',
                    'roles' => array(
                        0 => 'admin',
                        1 => 'owner',
                        2 => 'guest',
                    ),
                ),
                43 => array(
                    'controller' => 'User',
                    'roles' => array(
                        0 => 'admin',
                        1 => 'owner',
                    ),
                ),
                44 => array(
                    'controller' => 'Admin\\CanariumCore',
                    'roles' => array(
                        0 => 'admin',
                        1 => 'owner',
                    ),
                ),
                45 => array(
                    'controller' => 'CanariumCore\\V1\\Rest\\User\\Controller',
                    'roles' => array(
                        0 => 'admin',
                        1 => 'owner',
                    ),
                ),
            ),
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'url_title_filter' => 'CanariumCore\\View\\Helper\\Filter\\Url',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'CanariumCore\\Controller\\Index' => 'CanariumCore\\Controller\\IndexController',
            'Admin\\CanariumCore' => 'CanariumCore\\Controller\\AdminController',
            'User' => 'CanariumCore\\Controller\\UserController',
            'zfcuser' => 'CanariumCore\\Controller\\UserController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'CanariumCore\\Controller\\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'canarium-core' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/canarium-core',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CanariumCore\\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(),
                        ),
                    ),
                ),
            ),
            'admin' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/admin',
                    'defaults' => array(
                        'controller' => 'Admin\\CanariumCore',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(),
            ),
            'oauth2callback' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/oauth2callback',
                    'defaults' => array(
                        'controller' => 'CanariumCore\\Controller\\Index',
                        'action' => 'oauth2callback',
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
                        'action' => 'index',
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
                                'action' => 'update-profile',
                            ),
                        ),
                    ),
                ),
            ),
            'canarium-core.rest.doctrine.user' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/user[/:user_id]',
                    'defaults' => array(
                        'controller' => 'CanariumCore\\V1\\Rest\\User\\Controller',
                    ),
                ),
            ),
        ),
    ),
    'db' => array(
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=skeleton;host=localhost',
        'driver_options' => array(
            1002 => 'SET NAMES \'UTF8\'',
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            0 => 'Zend\\Cache\\Service\\StorageCacheAbstractServiceFactory',
            1 => 'Zend\\Log\\LoggerAbstractServiceFactory',
            2 => 'Zend\\Db\\Adapter\\AdapterAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
            'zfcuser_zend_db_adapter' => 'Zend\\Db\\Adapter\\Adapter',
        ),
        'factories' => array(
            'admin' => 'CanariumCore\\Navigation\\Service\\AdminNavigationFactory',
            'navigation' => 'Zend\\Navigation\\Service\\DefaultNavigationFactory',
            'Zend\\Db\\Adapter\\Adapter' => 'Zend\\Db\\Adapter\\AdapterServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            0 => array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            0 => 'ViewJsonStrategy',
        ),
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/admin' => __DIR__ . '/../view/layout/admin.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
            'partial/paginator' => __DIR__ . '/../view/partial/paginator.phtml',
        ),
        'template_path_stack' => array(
            0 => __DIR__ . '/../view',
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(),
        ),
    ),
    'jhu' => array(
        'zdt_logger' => array(
            'logger' => 'Zend\\Log\\Logger',
        ),
    ),
    'zf-versioning' => array(
        'uri' => array(
            0 => 'canarium-core.rest.doctrine.user',
        ),
    ),
    'zf-rest' => array(
        'CanariumCore\\V1\\Rest\\User\\Controller' => array(
            'listener' => 'CanariumCore\\V1\\Rest\\User\\UserResource',
            'route_name' => 'canarium-core.rest.doctrine.user',
            'route_identifier_name' => 'user_id',
            'entity_identifier_name' => 'id',
            'collection_name' => 'user',
            'entity_http_methods' => array(
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ),
            'collection_http_methods' => array(
                0 => 'GET',
                1 => 'POST',
            ),
            'collection_query_whitelist' => array(),
            'page_size' => 25,
            'page_size_param' => 'limit',
            'entity_class' => 'CanariumCore\\Entity\\User',
            'collection_class' => 'CanariumCore\\V1\\Rest\\User\\UserCollection',
            'service_name' => 'User',
        ),
    ),
    'zf-content-negotiation' => array(
        'controllers' => array(
            'CanariumCore\\V1\\Rest\\User\\Controller' => 'HalJson',
        ),
        'accept-whitelist' => array(
            'CanariumCore\\V1\\Rest\\User\\Controller' => array(
                0 => 'application/vnd.canarium-core.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ),
        ),
        'content-type-whitelist' => array(
            'CanariumCore\\V1\\Rest\\User\\Controller' => array(
                0 => 'application/json',
            ),
        ),
    ),
    'zf-hal' => array(
        'metadata_map' => array(
            'CanariumCore\\Entity\\User' => array(
                'route_identifier_name' => 'user_id',
                'entity_identifier_name' => 'id',
                'route_name' => 'canarium-core.rest.doctrine.user',
                'hydrator' => 'CanariumCore\\V1\\Rest\\User\\UserHydrator',
            ),
            'CanariumCore\\V1\\Rest\\User\\UserCollection' => array(
                'entity_identifier_name' => 'id',
                'route_name' => 'canarium-core.rest.doctrine.user',
                'is_collection' => true,
            ),
        ),
    ),
    'zf-apigility' => array(
        'doctrine-connected' => array(
            'CanariumCore\\V1\\Rest\\User\\UserResource' => array(
                'object_manager' => 'doctrine.entitymanager.orm_default',
                'hydrator' => 'CanariumCore\\V1\\Rest\\User\\UserHydrator',
            ),
        ),
    ),
    'doctrine-hydrator' => array(
        'CanariumCore\\V1\\Rest\\User\\UserHydrator' => array(
            'entity_class' => 'CanariumCore\\Entity\\User',
            'object_manager' => 'doctrine.entitymanager.orm_default',
            'by_value' => true,
            'strategies' => array(),
            'use_generated_hydrator' => true,
        ),
    ),
    'zf-content-validation' => array(
        'CanariumCore\\V1\\Rest\\User\\Controller' => array(
            'input_filter' => 'CanariumCore\\V1\\Rest\\User\\Validator',
        ),
    ),
    'input_filter_specs' => array(
        'CanariumCore\\V1\\Rest\\User\\Validator' => array(
            0 => array(
                'name' => 'username',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => 255,
                        ),
                    ),
                ),
            ),
            1 => array(
                'name' => 'email',
                'required' => true,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => 255,
                        ),
                    ),
                ),
            ),
            2 => array(
                'name' => 'displayName',
                'required' => false,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => 50,
                        ),
                    ),
                ),
            ),
            3 => array(
                'name' => 'password',
                'required' => true,
                'filters' => array(
                    0 => array(
                        'name' => 'Zend\\Filter\\StringTrim',
                    ),
                    1 => array(
                        'name' => 'Zend\\Filter\\StripTags',
                    ),
                ),
                'validators' => array(
                    0 => array(
                        'name' => 'Zend\\Validator\\StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => 128,
                        ),
                    ),
                ),
            ),
            4 => array(
                'name' => 'lastLogin',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
        ),
    ),
);
