<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Controller\Plugin\Factory;

use SD\Application\Controller\Plugin\Functions;

class FunctionsFactory
{
    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $plugin = new Functions();

        return $plugin;
    }
}
