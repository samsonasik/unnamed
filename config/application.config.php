<?php

$env = getenv('APPLICATION_ENV');
$modules = [];

$modules[] = 'SD\Application';
$modules[] = 'SD\Admin';
$modules[] = 'SD\Themes';
$modules[] = 'DoctrineModule';
$modules[] = 'DoctrineORMModule';

if ($env === 'development') {
    $modules[] = 'ZendDeveloperTools';
    $modules[] = 'SanSessionToolbar';
}

$config = [
    'modules'                 => $modules,
    'module_listener_options' => [
        'module_paths' => [
            './module',
            './vendor',
        ],
        'config_glob_paths' => [
            'config/autoload/{,*.}{global,local}.php',
        ],
        'config_cache_enabled'     => ($env === 'production'),
        'config_cache_key'         => 'app_config',
        'module_map_cache_enabled' => ($env === 'production'),
        'module_map_cache_key'     => 'module_map',
        'cache_dir'                => dirname(__DIR__).'/data/cache/modules',
        'check_dependencies'       => ($env !== 'production'),
    ],
];

return $config;
