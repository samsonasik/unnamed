<?php

/**
 * @copyright  2015 - 2016 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class CategoryRepository extends EntityRepository
{
    /**
     * @return array|object
     */
    public function getCategories()
    {
        $rsm = new ResultSetMapping();
        $qb = $this->createQueryBuilder('ca')
                    ->select(['ca', 'cc', 'c'])
                    ->leftJoin('cats', 'cc', 'ON', 'cc.category_id = ca.id')
                    ->leftJoin('content', 'c', 'ON', 'cc.content_id = c.id')->getQuery()->getResult();
        // $em = $this->getEntityManager()->createQuery("SELECT * FROM SD\Admin\Entity\Category", $rsm);

        echo \Zend\Debug\Debug::dump($qb, null, false);
        exit;
        $qb = $this->createQueryBuilder('p');

        return $this->createQueryBuilder(['ca'])
                    ->select('ca')
                    ->leftJoin('c')
                    ->orderBy('ca.id', 'ASC')
                    ->getQuery()->getResult();
    }
}
