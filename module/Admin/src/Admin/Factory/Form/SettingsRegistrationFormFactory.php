<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.21
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace Admin\Factory\Form;

use Admin\Form\SettingsRegistrationForm;
use Zend\ServiceManager\ServiceLocatorInterface;

final class SettingsRegistrationFormFactory
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->getServiceLocator()->get('Config');

        $form = new SettingsRegistrationForm($config['system_config']['registration']);

        return $form;
    }
}
