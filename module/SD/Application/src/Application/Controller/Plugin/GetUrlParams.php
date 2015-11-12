<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.22
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Controller\Plugin;

use Zend\Escaper\Escaper;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mvc\Controller\Plugin\Params;

final class GetUrlParams extends AbstractPlugin
{
    /**
     * @var Params
     */
    private $params;

    /**
     * @param Params $params
     */
    public function __construct(Params $params)
    {
        $this->params = $params;
    }

    /**
     * Shorthand method for getting params from URLs. Makes code easier to edit and avoids DRY code.
     *
     * @param string $paramName
     *
     * @return array|string
     */
    public function __invoke($paramName)
    {
        $escaper = new Escaper('utf-8');

        /*
         * Return early. Usually params will come from post.
         *
         * @var mixed
         */
        $param = $this->params->fromPost($paramName, null);
        if (!$param) {
            $param = $this->findParam($paramName);
        }

        /*
         * If this is array it MUST comes from fromFiles()
         */
        if (is_array($param) && !empty($param)) {
            return $param;
        }

        return $escaper->escapeHtml($param);
    }

    /**
     * @param string $paramName
     *
     * @return mixed
     */
    private function findParam($paramName)
    {
        $param = $this->params->fromRoute($paramName, null);
        if (!$param) {
            $param = $this->params->fromQuery($paramName, null);
        }
        if (!$param) {
            $param = $this->params->fromHeader($paramName, null);
        }
        if (!$param) {
            $param = $this->params->fromFiles($paramName, null);
        }

        return $param;
    }
}
