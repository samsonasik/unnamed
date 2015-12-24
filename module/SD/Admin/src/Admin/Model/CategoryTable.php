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
use SD\Admin\Entity\Category;
use SD\Admin\Exception\RuntimeException;
use Zend\Paginator\Paginator as ZendPaginator;

final class CategoryTable implements CategoryTableInterface
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
     * @param int $catId category id
     *
     * @return Category
     */
    public function getCategory($catId = 0)
    {
        $category = $this->getEntityRepository()->findBy(['id' => $catId]);

        if (empty($category)) {
            throw new RuntimeException("Couldn't find category");
        }

        return $category[0];
    }

    /**
     * Delete a category based on the provided category id.
     *
     * @param int $catId category id
     */
    public function deleteCategory($catId = 0)
    {
        $category = $this->getCategory($catId);

        if ($category) {
            $this->objectManager->remove($category);
            $this->objectManager->flush();
        }
    }

    /**
     * Save or update category based on the provided id.
     *
     * @param Category $category
     *
     * @return Category
     */
    public function saveCategory(Category $category)
    {
        $this->objectManager->persist($category);
        $this->objectManager->flush();

        return $category;
    }
}
