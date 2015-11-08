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
use Admin\Exception\RuntimeException;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use Zend\Paginator\Paginator as ZendPaginator;

final class ContentTable implements ContentTableInterface
{
    /*
     * @var ObjectManager
     */
    private $objectManager;

    /*
     * @var object
     */
    private $objectRepository;

    /**
     * @param ObjectManager    $objectManager
     * @param ObjectRepository $objectRepository
     */
    public function __construct(ObjectManager $objectManager, ObjectRepository $objectRepository)
    {
        $this->objectManager = $objectManager;
        $this->objectRepository = $objectRepository;
    }

    /**
     * @return object
     */
    public function queryBuilder()
    {
        return $this->objectManager->createQueryBuilder();
    }

    /**
     * @param \Doctrine\ORM\Query|\Doctrine\ORM\QueryBuilder $query               A Doctrine ORM query or query builder.
     * @param bool                                           $fetchJoinCollection Whether the query joins a collection (true by default).
     *
     * @return ZendPaginator
     */
    public function preparePagination($query, $fetchJoinCollection = true)
    {
        return new ZendPaginator(new PaginatorAdapter(new ORMPaginator($query, $fetchJoinCollection)));
    }

    /**
     * @return object
     */
    public function getEntityRepository()
    {
        return $this->objectRepository;
    }

    /**
     * @param int $contentId content id
     * @param int $language  user language
     *
     * @throws RuntimeException If content is not found
     *
     * @return Content
     */
    public function getContent($contentId = 0, $language = 1)
    {
        $content = $this->queryBuilder();
        $content->select(['c']);
        $content->from('Admin\Entity\Content', 'c');
        $content->where('c.id = :id AND c.language = :language');
        $content->setParameter(':id', (int) $contentId);
        $content->setParameter(':language', (int) $language);
        $content = $content->getQuery()->getSingleResult();

        if (empty($content)) {
            throw new RuntimeException("Couldn't find content");
        }

        return $content;
    }

    /**
     * Delete content based on the provided id and language.
     *
     * @param int $contentId content id
     * @param int $language  user language
     */
    public function deleteContent($contentId = 0, $language = 1)
    {
        $content = $this->getContent($contentId, $language);
        if ($content) {
            $this->objectManager->remove($content);
            $this->objectManager->flush();
        }
    }

    /**
     * Save or update content based on the provided id and language.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function saveContent(Content $content)
    {
        $this->objectManager->persist($content);
        $this->objectManager->flush();

        return $content;
    }

    /**
     * This method can disable or enable contents.
     *
     * @param int $contentId content id
     * @param int $language  user language
     * @param int $state     0 - deactivated, 1 - active
     */
    public function toggleActiveContent($contentId = 0, $language = 1, $state = 0)
    {
        $menu = $this->getContent($contentId, $language);

        if ($menu) {
            $menu->setActive((int) $state);
            $this->saveContent($menu);
        }
    }
}
