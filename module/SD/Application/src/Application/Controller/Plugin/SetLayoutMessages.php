<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.23
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Controller\Plugin;

use RecursiveArrayIterator;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\Container;
use Zend\Stdlib\SplQueue;

final class SetLayoutMessages extends AbstractPlugin
{
    /**
     * This method will iterate over an array and show its contents as separated strings.
     * The method will accept an array with unlimited depth.
     *
     * <code>
     *     $myArray = [
     *         0 => 'A',
     *         1 => ['subA','subB',
     *                  [0 => 'subsubA', 1 => 'subsubB',
     *                      2 => [0 => 'subsubsubA', 1 => 'subsubsubB']
     *                  ]
     *              ],
     *         2 => 'B',
     *         3 => ['subA','subB','subC'],
     *         4 => 'C'
     *     ];
     *
     *     $myArray = "Another way is to pass only a string";
     *
     *     $this->setLayoutMessages($myArray, "default");
     * </code>
     *
     * @param array|string|RecursiveArrayIterator $message
     * @param string                              $namespace determinate the message layout and color.
     *
     * @return Container|SplQueue
     */
    public function __invoke($message = [], $namespace = 'default')
    {
        if (!in_array($namespace, ['success', 'error', 'warning', 'info', 'default'])) {
            $namespace = 'default';
        }

        $iterator = new RecursiveArrayIterator((array) $message);
        $trans = new Container('translations');

        while ($iterator->valid()) {
            if ($iterator->hasChildren()) {
                $this->__invoke($iterator->getChildren(), $namespace);
            } else {
                if (!isset($trans->flashMessages)
                    || ! $trans->flashMessages instanceof SplQueue
                ) {
                    $trans->flashMessages = new SplQueue();
                }
                $arr[$namespace] = $iterator->current();
                $trans->flashMessages->push($arr);
            }
            $iterator->next();
        }

        $trans->setExpirationHops(1, ['flashMessages']);

        return $trans;
    }
}
