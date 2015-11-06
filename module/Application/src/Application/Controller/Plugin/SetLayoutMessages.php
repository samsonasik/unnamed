<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.21
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\View\Model\ViewModel;

final class SetLayoutMessages extends AbstractPlugin
{
    /**
     * @var FlashMessenger
     */
    private $flashMessenger = null;

    /**
     * @var ViewModel
     */
    private $layout = null;

    public function __construct(ViewModel $layout = null, FlashMessenger $flashMessenger = null)
    {
        $this->layout = $layout;
        $this->flashMessenger = $flashMessenger;
    }

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
     * @param array|string $message
     * @param string       $namespace determinate the message layout and color. It's also used for the flashMessenger namespace
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function __invoke($message = [], $namespace = 'default')
    {
        $this->flashMessenger->setNamespace($namespace);

        if (!in_array($namespace, ['success', 'error', 'warning', 'info', 'default'])) {
            $namespace = 'default';
        }

        $iterator = new \RecursiveArrayIterator((array) $message);

        while ($iterator->valid()) {
            if ($iterator->hasChildren()) {
                $this->__invoke($iterator->getChildren(), $namespace);
            } else {
                $this->flashMessenger->addMessage($iterator->current(), $namespace, 10);
            }
            $iterator->next();
        }

        return $this->layout->setVariable('flashMessages', $this->flashMessenger);
    }
}
