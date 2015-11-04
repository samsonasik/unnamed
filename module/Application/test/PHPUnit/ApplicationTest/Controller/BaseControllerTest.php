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

use Application\Controller\BaseController;
use ReflectionClass;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class BaseControllerTest extends AbstractHttpControllerTestCase
{
    /**
     * Original $_SERVER.
     *
     * @var array
     */
    protected $_origServer;

    /*
     * @var BaseController
     */
    protected $controller;

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceManager;

    protected function setUp()
    {
        $this->_origServer = $_SERVER;
        $_SERVER['SCRIPT_FILENAME'] = __FILE__;
        $_SERVER['PHP_SELF'] = __FILE__;
        $_SERVER['REQUEST_URI'] = '/index/index';

        $this->controller = new BaseController();

        $this->serviceManager = $this->prophesize('Zend\ServiceManager\ServiceLocatorInterface');
        $this->controller->setServiceLocator($this->serviceManager->reveal());

        $this->setApplicationConfig(
            include dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))).'/config/application.config.php'
        );

        parent::setUp();
    }

    public function tearDown()
    {
        unset($this->controller, $this->serviceManager);
        $_SERVER = $this->_origServer;
    }

    /**
     * Get a protected/private method reflection for testing.
     *
     * @param object $obj  The instantiated instance of your class
     * @param string $name The name of your protected/private method
     * @param array  $args Arguments for the protected/private method
     *
     * @return ReflectionClass The method you asked for
     */

    private static function getProtectedOrPrivateMethod($obj, $name, array $args)
    {
        $class = new ReflectionClass($obj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method->invokeArgs($obj, $args);
    }

    public function testIndexAction()
    {
        $this->dispatch('/');
        $this->assertModuleName('application');
        $this->assertControllerName('Application\Controller\Index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('application');
    }

    // does't work properly
    public function testValidIdentity()
    {
        $authMock = $this->getMock('Zend\Authentication\AuthenticationService');
        $authMock->expects($this->any())
                 ->method('setIdentity')
                 ->will($this->returnValue('someIdentity'));

        $authMock->expects($this->any())
                 ->method('hasIdentity')
                 ->will($this->returnValue(true));

        $authMock->expects($this->any())
                 ->method('getIdentity')
                 ->will($this->returnValue('someIdentity'));
    }

    public function testGetViewMethodWillReturnViewModelInstance()
    {
        $viewModel = static::getProtectedOrPrivateMethod($this->controller, 'getView', []);

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $viewModel);
    }

    public function testGetTranslationMethodWillReturnContainerInstance()
    {
        $sessionContainer = static::getProtectedOrPrivateMethod($this->controller, 'getTranslation', []);

        $this->assertInstanceOf('Zend\Session\Container', $sessionContainer);
    }

    public function testLanguageCanReturnLanguageId()
    {
        $langMethod = static::getProtectedOrPrivateMethod($this->controller, 'language', ['language']);

        $this->assertEquals(1, $langMethod);
    }

    public function testLanguageReturnsOneForWrongArgument()
    {
        $langMethod = static::getProtectedOrPrivateMethod($this->controller, 'language', ['this-argument-is-invalid']);

        $this->assertEquals(1, $langMethod);
    }
}
