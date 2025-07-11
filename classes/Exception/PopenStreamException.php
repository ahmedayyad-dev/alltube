<?php

namespace Alltube\Exception;

/**
 * Could not open popen stream.
 */
class PopenStreamException extends AlltubeLibraryException
{
    /**
     * Error message.
     * @var string
     */
    protected $message = 'Could not open popen stream.';
}
