<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Controller;

use Zend\Mvc\MvcEvent;

/**
 * @method object getTable($tableName)
 * @method mixed UserData()
 */
final class ProfileController extends BaseController
{
    /**
     * @param MvcEvent $event
     *
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $event)
    {
        parent::onDispatch($event);
        /*
         * If user is logged and tries to access one of the given actions
         * he will be redirected to the root url of the website.
         */
        if (getenv('APPLICATION_ENV') !== 'development') {
            if (!$this->UserData()->hasIdentity()) {
                $this->redirect()->toUrl('/');
            }
        }
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $this->getView()->setTemplate('application/profile/index');

        $user = $this->getTable('SD\\Admin\\Model\\UserTable')->getUser($this->UserData()->getIdentity());
        $this->getView()->setVariable('identity', $user);

        return $this->getView();
    }
}
