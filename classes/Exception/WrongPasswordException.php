<?php

namespace Alltube\Exception;

/**
 * Wrong password.
 */
class WrongPasswordException extends AlltubeLibraryException
{
    /**
     * Error message.
     * @var string
     */
    protected $message = 'Wrong password.';
}
