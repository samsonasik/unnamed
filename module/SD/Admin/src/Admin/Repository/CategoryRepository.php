<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Repository;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    /**
     * @return array|object
     */
    public function getCategories()
    {
        return $this->createQueryBuilder('c')->select('c')
                    ->orderBy('c.id', 'ASC')
                    ->getQuery()->getResult();
    }
}
