<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.23
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

final class AbstractControllerFactory implements AbstractFactoryInterface
{
    /**
     * Determine if we can create a service with name.
     *
     * @method canCreateServiceWithName
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param string                  $name
     * @param string                  $requestedName
     *
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $requestedName = $requestedName.'Controller';

        if ($requestedName !== 'SD\Admin\Controller\SettingsController') {
            return class_exists($requestedName);
        }

        return false;
    }

    /**
     * Create service with name.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param string                  $name
     * @param string                  $requestedName
     *
     * @return object
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();
        $className = $requestedName.'Controller';
        $formInstance = str_replace('Controller', 'Form', $className);
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $form = $serviceLocator->get('FormElementManager')->get($formInstance);
        $form->setObjectManager($entityManager);

        return new $className($form);
    }
}
