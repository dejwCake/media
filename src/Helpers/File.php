<?php

namespace DejwCake\Media\Helpers;

use RuntimeException;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * A file in the file system.
 *
 */
class File extends \SplFileInfo
{
    /**
     * Constructs a new file from the given path.
     *
     * @param string $path      The path to the file
     * @param bool   $checkPath Whether to check the path or not
     *
     * @throws FileNotFoundException If the given path is not a file
     */
    public function __construct($path, $checkPath = true)
    {
        if ($checkPath && !is_file($path)) {
            throw new FileNotFoundException($path);
        }

        parent::__construct($path);
    }

    /**
     * Returns the extension based on the path.
     *
     * @return string|null The extension or null if it cannot be foud
     *
     */
    public function getExtension()
    {
        return pathinfo($this->getPathname(), PATHINFO_EXTENSION);
    }

    /**
     * Returns the mime type of the file.
     *
     *
     * @return string|null The guessed mime type (e.g. "application/pdf")
     *
     */
    public function getMimeType()
    {
        return mime_content_type($this->getPathname());
    }

    /**
     * Moves the file to a new location.
     *
     * @param string $directory The destination folder
     * @param string $name      The new file name
     *
     * @return File A File object representing the new file
     *
     * @throws RuntimeException if the target file could not be created
     */
    public function move($directory, $name = null)
    {
        $target = $this->getTargetFile($directory, $name);

        if (!@rename($this->getPathname(), $target)) {
            $error = error_get_last();
            throw new RuntimeException(sprintf('Could not move the file "%s" to "%s" (%s)', $this->getPathname(), $target, strip_tags($error['message'])));
        }

        @chmod($target, 0666 & ~umask());

        return $target;
    }

    protected function getTargetFile($directory, $name = null)
    {
        if (!is_dir($directory)) {
            if (false === @mkdir($directory, 0777, true) && !is_dir($directory)) {
                throw new RuntimeException(sprintf('Unable to create the "%s" directory', $directory));
            }
        } elseif (!is_writable($directory)) {
            throw new RuntimeException(sprintf('Unable to write in the "%s" directory', $directory));
        }

        $target = rtrim($directory, '/\\').DIRECTORY_SEPARATOR.(null === $name ? $this->getBasename() : $this->getName($name));

        return new self($target, false);
    }

    /**
     * Returns locale independent base name of the given path.
     *
     * @param string $name The new file name
     *
     * @return string containing
     */
    protected function getName($name)
    {
        $originalName = str_replace('\\', '/', $name);
        $pos = strrpos($originalName, '/');
        $originalName = false === $pos ? $originalName : substr($originalName, $pos + 1);

        return $originalName;
    }
}
