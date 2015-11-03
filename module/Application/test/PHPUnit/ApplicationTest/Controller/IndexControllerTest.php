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

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use PHPUnit_Framework_Testcase;

class IndexControllerTest extends PHPUnit_Framework_Testcase
{
    public function setUp()
    {
        if (!defined('APP_ENV')) {
            define('APP_ENV', getenv('APPLICATION_ENV'));
        }

        $this->setApplicationConfig(
            include dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))).'/config/application.config.php'
        );

        parent::setUp();
    }

    /**
     * Called after every test.
     *
     * @method tearDown
     *
     * @return void
     */
    public function tearDown()
    {
    }

    public function testIndexAction()
    {
        $this->dispatch('/');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Application');
        $this->assertControllerName('Application\Controller\Index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('application/default');
    }
}
