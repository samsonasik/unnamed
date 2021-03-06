<?php

/**
 * @copyright  2015 - 2016 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use SD\Admin\Entity\Role;
use SD\Admin\Exception\RuntimeException;
use Zend\Paginator\Paginator as ZendPaginator;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\GenericResource as AclResource;
use Zend\Permissions\Acl\Role\GenericRole as AclRole;

final class RoleTable extends Acl implements RoleTableInterface
{
    /*
     * @var ObjectManager
     */
    private $objectManager;

    /*
     * @var object
     */
    private $objectRepository;

    /**
     * @param ObjectManager    $objectManager
     * @param ObjectRepository $objectRepository
     */
    public function __construct(ObjectManager $objectManager, ObjectRepository $objectRepository)
    {
        $this->objectManager = $objectManager;
        $this->objectRepository = $objectRepository;

        /*
         * Default roles.
         */
        $this->addRole(new AclRole('Guest'));
        $this->addRole(new AclRole('User'), 'Guest');
        $this->addRole(new AclRole('Admin'), 'User');

        $this->addResource(new AclResource('Home')); // home / front page
        $this->addResource(new AclResource('Registration')); // home / front page
        $this->addResource(new AclResource('Login')); // home / front page
        $this->addResource(new AclResource('Admin')); // admin route

        $this->allow('Guest', 'Home', 'Home');
        $this->allow('Guest', 'Registration', ['Registration']);
        $this->allow('Guest', 'Login', ['Login']);
        $this->deny('Guest', 'Profile', ['ViewProfile']);
        $this->deny('Guest', 'Admin', ['AllowAll']);

        $this->allow('User', 'Profile', ['ViewProfile', 'EditProfile']);
        $this->deny('User', 'Registration', 'Registration');
        $this->deny('User', 'Admin', ['AllowAll']);

        $this->allow('Admin', 'Admin', ['AllowAll']);
        $this->allow('Admin', 'Home', ['AllowAll']);
    }

    /**
     * @return object
     */
    public function queryBuilder()
    {
        return $this->objectManager->createQueryBuilder();
    }

    /**
     * @param \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder $query               A Doctrine ORM query or query builder.
     * @param bool                                           $fetchJoinCollection Whether the query joins a collection (true by default).
     *
     * @return ZendPaginator
     */
    public function preparePagination($query, $fetchJoinCollection = true)
    {
        return new ZendPaginator(new PaginatorAdapter(new ORMPaginator($query, $fetchJoinCollection)));
    }

    /**
     * @return object
     */
    public function getEntityRepository()
    {
        return $this->objectRepository;
    }

    /**
     * @param int $roleId
     *
     * @return Role
     */
    public function getRole($roleId = 0)
    {
        $role = $this->getEntityRepository()->findBy(['id' => $roleId]);

        if (empty($role)) {
            throw new RuntimeException("Couldn't find role");
        }

        return $role[0];
    }

    /**
     * Delete a role based on the provided user id.
     *
     * @param int $roleId user id
     */
    public function deleteRole($roleId = 0)
    {
        $role = $this->getRole($roleId);

        if ($role) {
            $this->objectManager->remove($role[0]);
            $this->objectManager->flush();
        }
    }

    /**
     * Save or update role based on the provided id.
     *
     * @param Role $role
     *
     * @return Role
     */
    public function saveRole(Role $role)
    {
        $this->objectManager->persist($role);
        $this->objectManager->flush();

        return $role;
    }
}
