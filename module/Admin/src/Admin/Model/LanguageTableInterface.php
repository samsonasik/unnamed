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

use Admin\Entity\Language;

interface LanguageTableInterface
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
     * @param int $languageId
     *
     * @return Language
     */
    public function getLanguage($languageId = 0);

    /**
     * @param int $languageId
     */
    public function deleteLanguage($languageId = 0);

    /**
     * Save or update language based on the provided id.
     *
     * @param Language $language
     *
     * @return Language
     */
    public function saveLanguage(Language $language);
}
