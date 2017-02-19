<?php
/**
 * Created by PhpStorm.
 * User: Dejw
 * Date: 18.02.2017
 * Time: 16:41
 */

namespace DejwCake\Media\Helpers;


use DejwCake\Media\Model\Entity\Media;

class PathGenerator
{
    /**
     * Get the path for the given media, relative to the root storage path.
     *
     * @param Media $media
     *
     * @return string
     */
    public function getPath(Media $media)
    {
        return $this->getBasePath($media).'/';
    }

    /**
     * Get the path for conversions of the given media, relative to the root storage path.
     *
     * @param Media $media
     *
     * @return string
     */
    public function getPathForConversions(Media $media)
    {
        return $this->getBasePath($media).'/conversions/';
    }

    /**
     * Get a (unique) base path for the given media.
     *
     * @param Media $media
     *
     * @return string
     */
    protected function getBasePath(Media $media)
    {
        return $media->get('id');
    }
}