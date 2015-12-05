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

use SD\Admin\Entity\Content;

interface ContentTableInterface
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
     * @param int $contentId content id
     * @param int $language  user language
     *
     * @throws RuntimeException If content is not found
     *
     * @return Content
     */
    public function getContent($contentId = 0, $language = 1);

    /**
     * Delete content based on the provided id and language.
     *
     * @param int $contentId content id
     * @param int $language  user language
     *
     * @return void
     */
    public function deleteContent($contentId = 0, $language = 1);

    /**
     * Save or update content based on the provided id and language.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function saveContent(Content $content);

    /**
     * This method can disable or enable contents.
     *
     * @param int $contentId content id
     * @param int $language  user language
     * @param int $state     0 - deactivated, 1 - active
     *
     * @return void
     */
    public function toggleActiveContent($contentId = 0, $language = 1, $state = 0);
}
