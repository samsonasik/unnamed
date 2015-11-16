<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.23
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Repository;

use Doctrine\ORM\EntityRepository;

class LanguageRepository extends EntityRepository
{
    /**
     * @return array|object
     */
    public function getLanguages()
    {
        return $this->createQueryBuilder('l')->select('l')
                    ->where('l.active = 1')
                    ->orderBy('l.id', 'ASC')
                    ->getQuery()->getResult();
    }
}
