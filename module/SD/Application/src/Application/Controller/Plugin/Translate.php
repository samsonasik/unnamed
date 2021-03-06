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

use Zend\I18n\Translator\Translator;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

final class Translate extends AbstractPlugin
{
    /**
     * @var Translator
     */
    private $translator;

    /**
     * @param Translator|object|array $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param string $message
     *
     * @return string
     */
    public function __invoke($message = '', $textDomain = 'SD_Translations')
    {
        return $this->translator->translate($message, $textDomain);
    }
}
