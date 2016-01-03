<?php

/**
 * @copyright  2015 - 2016 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace ApplicationTest\Factory;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class SessionFactoryTest extends AbstractHttpControllerTestCase
{
    /**
     * @var array
     */
    protected $origServer;

    protected function setUp()
    {
        $this->origServer = $_SERVER;
        $_SERVER['SCRIPT_FILENAME'] = __FILE__;
        $_SERVER['PHP_SELF'] = __FILE__;
        $_SERVER['REQUEST_URI'] = '/index/index';
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_PORT'] = '443';

        $this->setApplicationConfig(
            include dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))).'/config/application.config.php'
        );

        parent::setUp();
    }

    public function tearDown()
    {
        $_SERVER = $this->origServer;
    }

    public function testCanInvokeSession()
    {
        $session = $this->prophesize('SD\Application\Factory\SessionFactory');
        $this->assertInstanceOf('SD\Application\Factory\SessionFactory', $session->reveal());

        $sessionManager = $this->prophesize('Zend\Session\SessionManager');
        $session->__invoke()->willReturn($sessionManager->reveal());
    }
}
