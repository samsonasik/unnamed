<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.23
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Factory\Form;

use SD\Admin\Form\SettingsPostsForm;
use Zend\ServiceManager\ServiceLocatorInterface;

final class SettingsPostsFormFactory
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->getServiceLocator()->get('Config');

        $form = new SettingsPostsForm($config['system_config']['posts']);

        return $form;
    }
}
