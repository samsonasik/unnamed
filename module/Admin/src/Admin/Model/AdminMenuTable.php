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

use Admin\Entity\AdminMenu;
use Admin\Exception\RuntimeException;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use Zend\Paginator\Paginator as ZendPaginator;

final class AdminMenuTable implements AdminMenuTableInterface
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
     * @param int $adminMenuId admin menu id
     *
     * @throws RuntimeException If admin menu is not found
     *
     * @return AdminMenu
     */
    public function getAdminMenu($adminMenuId = 0)
    {
        $adminMenu = $this->getEntityRepository()->find($adminMenuId);

        if (empty($adminMenu)) {
            throw new RuntimeException("Couldn't find admin menu");
        }

        return $adminMenu;
    }

    /**
     * Delete a admin menu based on the provided id.
     *
     * @param int $adminMenuId admin menu id
     */
    public function deleteAdminMenu($adminMenuId = 0)
    {
        $adminMenu = $this->getAdminMenu($adminMenuId);

        if ($adminMenu) {
            $this->objectManager->remove($adminMenu);
            $this->objectManager->flush();
        }
    }

    /**
     * Save or update menu based on the provided id and language.
     *
     * @param AdminMenu $adminMenu
     *
     * @return AdminMenu
     */
    public function saveAdminMenu(AdminMenu $adminMenu)
    {
        $this->objectManager->persist($adminMenu);
        $this->objectManager->flush();

        return $adminMenu;
    }
}
