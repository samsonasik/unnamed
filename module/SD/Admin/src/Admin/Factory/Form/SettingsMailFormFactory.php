<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Factory\Form;

use SD\Admin\Form\SettingsMailForm;
use Zend\ServiceManager\ServiceLocatorInterface;

final class SettingsMailFormFactory
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->getServiceLocator()->get('Config');

        $form = new SettingsMailForm($config['system_config']['mail']);

        return $form;
    }
}
