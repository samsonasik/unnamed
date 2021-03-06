<?php

/**
 * @copyright  2015 - 2016 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace ApplicationTest\Controller;

use PHPUnit_Framework_Testcase;
use SD\Application\Controller\IndexController;

class IndexControllerTest extends PHPUnit_Framework_Testcase
{
    /*
     * @var IndexController
     */
    private $controller;

    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    private $serviceManager;

    protected function setUp()
    {
        $controller = new IndexController();

        $userDataPlugin = $this->getPlugin('UserData');
        $this->serviceManager = $this->prophesize('Zend\ServiceManager\ServiceLocatorInterface');

        $controller->setServiceLocator($this->serviceManager->reveal());
        $controller->setPluginManager($userDataPlugin);

        $this->controller = $controller;
    }

    public function tearDown()
    {
        unset($this->controller);
    }

    /**
     * @param string $pluginName
     * @param string $method
     *
     * @return \Zend\Mvc\Controller\PluginManager
     */
    private function getPlugin($pluginName, $method = 'get')
    {
        $pluginManager = $this->getMock('Zend\Mvc\Controller\PluginManager', ['get']);
        $pluginManager->expects($this->any())
                        ->method($method)
                        ->will($this->returnCallback([$this, $pluginName]));

        return $pluginManager;
    }

    public function testIndexAction()
    {
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $this->controller->indexAction());
    }
}
