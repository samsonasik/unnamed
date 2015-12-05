<?php

/**
 * @copyright  2015 (c] Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application;

return [
    'router' => [
        'routes' => [
            'application' => [
                'type'    => 'Zend\Mvc\Router\Http\Literal',
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => 'SD\Application\Controller\Index',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '[:controller[/][:action[/[:id][token/:token][:post]][/page/:page][/search/:search]]]',
                            'constraints' => [
                                'controller' => '[a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z0-9_-]*',
                                'token'      => '[a-zA-Z-+_/&0-9]*',
                                'post'       => '[a-zA-Z0-9_-]*',
                                'search'     => '[a-zA-Z0-9_-]*',
                                'id'         => '[0-9]+',
                                'page'       => '[0-9]+',
                            ],
                            'defaults' => [
                                '__NAMESPACE__' => 'SD\Application\Controller',
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
            'SD\Application\Controller\Login' => Factory\Controller\LoginControllerFactory::class,
        ],
        'invokables' => [
            'SD\Application\Controller\Base'    => Controller\BaseController::class,
            'SD\Application\Controller\Index'   => Controller\IndexController::class,
            'SD\Application\Controller\News'    => Controller\NewsController::class,
            'SD\Application\Controller\Menu'    => Controller\MenuController::class,
            'SD\Application\Controller\Profile' => Controller\ProfileController::class,
        ],
    ],
    'doctrine' => [
        'driver' => [
            'SD_Application_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    dirname(__DIR__).'/src/Application/Entity',
                ],
            ],
            'orm_default' => [
                'drivers' => [
                    'SD\Application\Entity' => 'SD_Application_driver',
                ],
            ],
        ],
    ],
];
