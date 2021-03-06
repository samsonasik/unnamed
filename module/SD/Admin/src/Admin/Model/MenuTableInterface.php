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

use SD\Admin\Entity\Menu;

interface MenuTableInterface
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
     * @param int $menuId   menu id
     * @param int $language user language
     *
     * @throws RuntimeException If menu is not found
     *
     * @return Menu
     */
    public function getMenu($menuId = 0, $language = 1);

    /**
     * Delete a menu based on the provided id and language.
     *
     * @param int $menuId   menu id
     * @param int $language user language
     *
     * @return void
     */
    public function deleteMenu($menuId = 0, $language = 1);

    /**
     * @param Menu $menu
     *
     * @return Menu
     */
    public function saveMenu(Menu $menu);

    /**
     * This method can disable or enable menus.
     *
     * @param int $menuId   menu id
     * @param int $language user language
     * @param int $state    0 - deactivated, 1 - active
     *
     * @return void
     */
    public function toggleActiveMenu($menuId = 0, $language = 1, $state = 0);
}
