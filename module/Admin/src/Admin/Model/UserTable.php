<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.21
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace Admin\Model;

use Admin\Entity\User;
use Admin\Exception\RuntimeException;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use Zend\Paginator\Paginator as ZendPaginator;

final class UserTable implements UserTableInterface
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
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
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
     * @param int $userId user id
     *
     * @return User
     */
    public function getUser($userId = 0)
    {
        $user = $this->getEntityRepository()->find($userId);

        if (empty($user)) {
            throw new RuntimeException("Couldn't find user");
        }

        return $user;
    }

    /**
     * This method can disable or enable user accounts.
     *
     * @param int $userId user id
     * @param int $state  0 - enabled, 1 - disabled
     */
    public function toggleUserState($userId = 0, $state = 0)
    {
        $user = $this->getUser($userId);
        if ($user) {
            $user->setDisabled((int) $state);
            $user->setAdmin(0); // doesn't matter the $state. Remove user admin rights.
            $this->saveUser($user);
        }
    }

    /**
     * Update user based on the provided id.
     *
     * @param User $user
     *
     * @return User
     */
    public function saveUser(User $user)
    {
        $this->objectManager->persist($user);
        $this->objectManager->flush();

        return $user;
    }
}
