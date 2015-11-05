<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.21
 *
 * @link       TBA
 */
namespace Admin\Entity;

use Admin\Exception\BadMethodCallException;
use Admin\Exception\InvalidArgumentException;

final class GD implements GDInterface
{
    /**
     * The GD library.
     *
     * @var array
     */
    private $gd = [];

    /**
     * @method __construct
     *
     * @param string $version minimum GD version
     */
    public function __construct($version = '2.0.1')
    {
        $this->loadGdInfo();
        $this->checkGdVersion($version);
    }

    /**
     * Load GD library.
     *
     * @throws BadMethodCallException if gd_info doesn't exists
     */
    private function loadGdInfo()
    {
        if (!function_exists('gd_info')) {
            throw new BadMethodCallException('GD library has not been installed');
        }

        $this->gd = gd_info();
    }

    /**
     * Check minimum needed GD version.
     *
     * @param string $version
     *
     * @throws InvalidArgumentException on invalid version
     */
    private function checkGdVersion($version = '2.0.1')
    {
        if (version_compare(GD_VERSION, $version, '<')) {
            throw new InvalidArgumentException(sprintf('GD2 version %s or higher is required', $version));
        }
    }

    /**
     * Check Free Type support.
     *
     * @return bool
     */
    public function hasFreeTypeSupport()
    {
        return $this->gd['FreeType Support'];
    }

    /**
     * Check Free Type Linkage support.
     *
     * @return string|null
     */
    public function getFreeTypeLinkage()
    {
        if ($this->hasFreeTypeSupport()) {
            return $this->gd['FreeType Linkage'];
        }

        return;
    }

    /**
     * Check T1Lib support.
     *
     * @return bool
     */
    public function hasT1LibSupport()
    {
        return $this->gd['T1Lib Support'];
    }

    /**
     * Check GIF file read support.
     *
     * @return bool
     */
    public function hasGifReadSupport()
    {
        return $this->gd['GIF Read Support'];
    }

    /**
     * Check GIF file creation support.
     *
     * @return bool
     */
    public function hasGifCreateSupport()
    {
        return $this->gd['GIF Create Support'];
    }

    /**
     * Check JPEG|JPG file support.
     *
     * @return bool
     */
    public function hasJpegSupport()
    {
        return $this->gd['JPEG Support'];
    }

    /**
     * Check PNG file support.
     *
     * @return bool
     */
    public function hasPngSupport()
    {
        return $this->gd['PNG Support'];
    }
}
