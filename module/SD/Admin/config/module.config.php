<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.23
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin;

return [
    'router' => [
        'routes' => [
            'admin' => [
                'type'    => 'Zend\Mvc\Router\Http\Literal',
                'options' => [
                    'route'    => '/admin',
                    'defaults' => [
                        'controller' => 'SD\Admin\Controller\Index',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/[:controller[/][:action[/][[:id][:themeName]][page/:page][/search/:search]]]',
                            'constraints' => [
                                'controller' => '[a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z0-9_-]*',
                                'search'     => '[a-zA-Z0-9_-]*',
                                'id'         => '[0-9]+',
                                'themeName'  => '[a-zA-Z0-9_-]*',
                                'page'       => '[0-9]+',
                            ],
                            'defaults' => [
                                '__NAMESPACE__' => 'SD\Admin\Controller',
                                'controller'    => 'Index',
                                'action'        => 'index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            'SD\Admin\Controller\Settings' => Factory\Controller\SettingsControllerFactory::class,
        ],
        'invokables' => [
            'SD\Admin\Controller\Base'  => Controller\BaseController::class,
            'SD\Admin\Controller\Index' => Controller\IndexController::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            'SD\Admin\Form\SettingsMailForm'         => Factory\Form\SettingsMailFormFactory::class,
            'SD\Admin\Form\SettingsPostsForm'        => Factory\Form\SettingsPostsFormFactory::class,
            'SD\Admin\Form\SettingsGeneralForm'      => Factory\Form\SettingsGeneralFormFactory::class,
            'SD\Admin\Form\SettingsDiscussionForm'   => Factory\Form\SettingsDiscussionFormFactory::class,
            'SD\Admin\Form\SettingsRegistrationForm' => Factory\Form\SettingsRegistrationFormFactory::class,
        ],
    ],
    'view_manager' => [

        'template_map' => include __DIR__.'/../template_map.php',
    ],
    'doctrine' => [
        'driver' => [
            'SD_Admin_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    dirname(__DIR__).'/src/Admin/Entity',
                ],
            ],
            'orm_default' => [
                'drivers' => [
                    'SD\Admin\Entity' => 'SD_Admin_driver',
                ],
            ],
        ],
    ],
];
