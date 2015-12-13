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

use SD\Admin\Entity\Category;

interface CategoryTableInterface
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
     * @param int $catId category id
     *
     * @return Category
     */
    public function getCategory($catId = 0);

    /**
     * Delete a category based on the provided id.
     *
     * @param int $catId category id
     *
     * @return void
     */
    public function deleteCategory($catId = 0);

    /**
     * Save or update category based on the provided id.
     *
     * @param Category $category
     *
     * @return Category
     */
    public function saveCategory(Category $category);
}
