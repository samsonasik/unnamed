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

use Admin\Form\UserForm;
use Zend\ServiceManager\ServiceLocatorInterface;

final class UserFormFactory
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        $services = $serviceLocator->getServiceLocator();

        $entityManager = $services->get('Doctrine\ORM\EntityManager');

        $form = new UserForm($entityManager);

        return $form;
    }
}
