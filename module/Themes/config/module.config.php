<?php

return  [
  'router' => [
    'routes' => [
      'themes' => [
        'type' => 'Literal',
        'options' => [
          'route' => '/theme',
          'defaults' => [
            '__NAMESPACE__' => 'Themes\\Controller',
            'controller' => 'Index',
            'action' => 'index',
          ],
        ],
        'may_terminate' => true,
        'child_routes' => [
          'default' => [
            'type' => 'Segment',
            'options' => [
              'route' => '/[:controller[/:action]]',
              'constraints' => [
                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
              ],
            ],
          ],
        ],
      ],
    ],
  ],
  'controllers' => [
    'factories' => [
      'Themes\\Controller\\Index' => 'Themes\\Factory\\Controller\\IndexControllerFactory',
    ],
  ],
  'service_manager' => [
    'factories' => [
      'initThemes' => 'Themes\\Factory\\ThemesFactory',
      'getThemesFromDir' => 'Themes\\Factory\\GetThemesFromDir',
    ],
    'invokables' => [
      'reloadService' => 'Themes\\Service\\ReloadService',
    ],
  ],
  'theme' => [
    'name' => 'default',
  ],
  'view_manager' => [
    'template_path_stack' => [
      'themes' => 'C:\\xampp\\htdocs\\unnamed\\module\\Themes\\config/../view',
    ],
  ],
];
