<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

final class SystemSettings extends AbstractPlugin
{
    /**
     * @var array
     */
    private $options = [];

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * Shorthand method for requesting global system settings.
     *
     * @param string $option
     * @param string $value
     *
     * @return string
     */
    public function __invoke($option = 'general', $value = 'site_name')
    {
        return $this->getOption($option, $value);
    }

    /**
     * Get an individual option.
     *
     * Returns null for not found option.
     *
     * @param string $key
     * @param string $value
     *
     * @return mixed
     */
    private function getOption($key, $value)
    {
        return array_key_exists($value, $this->options[$key]) ? $this->options[$key][$value] : null;
    }
}
