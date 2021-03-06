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
use Zend\Session\Container;

class MenuRepository extends EntityRepository
{
    /**
     * @return array|object
     */
    public function getMenus()
    {
        $lang = new Container('translations');

        return $this->createQueryBuilder('m')->select('m')
                    ->where("m.active = 1 AND m.language = '".$lang->offsetGet('language')."'")
                    ->orderBy('m.parent', 'ASC')
                    ->getQuery()->getResult();
    }
}
