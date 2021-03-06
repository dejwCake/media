<?php
use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Core\Plugin;

Configure::write('Media', [
    /*
     * Library used to manipulate image.
     *
     * Options: gd (default), imagick, gmagick
     */
    'library' => env('IMAGEUPLOAD_LIBRARY', 'gd'),

    /*
     * Quality for JPEG type.
     *
     * Scale: 1-100;
     */
    'quality' => 90,

    /*
     * Upload directory.
     *
     * Default: public/uploads/images
     */
    'path' => WWW_ROOT . 'files' . DS . 'uploads',
    'ckeditorPath' => WWW_ROOT . 'files' . DS . 'ckeditor',

    /*
      * Use original name. If set to false, will use hashed name.
      *
      * Options:
      *     - original (default): use original filename in "slugged" name
      *     - hash: use filename hash as new file name
      *     - random: use random generated new file name
      *     - timestamp: use uploaded timestamp as filename
      *     - dashTime: use original filename dashed with time()
      *     - custom: user must provide new name, if not will use original filename
      */
    'newfilename' => 'dashTime',

    /*
     * Sizes, used to crop and create multiple size.
     *
     * array(width, height, square, quality), if square set to TRUE, image will be in square
     */
    'dimensions' => [
//        'square50' => [50, 50, true],
//        'square100' => array(100, 100, true),
//        'square200' => array(200, 200, true),
//        'square400' => array(400, 400, true),
//
//        'size50' => array(50, 50, false),
//        'size100' => array(100, 100, false),
//        'size200' => array(200, 200, false),
//        'size400' => array(400, 400, false),
    ],

    /*
     * Dimension identifier. If TRUE will use dimension name as suffix, if FALSE use directory.
     *
     * Example:
     *     - TRUE (default): newname_square50.png, newname_size100.jpg
     *     - FALSE: square50/newname.png, size100/newname.jpg
     */
    'suffix' => true,

    /*
     * Get the EXIF data. PHP must be compiled in with --enable-exif to use this method.
     * Windows users must also have the mbstring extension enabled.
     *
     * Example:
     *     - TRUE: get the exif data if exists
     *     - FALSE (default): ignore exif data
     */
    'exif' => env('IMAGEUPLOAD_EXIF', false),

    //For media
    'table' => 'DejwCake/Media.Media',

    /*
     * The filesystems on which to store added files and derived images. Choose one or more
     * of the filesystems you configured in app/config/filesystems.php
     */
    'defaultFilesystem' => 'media',

    /*
     * The maximum file size of an item in bytes. Adding a file
     * that is larger will result in an exception.
     */
    'maxFileSize' => 1024 * 1024 * 100,

    'disks' => [
        'media' => [
            'driver' => 'local',
            'root'   => WWW_ROOT . 'files'. DS .'media',
        ],
    ],

    'templates' => [
        'fileTemplate',
        'imageTemplate',
    ],

    'defaultTemplate' => 'image',
]);
