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

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use SD\Admin\Entity\Content;
use SD\Admin\Exception\RuntimeException;
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
        $content->from('SD\Admin\Entity\Content', 'c');
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
     * @return object
     */
    public function getNewsContent($languageId = 1)
    {
        return $this->queryBuilder()->select(['c'])
                                ->from('SD\Admin\Entity\Content', 'c')
                                ->where('c.type = 1 AND c.language = :language')
                                ->setParameter(':language', (int) $languageId)
                                ->orderBy('c.date DESC');
    }

    /**
     * @return object
     */
    public function getMenuContent($languageId = 1)
    {
        return $this->queryBuilder()
                    ->getEntityManager()
                    ->createQuery('SELECT c FROM SD\Admin\Entity\Content AS c LEFT JOIN SD\Admin\Entity\Menu AS m WITH c.menu=m.id WHERE c.type = 0 AND c.language = :language ORDER BY m.parent ASC, m.menuOrder ASC, c.date DESC')
                    ->setParameter(':language', $languageId);
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
