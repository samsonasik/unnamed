<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.23
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Factory;

use SD\Application\Controller\ErrorHandling;
use Zend\Log\Logger;

final class ErrorHandlingFactory
{
    /**
     * {@inheritdoc}
     */
    public function __invoke()
    {
        $logger = new ErrorHandling(new Logger());

        return $logger;
    }
}
