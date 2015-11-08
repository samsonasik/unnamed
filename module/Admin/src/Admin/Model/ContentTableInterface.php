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

use Admin\Entity\Content;

interface ContentTableInterface
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function queryBuilder();

    /**
     * @param \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder $query               A Doctrine ORM query or query builder.
     * @param bool                                           $fetchJoinCollection Whether the query joins a collection (true by default).
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
     */
    public function getContent($contentId = 0, $language = 1);

    /**
     * Delete content based on the provided id and language.
     *
     * @param int $contentId content id
     * @param int $language  user language
     */
    public function deleteContent($contentId = 0, $language = 1);

    /**
     * Save or update content based on the provided id and language.
     *
     * @param Content $content
     */
    public function saveContent(Content $content);

    /**
     * This method can disable or enable contents.
     *
     * @param int $contentId content id
     * @param int $language  user language
     * @param int $state     0 - deactivated, 1 - active
     */
    public function toggleActiveContent($contentId = 0, $language = 1, $state = 0);
}
