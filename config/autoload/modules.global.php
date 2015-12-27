<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */

/*
 * All configurations options, used in two or more modules must go in here.
 */
return [
    'service_manager' => [
        'abstract_factories' => [
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Form\FormAbstractServiceFactory',
            'SD\Admin\Service\AbstractTableFactory',
        ],
        'factories' => [
            'translator'    => 'Zend\Mvc\Service\TranslatorServiceFactory',
            'ErrorHandling' => 'SD\Application\Factory\ErrorHandlingFactory',
            'initSession'   => 'SD\Application\Factory\SessionFactory',
        ],
    ],
    'controllers' => [
        'abstract_factories' => [
            'SD\Admin\Service\AbstractControllerFactory',
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            'translate'         => 'SD\Application\Controller\Plugin\Factory\TranslateFactory',
            'Mailing'           => 'SD\Application\Controller\Plugin\Factory\MailingFactory',
            'UserData'          => 'SD\Application\Controller\Plugin\Factory\UserDataFactory',
            'InitMetaTags'      => 'SD\Application\Controller\Plugin\Factory\InitMetaTagsFactory',
            'getParam'          => 'SD\Application\Controller\Plugin\Factory\GetUrlParamsFactory',
            'getTable'          => 'SD\Application\Controller\Plugin\Factory\GetTableModelFactory',
            'getFunctions'      => 'SD\Application\Controller\Plugin\Factory\FunctionsFactory',
            'setErrorCode'      => 'SD\Application\Controller\Plugin\Factory\ErrorCodesFactory',
            'systemSettings'    => 'SD\Application\Controller\Plugin\Factory\SystemSettingsFactory',
        ],
        'invokables' => [
            'setLayoutMessages' => 'SD\Application\Controller\Plugin\SetLayoutMessages',
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'translate' => 'SD\Application\View\Helper\Factory\TranslateHelperFactory',
        ],
    ],
    'shared' => [
        'InitMetaTags' => false,
    ],
    'translator' => [
        'locale'                    => 'en',
        'translation_file_patterns' => [
            [
                'type'        => 'phpArray',
                'base_dir'    => __DIR__.'/../../vendor/zendframework/zend-i18n-resources/languages',
                'pattern'     => '%s/Zend_Captcha.php',
                'text_domain' => 'formandtitle',
            ],
            [
                'type'        => 'phpArray',
                'base_dir'    => __DIR__.'/../../vendor/zendframework/zend-i18n-resources/languages',
                'pattern'     => '%s/Zend_Validate.php',
                'text_domain' => 'formandtitle',
            ],
            [
                'type'        => 'phpArray',
                'base_dir'    => __DIR__.'/../../module/SD/Themes/themes/default/languages/phpArray',
                'pattern'     => '%s.php',
                'text_domain' => 'SD_Translations',
            ],
        ],
        'cache' => [
            'adapter' => [
                'name'    => 'Filesystem',
                'options' => [
                    'cache_dir' => __DIR__.'/../../data/cache/frontend',
                    'ttl'       => '604800',
                ],
            ],
            'plugins' => [
                [
                    'name'    => 'serializer',
                    'options' => [],
                ],
                'exception_handler' => [
                    'throw_exceptions' => (getenv('APPLICATION_ENV') === 'development'),
                ],
            ],
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => (getenv('APPLICATION_ENV') === 'development'),
        'display_exceptions'       => (getenv('APPLICATION_ENV') === 'development'),
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/index',
        'exception_template'       => 'error/index',
        'default_template_suffix'  => 'phtml',
        'strategies'               => [
            'ViewJsonStrategy',
        ],
    ],
    'doctrine' => [
        'authentication' => [
            'orm_default' => [
                'object_manager'      => 'Doctrine\ORM\EntityManager',
                'identity_class'      => 'SD\Admin\Entity\User',
                'identity_property'   => 'email',
                'credential_property' => 'password',
                'credential_callable' => function (\SD\Admin\Entity\User $user, $passwordProvided) {
                    return password_verify($passwordProvided, $user->getPassword());
                },
            ],
        ],
    ],
];
