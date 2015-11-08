<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.21
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace Admin\Entity;

interface ImageInterface
{
    /**
     * Get all options set.
     *
     * @return array
     */
    public function getOptions();

    /**
     * Get an individual option.
     *
     * Keys are normalized to lowercase.
     *
     * Returns null for unfound options
     *
     * @param string $option
     *
     * @return mixed
     */
    public function getOption($option);

    /**
     * The function will return false for invalid images.
     *
     * @return array|false
     */
    public function getImageInfo();

    /**
     * Create the image with the given width and height.
     *
     * @param int $width
     * @param int $height
     *
     * @return self
     */
    public function resize($width = 1, $height = 1);

    /**
     * @param string $path
     * @param string $fileName
     *
     * @return void
     */
    public function save($path, $fileName);

    /**
     * Opens an existing image from $path.
     *
     * @param string $path
     *
     * @return self
     */
    public function open($path);
}
