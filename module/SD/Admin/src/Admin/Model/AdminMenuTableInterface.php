<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.22
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Model;

use SD\Admin\Entity\AdminMenu;

interface AdminMenuTableInterface
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
     * @param int $adminMenuId admin menu id
     *
     * @throws RuntimeException If admin menu is not found
     *
     * @return AdminMenu
     */
    public function getAdminMenu($adminMenuId = 0);

    /**
     * Delete a admin menu based on the provided id.
     *
     * @param int $adminMenuId admin menu id
     *
     * @return void
     */
    public function deleteAdminMenu($adminMenuId = 0);

    /**
     * Save or update menu based on the provided id and language.
     *
     * @param AdminMenu $adminMenu
     *
     * @return AdminMenu
     */
    public function saveAdminMenu(AdminMenu $adminMenu);
}
