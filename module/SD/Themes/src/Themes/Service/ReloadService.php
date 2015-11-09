<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.22
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Themes\Service;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;

final class ReloadService implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    public function reload()
    {
        $this->getEventManager()->trigger(__FUNCTION__, $this);
    }
}
