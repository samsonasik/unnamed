<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.22
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Entity;

use SD\Admin\Exception\BadMethodCallException;
use SD\Admin\Exception\InvalidArgumentException;
use SD\Admin\Exception\RuntimeException;

final class Image implements ImageInterface
{
    /**
     * A valid image path /path/to/image.png.
     *
     * @var string|resource
     */
    private $imageFile;

    /**
     * Image format, taken from the mime type.
     *
     * @var string
     */
    private $format;

    /**
     * The current dimensions of the image.
     *
     * @var array
     */
    private $imageDimensions = ['width' => 1, 'height' => 1];

    /**
     * @var int
     */
    private $width = 320;

    /**
     * @var int
     */
    private $height = 270;

    /**
     * All config options for different image formats.
     *
     * @var array
     */
    private $options = [
        'preserve_alpha'         => true,
        'alpha_color_allocate'   => [255, 255, 255],
        'alpha_transperancy'     => 64,
        'png_compression_level'  => -1,
        'png_compression_filter' => 'all',
        'jpeg_quality'           => 75,
        'foreground'             => [0, 0, 0],

        // still not used anywhere
        'interlace'               => 0,
        'transparency_mask_color' => [0, 0, 0],
    ];

    /**
     * All allowed mime types.
     *
     * @var array
     */
    private $allowedMimeTypes = [
        'image/gif',
        'image/jpeg',
        'image/png',
        'image/bmp',
        'image/webp',
        'image/wbmp',
    ];

    /**
     * PNG compression filters.
     *
     * @var array
     */
    private $pngFilterTypes = [
        'no'    => PNG_NO_FILTER,
        'none'  => PNG_FILTER_NONE,
        'sub'   => PNG_FILTER_SUB,
        'up'    => PNG_FILTER_UP,
        'avg'   => PNG_FILTER_AVG,
        'paeth' => PNG_FILTER_PAETH,
        'all'   => PNG_ALL_FILTERS,
    ];

    /**
     * The GD library.
     *
     * @var GD
     */
    private $gdLib;

    public function __construct()
    {
        $this->gdLib = new GD('2.0.1');
    }

    /**
     * Try to open and read image.
     *
     * @method open
     *
     * @param string $imageFile
     * @param array  $options
     *
     * @return self
     */
    public function open($imageFile, array $options = [])
    {
        /*
         * See if this really is a file
         */
        if (!is_file($imageFile)) {
            throw new InvalidArgumentException('Invalid image');
        }

        /*
         * Try reading its contents
         */
        $data = file_get_contents($imageFile);
        if (!$data) {
            throw new RuntimeException('Cannot open file');
        }

        /*
         * Try creating image from the read file
         */
        $resource = imagecreatefromstring($data);
        if (!is_resource($resource)) {
            throw new RuntimeException('Unable to open image');
        }

        $this->imageFile = $imageFile;
        $this->setOptions($options);
        $this->extractImageFormat();
        $this->createImageFromFormat();

        $this->imageDimensions = [
            'width'  => imagesx($this->getImageFile()),
            'height' => imagesy($this->getImageFile()),
        ];

        return $this;
    }

    /**
     * Free up memory.
     */
    public function __destruct()
    {
        if (is_resource($this->getImageFile())) {
            imagedestroy($this->getImageFile());
        }
    }

    /**
     * @return string|resource
     */
    private function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Returns the format.
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * The current dimensions of the image.
     *
     * @return array
     */
    public function getImageDimensions()
    {
        return $this->imageDimensions;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Holds all config data for all methods.
     *
     * @param array $options
     *
     * @return self
     */
    private function setOptions(array $options = [])
    {
        if (!is_array($options)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Parameter provided to %s must be an array',
                    __METHOD__
                )
            );
        }

        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $this->options[$key] = $value;
            }
        }

        return $this;
    }

    /**
     * Get all options set.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get an individual option.
     *
     * Keys are normalized to lowercase.
     *
     * Returns null for not found options.
     *
     * @param string $option
     *
     * @return mixed
     */
    public function getOption($option)
    {
        $option = strtolower($option);
        if (array_key_exists($option, $this->options)) {
            return $this->options[$option];
        }

        return;
    }

    /**
     * The function will return false for invalid images.
     *
     * @return array|false
     */
    public function getImageInfo()
    {
        return getimagesize($this->getImageFile());
    }

    /**
     * Prepare new image size.
     *
     * @param int $width
     * @param int $height
     */
    private function checkImageSizes($width = 1, $height = 1)
    {
        if ($height < 1 || $width < 1) {
            throw new InvalidArgumentException('Image height and width must be at least 1 pixel');
        }

        $this->width = (int) $width;
        $this->height = (int) $height;
    }

    /**
     * Extract the file format by mime-type.
     *
     * @throws RuntimeException for invalid mime-types
     */
    private function extractImageFormat()
    {
        $format = $this->getImageInfo();

        if (!in_array($format['mime'], $this->allowedMimeTypes)) {
            throw new RuntimeException('Unsupported image format');
        }

        /*
         * strip out image/ from mime type and make the rest upper cases
         */
        $format = strtoupper(substr($format['mime'], 6));

        /*
         * Normalize format
         */
        $format = $this->normalizeImageFormat($format);

        $this->format = $format;
    }

    /**
     * @param string $format
     *
     * @return string
     */
    private function normalizeImageFormat($format)
    {
        if ($format === 'JPG' || $format === 'PJPEG') {
            $format = 'JPEG';
        }

        if ($format === 'VND.WAP.WBMP') {
            $format = 'WBMP';
        }

        return $format;
    }

    /**
     * Try to create a new image from the supplied file.
     *
     * @throws RuntimeException on invalid image format
     */
    private function createImageFromFormat()
    {
        switch ($this->getFormat()) {
            case 'GIF':
                $this->imageFile = $this->imageCreateFromGif();
                break;

            case 'JPEG':
                $this->imageFile = $this->imageCreateFromJpeg();
                break;

            case 'PNG':
                $this->imageFile = $this->imageCreateFromPng();
                break;

            case 'WEBP':
                $this->imageFile = $this->imageCreateFromWebp();
                break;

            default:
                throw new RuntimeException('Invalid image format');
        }
    }

    /**
     * See if we can create GIF images.
     *
     * @throws BadMethodCallException on missing support
     */
    private function imageCreateFromGif()
    {
        if ($this->gdLib->hasGifCreateSupport()) {
            return imageCreateFromGif($this->getImageFile());
        }

        throw new BadMethodCallException('Missing GIF create support');
    }

    /**
     * See if we can create JEPG|JPG images.
     *
     * @throws BadMethodCallException on missing support
     */
    private function imageCreateFromJpeg()
    {
        if ($this->gdLib->hasJpegSupport()) {
            return imageCreateFromJpeg($this->getImageFile());
        }

        throw new BadMethodCallException('Missing JPEG support');
    }

    /**
     * See if we can create PNG images.
     *
     * @throws BadMethodCallException on missing support
     */
    private function imageCreateFromPng()
    {
        if ($this->gdLib->hasPngSupport()) {
            return imageCreateFromPng($this->getImageFile());
        }

        throw new BadMethodCallException('Missing PNG support');
    }

    /**
     * See if we can create WEBP images.
     *
     * @throws BadMethodCallException on missing support
     *
     * @return resource
     */
    private function imageCreateFromWebp()
    {
        if ($this->gdLib->hasJpegSupport() || $this->gdLib->hasPngSupport()) {
            if (function_exists('imageCreateFromWebp')) {
                return imageCreateFromWebp($this->getImageFile());
            }
        }

        throw new BadMethodCallException('Missing WEBP support');
    }

    /**
     * Generates a GD image.
     *
     * @return resource
     */
    private function generateImage()
    {
        $resource = imagecreatetruecolor($this->getWidth(), $this->getHeight());

        imagealphablending($resource, $this->getOption('preserve_alpha'));
        imagesavealpha($resource, true);

        if (function_exists('imageantialias')) {
            imageantialias($resource, true);
        }

        $color = $this->getArrayKeyValue('alpha_color_allocate', [255, 255, 255]);
        $alpha = $this->getKeyValue('alpha_transperancy');

        $transparentColor = imagecolorallocatealpha($resource, $color[0], $color[1], $color[2], $alpha);
        imagefill($resource, 0, 0, $transparentColor);
        imagecolortransparent($resource, $transparentColor);

        return $resource;
    }

    /**
     * Create the image with the given width and height.
     *
     * @param int $width
     * @param int $height
     *
     * @throws RuntimeException on invalid operation
     *
     * @return self
     */
    public function resize($width = 1, $height = 1)
    {
        $this->checkImageSizes($width, $height);

        $oldImageDimensions = $this->getImageDimensions();
        $newImage = $this->generateImage();

        imagealphablending($this->getImageFile(), true);
        imagealphablending($newImage, true);

        if (!imagecopyresampled($newImage, $this->getImageFile(), 0, 0, 0, 0, $this->getWidth(), $this->getHeight(), $oldImageDimensions['width'], $oldImageDimensions['height'])) {
            throw new RuntimeException('Image resizing has failed');
        }

        imagealphablending($this->getImageFile(), false);
        imagealphablending($newImage, false);
        imagedestroy($this->getImageFile());

        $this->imageFile = $newImage;

        return $this;
    }

    /**
     * @param string $path
     * @param string $fileName
     *
     * @throws RuntimeException
     */
    public function save($path, $fileName)
    {
        $this->validateDir($path);

        if (!$fileName) {
            throw new RuntimeException('Image name is not set');
        }

        $format = strtolower($this->getFormat());
        $imageSaveMethod = 'image'.$format;
        $options = [$this->getImageFile(), $path.DIRECTORY_SEPARATOR.$fileName];
        $options[] = $this->getFormatOptions();

        if (!call_user_func_array($imageSaveMethod, $options)) {
            throw new RuntimeException('Image save has failed');
        }
    }

    /**
     * @param string $path
     *
     * @throws InvalidArgumentException
     */
    private function validateDir($path)
    {
        if (!is_dir($path)) {
            throw new InvalidArgumentException(sprintf('Directory "%s" not found or not a directory', $path));
        } elseif (!is_writable($path)) {
            throw new InvalidArgumentException(sprintf('Directory "%s" not writable', $path));
        } elseif (!is_readable($path)) {
            throw new InvalidArgumentException(sprintf('Directory "%s" not readable', $path));
        }
    }

    private function getFormatOptions()
    {
        $options = [];
        $opt = $this->checkFormatOptions();

        foreach ($opt as $option) {
            $options[] = $option;
        }

        return $options;
    }

    /**
     * @return array
     */
    private function checkFormatOptions()
    {
        $params = [];
        $format = strtolower($this->getFormat());

        if ($format === 'png') {
            $png = $this->pngOptions();
            $params[] = $png['level'];
            $params[] = $png['filter'];
        } elseif ($format === 'jpeg') {
            $params[] = $this->getKeyValue('jpeg_quality');
        } elseif ($format === 'wbmp') {
            $params[] = $this->getArrayKeyValue('foreground', [0, 0, 0]);
        }

        return $params;
    }

    /**
     * @return array
     */
    private function pngOptions()
    {
        $params = [];
        $params['level'] = $this->getOption('png_compression_level');
        $filter = (string) $this->getOption('png_compression_filter');

        if ($params['level'] < 0 || $params['level'] > 9) {
            // http://www.zlib.net/manual.html
            $params['level'] = -1; // Z_DEFAULT_COMPRESSION
            $this->options['png_compression_level'] = $params['level'];
        }

        if (!in_array($filter, array_keys($this->pngFilterTypes))) {
            throw new RuntimeException(
                sprintf('png_compression_filter should be one or a combination of: "%s"', implode('', array_keys($this->pngFilterTypes))));
        }
        $params['filter'] = $this->pngFilterTypes[$filter];

        return $params;
    }

    /**
     * @param string $key
     * @param int    $value
     *
     * @return int
     */
    private function getKeyValue($key, $value = 64)
    {
        $key = (string) $key;
        $alpha = $this->getOption($key);

        if ($alpha < 1 || $alpha > 127) {
            $alpha = (int) $value;
            $this->options[$key] = $alpha;
        }

        return $alpha;
    }

    /**
     * @param string $key
     * @param array  $defaultValue
     *
     * @return array
     */
    private function getArrayKeyValue($key, array $defaultValue = [255, 255, 255])
    {
        $value = $this->getOption($key);

        if (!is_array($value)) {
            $value = $defaultValue;
            $this->options[$key] = $value;
        }

        return $value;
    }
}
