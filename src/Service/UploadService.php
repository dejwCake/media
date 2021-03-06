<?php namespace DejwCake\Media\Service;

use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\Log\Log;

class UploadService
{

    protected $imagine;
    protected $library;

    public $results = [];

    public function __construct()
    {
        if (!$this->imagine) {
            $this->library = Configure::read('Media.library');
            $this->quality = Configure::read('Media.quality');
            $this->uploadpath = Configure::read('Media.path');
            $this->ckeditoruploadpath = Configure::read('Media.ckeditorPath');
            $this->newfilename = Configure::read('Media.newfilename');
            $this->dimensions = Configure::read('Media.dimensions');
            $this->suffix = Configure::read('Media.suffix');
            $this->exif = Configure::read('Media.exif');

            $this->isCkeditor = false;

            // Now create the instance
            if ($this->library == 'imagick') $this->imagine = new \Imagine\Imagick\Imagine();
            elseif ($this->library == 'gmagick') $this->imagine = new \Imagine\Gmagick\Imagine();
            elseif ($this->library == 'gd') $this->imagine = new \Imagine\Gd\Imagine();
            else                                 $this->imagine = new \Imagine\Gd\Imagine();
        }
    }

    private function checkPathIsOk($path, $dir = null)
    {
        $path = rtrim($path, DS) . ($dir ? DS . trim($dir, DS) : '');

        $folder = new Folder();
        if ($folder->create($path, 0777)) {
            Log::write('info', 'Uploader dir created: '.$path);
            // Successfully created the nested folders
            return true;
        } else {
            Log::write('error', 'Uploader dir create failed: '.$path);
            return false;
        }
    }

    public function upload($filesource, $newfilename = null, $dir = null)
    {
        if($this->isCkeditor) {
            $uploadpath = $this->ckeditoruploadpath;
        } else {
            $uploadpath = $this->uploadpath;
        }
        $isPathOk = $this->checkPathIsOk($uploadpath, $dir);

        if ($isPathOk) {
            if ($filesource) {
                $this->results['path'] = rtrim($uploadpath, DS) . ($dir ? DS . trim($dir, DS) : '');
                $this->results['dir'] = str_replace(WWW_ROOT, '', $this->results['path']);
                $this->results['original_filename'] = $filesource->getClientOriginalName();
                $this->results['original_filepath'] = $filesource->getRealPath();
                $this->results['original_extension'] = $filesource->getClientOriginalExtension();
                $this->results['original_filesize'] = $filesource->getSize();
                $this->results['original_mime'] = $filesource->getMimeType();
                $this->results['exif'] = $this->getExif($filesource->getRealPath());

                switch ($this->newfilename) {
                    case 'hash':
                        $this->results['filename'] = md5($this->results['original_filename'] . '.' . $this->results['original_extension'] . strtotime('now')) . '.' . $this->results['original_extension'];
                        break;
                    case 'random':
                        $this->results['filename'] = substr(md5(microtime()),rand(0,26),5) . '.' . $this->results['original_extension'];
                        break;
                    case 'timestamp':
                        $this->results['filename'] = strtotime('now') . '.' . $this->results['original_extension'];
                        break;
                    case 'dashTime':
                        $temp = explode('.', $this->results['original_filename']);
                        $ext  = array_pop($temp);
                        $name = implode('.', $temp);
                        $newFileName = $name.'-'.time();
                        $this->results['filename'] = $newFileName . '.' . $this->results['original_extension'];
                        break;
                    case 'custom':
                        $this->results['filename'] = (!empty($newfilename) ? $newfilename . '.' . $this->results['original_extension'] : $this->results['original_filename'] . '.' . $this->results['original_extension']);
                        break;
                    default:
                        $this->results['filename'] = $this->results['original_filename'];
                }

                $uploaded = $filesource->move($this->results['path'], $this->results['filename']);
                if ($uploaded) {
                    $this->results['original_filepath'] = rtrim($this->results['path']) . DS . $this->results['filename'];
                    $this->results['original_filedir'] = str_replace(WWW_ROOT, '', $this->results['original_filepath']);
                    $this->results['basename'] = pathinfo($this->results['original_filepath'], PATHINFO_FILENAME);

                    list($width, $height) = getimagesize($this->results['original_filepath']);
                    $this->results['original_width'] = $width;
                    $this->results['original_height'] = $height;

                    $this->createDimensions($this->results['original_filepath']);
                } else {
                    $this->results['error'] = 'File ' . $this->results['original_filename '] . ' is not uploaded.';
                    Log::error('Imageupload: ' . $this->results['error']);
                }
            }
        }

        return $this->results;
    }

    protected function createDimensions($filesource)
    {
        if (!empty($this->dimensions) && is_array($this->dimensions)) {
            foreach ($this->dimensions as $name => $dimension) {
                $width = (int)$dimension[0];
                $height = isset($dimension[1]) ? (int)$dimension[1] : $width;
                $crop = isset($dimension[2]) ? (bool)$dimension[2] : false;

                $this->resize($filesource, $name, $width, $height, $crop);
            }
        }
    }

    private function resize($filesource, $suffix, $width, $height, $crop)
    {
        if (!$height) $height = $width;

        $suffix = trim($suffix);

        $path = $this->results['path'] . ($this->suffix == false ? DS . trim($suffix, DS) : '');
        $name = $this->results['basename'] . ($this->suffix == true ? '_' . trim($suffix, DS) : '') . '.' . $this->results['original_extension'];

        $pathname = $path . DS . $name;

        try {
            $isPathOk = $this->checkPathIsOk($this->results['path'], ($this->suffix == false ? $suffix : ''));

            if ($isPathOk) {
                $size = new \Imagine\Image\Box($width, $height);
                $mode = $crop ? \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND : \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
                $newfile = $this->imagine->open($filesource)->thumbnail($size, $mode)->save($pathname, ['quality' => $this->quality]);

                list($nwidth, $nheight) = getimagesize($pathname);
                $filesize = filesize($pathname);

                $this->results['dimensions'][$suffix] = [
                    'path' => $path,
                    'dir' => str_replace(WWW_ROOT, '', $path),
                    'filename' => $name,
                    'filepath' => $pathname,
                    'filedir' => str_replace(WWW_ROOT, '', $pathname),
                    'width' => $nwidth,
                    'height' => $nheight,
                    'filesize' => $filesize,
                ];
            }
        } catch (\Exception $e) {

        }
    }

    protected function getExif($filesourcepath)
    {
        $exifdata = null;

        if ($this->exif) {
            try {
                $image = $this->imagine
                    ->setMetadataReader(new \Imagine\Image\Metadata\ExifMetadataReader())
                    ->open($filesourcepath);
                $metadata = $image->metadata();
                $exifdata = $metadata->toArray();
            } catch (\Exception $e) {

            }
        }

        return $exifdata;
    }

    public function setCkeditor($status = true)
    {
        $this->isCkeditor = $status;
    }
}