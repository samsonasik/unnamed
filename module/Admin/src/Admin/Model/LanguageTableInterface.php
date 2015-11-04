<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.21
 *
 * @link       TBA
 */
namespace Admin\Model;

use Admin\Entity\Language;

interface LanguageTableInterface
{
    /**
     * @return Doctrine\ORM\QueryBuilder
     */
    public function queryBuilder();

    /**
     * @param Query|QueryBuilder $query               A Doctrine ORM query or query builder.
     * @param bool               $fetchJoinCollection Whether the query joins a collection (true by default).
     *
     * @return Paginator
     */
    public function preparePagination($query, $fetchJoinCollection = true);

    /**
     * @return Admin\Entity\Language
     */
    public function getEntityRepository();

    /**
     * @param int $languageId
     *
     * @throws RuntimeException
     *
     * @return Language
     */
    public function getLanguage($languageId = 0);

    /**
     * @param int $languageId
     *
     * @return Language
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
