<?php

/**
 * @copyright  2015 - 2016 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Controller\Plugin;

use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\View\Model\ViewModel;

final class ErrorCodes extends AbstractPlugin
{
    /**
     * @var ViewModel
     */
    private $layout;

    /**
     * @var Response
     */
    private $response;

    /**
     * @param ViewModel $layout
     * @param Response  $response
     */
    public function __construct(ViewModel $layout, Response $response)
    {
        $this->layout = $layout;
        $this->response = $response;
    }

    /**
     * @param int $code error code
     */
    public function __invoke($code = 404)
    {
        $this->response->setStatusCode((int) $code);
        $this->layout->setVariables(
            [
            'message'   => '404 Not found',
            'reason'    => 'The link you have requested doesn\'t exists',
            'exception' => '',
            ]
        );
        $this->layout->setTemplate('error/index');
    }
}
