<?php

namespace SD\Themes;

return  [
    'router' => [
    'routes' => [
        'themes' => [
        'type'    => 'Literal',
        'options' => [
            'route'    => '/theme',
            'defaults' => [
            '__NAMESPACE__' => 'SD\Themes\Controller',
            'controller'    => 'Index',
            'action'        => 'index',
            ],
        ],
        'may_terminate' => true,
        'child_routes'  => [
            'default' => [
            'type'    => 'Segment',
            'options' => [
                'route'       => '/[:controller[/:action]]',
                'constraints' => [
                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                ],
            ],
            ],
        ],
        ],
    ],
    ],
    'controllers' => [
    'factories' => [
        'SD\Themes\Controller\Index' => Factory\Controller\IndexControllerFactory::class,
    ],
    ],
    'service_manager' => [
    'factories' => [
        'initThemes'       => Factory\ThemesFactory::class,
        'getThemesFromDir' => Factory\GetThemesFromDir::class,
    ],
    'invokables' => [
        'reloadService' => Service\ReloadService::class,
    ],
    ],
    'theme' => [
    'name' => 'default',
    ],
    'view_manager' => [
    'template_path_stack' => [
        'themes' => __DIR__.'/../view',
    ],
    ],
];
