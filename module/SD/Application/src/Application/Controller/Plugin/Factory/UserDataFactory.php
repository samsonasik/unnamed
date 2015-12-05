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

use SD\Application\Controller\Plugin\UserData;

final class UserDataFactory
{
    /**
     * {@inheritdoc}
     *
     * @return UserData
     */
    public function __invoke()
    {
        $plugin = new UserData();

        return $plugin;
    }
}
