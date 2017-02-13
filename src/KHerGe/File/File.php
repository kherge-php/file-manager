<?php

namespace KHerGe\File;

use KHerGe\File\Exception\ResourceException;

/**
 * Manages the file contents stored on the file system.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class File extends Stream
{
    /**
     * The file open mode.
     *
     * @var string
     */
    private $mode;

    /**
     * The path to the file.
     *
     * @var string
     */
    private $path;

    /**
     * Initializes the new file manager.
     *
     * @param string $path The path to the file.
     * @param string $mode The file open mode.
     *
     * @throws ResourceException If the file could not be opened.
     */
    public function __construct($path, $mode)
    {
        $error = null;
        set_error_handler(function ($severity, $message, $filename, $lineno) use (&$error) {
            $error = new \ErrorException($message, 0, $severity, $filename, $lineno);
        }, E_WARNING);
        $stream = fopen($path, $mode);
        restore_error_handler();

        if ($error) {
            throw new ResourceException(
                "The file \"$path\" could not be opened ($mode).",
                $error
            );
        }

        parent::__construct($stream);

        $this->mode = $mode;
        $this->path = $path;
    }
}
