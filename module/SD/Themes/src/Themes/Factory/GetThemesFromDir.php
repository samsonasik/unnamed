<?php

/**
 * @copyright  2015 - 2016 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Themes\Factory;

use DirectoryIterator;

final class GetThemesFromDir
{
    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $path = __DIR__.'/../../../themes/';
        $dir = new DirectoryIterator($path);
        $themesConfig = [];

        foreach ($dir as $file) {
            if (!$file->isDot()) {
                $hasConfig = $path.$file->getBasename().'/config/module.config.php';

                if (is_file($hasConfig)) {
                    $themesConfig['themes'][$file->getBasename()] = include $hasConfig;
                }
            }
        }

        return $themesConfig;
    }
}
