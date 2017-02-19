<?php namespace DejwCake\Media\Service;

use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use DejwCake\Media\Model\Entity\Media;
use Exception;

class MediaFileConversionService
{
    /**
     * The direction to flip: vertical.
     *
     * @constant
     * @var int
     */
    const DIR_VERT = 1;

    /**
     * The direction to flip: horizontal.
     *
     * @constant
     * @var int
     */
    const DIR_HORI = 2;

    /**
     * The direction to flip: vertical and horizontal.
     *
     * @constant
     * @var int
     */
    const DIR_BOTH = 3;

    /**
     * The location to crop: top.
     *
     * @constant
     * @var int
     */
    const LOC_TOP = 1;

    /**
     * The location to crop: bottom.
     *
     * @constant
     * @var int
     */
    const LOC_BOT = 2;

    /**
     * The location to crop: left.
     *
     * @constant
     * @var int
     */
    const LOC_LEFT = 3;

    /**
     * The location to crop: right.
     *
     * @constant
     * @var int
     */
    const LOC_RIGHT = 4;

    /**
     * The location to crop: center.
     *
     * @constant
     * @var int
     */
    const LOC_CENTER = 5;

    /**
     * The mode to resize: width.
     *
     * @constant
     * @var int
     */
    const MODE_WIDTH = 1;

    /**
     * The mode to resize: height.
     *
     * @constant
     * @var int
     */
    const MODE_HEIGHT = 2;

    /**
     * Should we allow file uploading for this request?
     *
     * @access protected
     * @var boolean
     */
    protected $_enabled = true;

    /**
     * @var string
     */
    protected $file;

    /**
     * @var array
     */
    protected $dimensions;

    /**
     * @var Media
     */
    protected $media;

    /**
     * @var array
     */
    protected $conversions;

    /**
     * @var Folder
     */
    protected $destinationDir;

    /**
     * MediaFileConversionService constructor.
     * @param $file
     * @param Media $media
     * @param $conversions
     * @param Folder $destinationDir
     *
     * @throws Exception
     */
    public function __construct($file, Media $media, $conversions, Folder $destinationDir)
    {
        $this->file = $file;
        $this->dimensions = $this->getDimensions($this->file);
        $this->media = $media;
        $this->conversions = $conversions;
        $this->destinationDir = $destinationDir;

        if ($this->_loadExtension('gd')) {
            $this->_enabled = ini_get('file_uploads');
        } else {
            $this->_enabled = false;
            throw new Exception(sprintf('%s: GD image library is not installed', __METHOD__));
        }

        if (!$this->_enabled) {
            return;
        }
    }

    /**
     * Create conversion files
     */
    public function createConversionFiles()
    {
        foreach ($this->conversions as $conversionName => $conversionData) {
            $conversionData = $conversionData + ['overwrite' => true];
            $this->resize($this->getDestinationPath($conversionName), $conversionData);
        }
    }

    /**
     * Get destination file path for conversion
     *
     * @param $conversionName
     * @return string
     */
    protected function getDestinationPath($conversionName)
    {
        return $this->destinationDir->pwd() . $conversionName . '.' . $this->media->get('extension');
    }

    /**
     * Resizes an image based off a previously uploaded image.
     *
     * @access public
     * @param $destinationPath
     * @param array $options
     *        - width,
     *        - height: The width and height to resize the image to
     *        - quality: The quality of the image
     *        - append: What should be appended to the end of the filename (defaults to dimensions if not set)
     *        - prepend: What should be prepended to the front of the filename
     *        - expand: Should the image be resized if the dimension is greater than the original dimension
     *        - aspect: Keep the aspect ratio
     *        - mode: Use the width or height as the base for aspect keeping
     *        - overwrite: Should we overwrite the existent file with the same name?
     * @return string
     */
    public function resize($destinationPath, array $options) {
        if ($this->media->get('type') != Media::TYPE_IMAGE || !$this->_enabled) {
            return false;
        }

        $options = $options + [
                'width' => null,
                'height' => null,
                'quality' => 100,
                'append' => null,
                'prepend' => null,
                'expand' => false,
                'aspect' => true,
                'mode' => self::MODE_WIDTH,
                'overwrite' => false
            ];

        $baseWidth = $this->dimensions['width'];
        $baseHeight = $this->dimensions['height'];
        $width = $options['width'];
        $height = $options['height'];
        $newWidth = null;
        $newHeight = null;


        if (empty($width) && empty($height)) {
            //DO not resize, just copy
            (new File($this->file))->copy($destinationPath);
            return $destinationPath;
        }
        if (is_numeric($width) && empty($height)) {
            $height = round(($baseHeight / $baseWidth) * $width);

        } else if (is_numeric($height) && empty($width)) {
            $width = round(($baseWidth / $baseHeight) * $height);

        } else if (!is_numeric($height) && !is_numeric($width)) {
            return false;
        }

        // Maintains the aspect ratio of the image
        if ($options['aspect']) {
            $widthScale = $width / $baseWidth;
            $heightScale = $height / $baseHeight;

            if (($options['mode'] == self::MODE_WIDTH && $widthScale < $heightScale) || ($options['mode'] == self::MODE_HEIGHT && $widthScale > $heightScale)) {
                $newWidth = $width;
                $newHeight = ($baseHeight * $newWidth) / $baseWidth;

            } else if (($options['mode'] == self::MODE_WIDTH && $widthScale > $heightScale) || ($options['mode'] == self::MODE_HEIGHT && $widthScale < $heightScale)) {
                $newHeight = $height;
                $newWidth = ($newHeight * $baseWidth) / $baseHeight;

            } else {
                $newWidth = $width;
                $newHeight = $height;
            }
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }

        // Don't expand if we don't want it too
        if (!$options['expand']) {
            if ($newWidth > $baseWidth) {
                $newWidth = $baseWidth;
            }

            if ($newHeight > $baseHeight) {
                $newHeight = $baseHeight;
            }
        }

        $newWidth = round($newWidth);
        $newHeight = round($newHeight);
        $append = '_resized_' . $newWidth . 'x' . $newHeight;

        if ($options['append'] !== false && empty($options['append'])) {
            $options['append'] = $append;
        }

        $transform = [
            'width'		=> $newWidth,
            'height'	=> $newHeight,
            'target'	=> $destinationPath,
            'quality'	=> $options['quality'],
        ];

        if ($this->transform($transform)) {
            return $destinationPath;
        }

        return false;
    }

    /**
     * Main function for transforming an image.
     *
     * @access public
     * @param array $options
     * @return boolean
     */
    public function transform(array $options) {
        $options = $options + [
                'dest_x' => 0,
                'dest_y' => 0,
                'dest_w' => null,
                'dest_h' => null,
                'source_x' => 0,
                'source_y' => 0,
                'source_w' => $this->dimensions['width'],
                'source_h' => $this->dimensions['height'],
                'quality' => 100
            ];

        $mimeType = $this->dimensions['type'];

        if (empty($options['dest_w'])) {
            $options['dest_w'] = $options['width'];
        }

        if (empty($options['dest_h'])) {
            $options['dest_h'] = $options['height'];
        }

        // Create an image to work with
        switch ($mimeType) {
            case 'image/gif':
                $source = imagecreatefromgif($this->file);
                break;
            case 'image/png':
                $source = imagecreatefrompng($this->file);
                break;
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
                $source = imagecreatefromjpeg($this->file);
                break;
            default:
                return false;
                break;
        }

        $target = imagecreatetruecolor($options['width'], $options['height']);

        // If gif,png allow transparencies
        if ($mimeType == 'image/gif' || $mimeType == 'image/png') {
            imagealphablending($target, false);
            imagesavealpha($target, true);
            imagefilledrectangle($target, 0, 0, $options['width'], $options['height'], imagecolorallocatealpha($target, 255, 255, 255, 127));
        }

        // Lets take our source and apply it to the temporary file and resize
        imagecopyresampled($target, $source, $options['dest_x'], $options['dest_y'], $options['source_x'], $options['source_y'], $options['dest_w'], $options['dest_h'], $options['source_w'], $options['source_h']);

        // Now write the resized image to the server
        switch ($mimeType) {
            case 'image/gif':
                imagegif($target, $options['target']);
                break;
            case 'image/png':
                imagepng($target, $options['target']);
                break;
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
                imagejpeg($target, $options['target'], $options['quality']);
                break;
            default:
                imagedestroy($source);
                imagedestroy($target);
                return false;
                break;
        }

        // Clear memory
        imagedestroy($source);
        imagedestroy($target);

        return true;
    }

    /**
     * Get the dimensions of an image.
     *
     * @access public
     * @param string $path
     * @return array
     */
    public function getDimensions($path) {
        $dim = [];

        $data = @getimagesize($path);
        if (!empty($data) && is_array($data)) {
            $dim = [
                'width' => $data[0],
                'height' => $data[1],
                'type' => $data['mime'],
            ];
        }

        if (empty($dim)) {
            $image = @imagecreatefromstring(file_get_contents($path));

            $dim = [
                'width' => @imagesx($image),
                'height' => @imagesy($image),
                'type' => (new File($this->file))->mime(),
            ];
        }

        return $dim;
    }

    /**
     * Attempt to load a missing extension.
     *
     * @access protected
     * @param string $name
     * @return boolean
     */
    protected function _loadExtension($name) {
        if (!extension_loaded($name) && function_exists('dl')) {
            @dl((PHP_SHLIB_SUFFIX == 'dll' ? 'php_' : '') . $name . '.' . PHP_SHLIB_SUFFIX);
        }

        return extension_loaded($name);
    }
}