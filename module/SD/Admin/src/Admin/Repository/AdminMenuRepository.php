<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.22
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Repository;

use Doctrine\ORM\EntityRepository;

class AdminMenuRepository extends EntityRepository
{
    /**
     * @return array|object
     */
    public function getParentMenus()
    {
        return $this->createQueryBuilder('m')->select('m')
                    ->where("m.parent = '0'")
                    ->getQuery()->getResult();
    }
}
