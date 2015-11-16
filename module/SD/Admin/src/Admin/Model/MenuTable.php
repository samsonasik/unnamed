<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.23
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use SD\Admin\Entity\Menu;
use SD\Admin\Exception\RuntimeException;
use Zend\Paginator\Paginator as ZendPaginator;

final class MenuTable implements MenuTableInterface
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
     * @param int $menuId   menu id
     * @param int $language user language
     *
     * @throws RuntimeException If menu is not found
     *
     * @return Menu
     */
    public function getMenu($menuId = 0, $language = 1)
    {
        $menu = $this->queryBuilder();
        $menu->select(['m']);
        $menu->from('SD\Admin\Entity\Menu', 'm');
        $menu->where('m.id = :id AND m.language = :language');
        $menu->setParameter(':id', (int) $menuId);
        $menu->setParameter(':language', (int) $language);
        $menu = $menu->getQuery()->getSingleResult();

        if (empty($menu)) {
            throw new RuntimeException("Couldn't find menu");
        }

        return $menu;
    }

    /**
     * Delete a menu based on the provided id and language.
     *
     * @param int $menuId   menu id
     * @param int $language user language
     */
    public function deleteMenu($menuId = 0, $language = 1)
    {
        $menu = $this->getMenu($menuId, $language);
        if ($menu) {
            $this->objectManager->remove($menu);
            $this->objectManager->flush();
        }
    }

    /**
     * @param Menu $menu
     *
     * @return Menu
     */
    public function saveMenu(Menu $menu)
    {
        $this->objectManager->persist($menu);
        $this->objectManager->flush();

        return $menu;
    }

    /**
     * This method can disable or enable menus.
     *
     * @param int $menuId   menu id
     * @param int $language user language
     * @param int $state    0 - deactivated, 1 - active
     */
    public function toggleActiveMenu($menuId = 0, $language = 1, $state = 0)
    {
        $menu = $this->getMenu($menuId, $language);

        if ($menu) {
            $menu->setActive((bool) $state);
            $this->saveMenu($menu);
        }
    }
}
