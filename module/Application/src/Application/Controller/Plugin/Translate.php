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

use Zend\I18n\Translator\Translator;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

final class Translate extends AbstractPlugin
{
    /**
     * @var Translator
     */
    private $translator = null;

    /**
     * @param Translator $translator
     */
    public function __construct(Translator $translator = null)
    {
        $this->translator = $translator;
    }

    /**
     * @param string $message
     *
     * @return string
     */
    public function __invoke($message = '')
    {
        return $this->translator->translate($message);
    }
}
