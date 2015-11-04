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
use Admin\Exception\RuntimeException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use Zend\Paginator\Paginator as ZendPaginator;

class LanguageTable implements LanguageTableInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Doctrine\ORM\QueryBuilder
     */
    public function queryBuilder()
    {
        return $this->entityManager->createQueryBuilder();
    }

    /**
     * @param Query|QueryBuilder $query               A Doctrine ORM query or query builder.
     * @param bool               $fetchJoinCollection Whether the query joins a collection (true by default).
     *
     * @return Paginator
     */
    public function preparePagination($query, $fetchJoinCollection = true)
    {
        return new ZendPaginator(new PaginatorAdapter(new ORMPaginator($query, $fetchJoinCollection)));
    }

    /**
     * @return Admin\Entity\Language
     */
    public function getEntityRepository()
    {
        return $this->entityManager->getRepository("Admin\Entity\Language");
    }

    /**
     * @param int $languageId
     *
     * @throws RuntimeException
     *
     * @return Language
     */
    public function getLanguage($languageId = 1)
    {
        $language = $this->getEntityRepository()->find($languageId);

        if (empty($language)) {
            throw new RuntimeException("Couldn't find language");
        }

        return $language;
    }

    /**
     * @param int $languageId
     *
     * @return Language
     */
    public function deleteLanguage($languageId = 0)
    {
        $language = $this->getLanguage($languageId);
        if ($language) {
            $this->entityManager->remove($language);
            $this->entityManager->flush();
        }
    }

    /**
     * Save or update language based on the provided id.
     *
     * @param Language $language
     *
     * @return Language
     */
    public function saveLanguage(Language $language)
    {
        $this->entityManager->persist($language);
        $this->entityManager->flush();

        return $language;
    }
}
