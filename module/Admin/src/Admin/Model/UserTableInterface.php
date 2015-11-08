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

interface UserTableInterface
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function queryBuilder();

    /**
     * @param \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder $query               A Doctrine ORM query or query builder.
     * @param bool                                           $fetchJoinCollection Whether the query joins a collection (true by default).
     *
     * @return ZendPaginator
     */
    public function preparePagination($query, $fetchJoinCollection = true);

    /**
     * @return object
     */
    public function getEntityRepository();

    /**
     * @param int $userId user id
     *
     * @return User
     */
    public function getUser($userId = 0);

    /**
     * This method can disable or enable user accounts.
     *
     * @param int $userId user id
     * @param int $state  0 - enabled, 1 - disabled
     */
    public function toggleUserState($userId = 0, $state = 0);

    /**
     * Update user based on the provided id.
     *
     * @param User $user
     *
     * @return User
     */
    public function saveUser(User $user);
}
