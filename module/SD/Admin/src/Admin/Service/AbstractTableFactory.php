<?php

/**
 * @copyright  2015 - 2016 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

final class AbstractTableFactory implements AbstractFactoryInterface
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
        return strpos($requestedName, 'Table') && class_exists($requestedName) ? true : false;
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
        $entity = substr($requestedName, 0, -5); // remove Table from name
        $entity = str_replace('Model', 'Entity', $entity);
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $repository = $entityManager->getRepository($entity);

        return new $requestedName($entityManager, $repository);
    }
}
