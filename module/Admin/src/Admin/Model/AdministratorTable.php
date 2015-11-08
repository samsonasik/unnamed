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

use Admin\Entity\Administrator;
use Admin\Exception\RuntimeException;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use Zend\Paginator\Paginator as ZendPaginator;

final class AdministratorTable implements AdministratorTableInterface
{
    /*
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /*
     * @var object
     */
    private $objectRepository;

    /**
     * @param ObjectManager $entityManager
     * @param ObjectRepository $objectRepository
     */
    public function __construct(ObjectManager $entityManager, ObjectRepository $objectRepository)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $objectRepository;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function queryBuilder()
    {
        return $this->entityManager->createQueryBuilder();
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
     * @param int $adminId user id
     *
     * @return Administrator
     */
    public function getAdministrator($adminId = 0)
    {
        $administrator = $this->getEntityRepository()->findBy(['user' => $adminId]);

        if (empty($administrator)) {
            throw new RuntimeException("Couldn't find administrator");
        }

        return $administrator[0];
    }

    /**
     * Delete a administrator based on the provided user id.
     *
     * @param int $adminId user id
     */
    public function deleteAdministrator($adminId = 0)
    {
        $administrator = $this->getAdministrator($adminId);

        if ($administrator) {
            $this->entityManager->remove($administrator[0]);
            $this->entityManager->flush();
        }
    }

    /**
     * Save or update administrator based on the provided id.
     *
     * @param Administrator $administrator
     *
     * @return Administrator
     */
    public function saveAdministrator(Administrator $administrator)
    {
        $this->entityManager->persist($administrator);
        $this->entityManager->flush();

        return $administrator;
    }
}
