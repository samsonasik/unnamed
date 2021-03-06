<?php

$modules = [];

$modules[] = 'SD\Application';
$modules[] = 'SD\Admin';
$modules[] = 'SD\Themes';
$modules[] = 'DoctrineModule';
$modules[] = 'DoctrineORMModule';

if (getenv('APPLICATION_ENV') === 'development') {
    $modules[] = 'ZendDeveloperTools';
    $modules[] = 'SanSessionToolbar';
}

$config = [
    'modules'                 => $modules,
    'module_listener_options' => [
        'module_paths' => [
            __DIR__.'/../module',
            __DIR__.'/../vendor',
        ],
        'config_glob_paths' => [
            'config/autoload/{,*.}{global,local}.php',
        ],
        'config_cache_enabled'     => (getenv('APPLICATION_ENV') === 'production'),
        'config_cache_key'         => 'app_config',
        'module_map_cache_enabled' => (getenv('APPLICATION_ENV') === 'production'),
        'module_map_cache_key'     => 'module_map',
        'cache_dir'                => dirname(__DIR__).'/data/cache/modules',
        'check_dependencies'       => (getenv('APPLICATION_ENV') !== 'production'),
    ],
];

return $config;
