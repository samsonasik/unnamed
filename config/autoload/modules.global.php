<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.23
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
$env = getenv('APPLICATION_ENV');

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
            'setLayoutMessages' => 'SD\Application\Controller\Plugin\Factory\SetLayoutMessagesFactory',
            'InitMetaTags'      => 'SD\Application\Controller\Plugin\Factory\InitMetaTagsFactory',
            'getParam'          => 'SD\Application\Controller\Plugin\Factory\GetUrlParamsFactory',
            'getTable'          => 'SD\Application\Controller\Plugin\Factory\GetTableModelFactory',
            'getFunctions'      => 'SD\Application\Controller\Plugin\Factory\FunctionsFactory',
            'setErrorCode'      => 'SD\Application\Controller\Plugin\Factory\ErrorCodesFactory',
            'systemSettings'    => 'SD\Application\Controller\Plugin\Factory\SystemSettingsFactory',
        ],
    ],
    'shared' => [
        'InitMetaTags' => false,
    ],
    'translator' => [
        'locale'                    => 'en',
        'translation_file_patterns' => [
            [
                'base_dir' => __DIR__.'/../../module/SD/Application/languages/phpArray',
                'type'     => 'phpArray',
                'pattern'  => '%s.php',
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
                    'throw_exceptions' => ($env === 'development'),
                ],
            ],
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => ($env === 'development'),
        'display_exceptions'       => ($env === 'development'),
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
