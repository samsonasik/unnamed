<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\View\Helper;

use SD\Application\Controller\Plugin\Translate;
use Zend\View\Helper\AbstractHelper;

final class TranslateHelper extends AbstractHelper
{
    /**
     * @var Translate
     */
    private $translator;

    /**
     * @param Translate $translator
     */
    public function __construct($translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param string $message
     * @param string $textDomain
     *
     * @return string
     */
    public function __invoke($message = '', $textDomain = 'SD_Translations')
    {
        return $this->translator->__invoke($message, $textDomain);
    }
}
