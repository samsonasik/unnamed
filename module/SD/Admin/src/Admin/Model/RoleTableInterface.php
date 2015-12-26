<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Model;

use SD\Admin\Entity\Role;

interface RoleTableInterface
{
    /**
     * @return object
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
     * @param int $adminId user id
     *
     * @return Role
     */
    public function getRole($adminId = 0);

    /**
     * Delete a role based on the provided user id.
     *
     * @param int $adminId user id
     *
     * @return void
     */
    public function deleteRole($adminId = 0);

    /**
     * Save or update role based on the provided id.
     *
     * @param Role $role
     *
     * @return Role
     */
    public function saveRole(Role $role);

    /**
     * Get Resource by Role.
     *
     * @param int $roleId
     *
     * @return array
     */
    public function getResourceByRole($roleId);

    /**
     * Get Right lists.
     *
     * @param int $roleId
     *
     * @return array
     */
    public function getRightLists($roleId = null);
}
