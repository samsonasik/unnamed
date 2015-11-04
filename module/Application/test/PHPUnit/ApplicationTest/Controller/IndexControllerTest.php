<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.21
 *
 * @link       TBA
 */
namespace ApplicationTest\Controller;

use Application\Controller\IndexController;
use PHPUnit_Framework_Testcase;

class IndexControllerTest extends PHPUnit_Framework_Testcase
{
    /*
     * @var IndexController
     */
    private $controller;

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
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
     * @return mixed
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

    // public function testLanguageAction()
    // {
    //     // $doctrine = $this->prophesize('Doctrine\ORM\EntityManager');
    //     // $language = new \Admin\Entity\Language($doctrine->reveal());
    //     $languageTableMock = $this->getMockBuilder('Admin\Model\LanguageTable')
    //         ->disableOriginalConstructor()
    //         ->getMock();

    //     $languageTableMock->expects($this->any())
    //                 ->method('getLanguage')
    //                 ->will($this->returnValue(array()));
    // }
}
