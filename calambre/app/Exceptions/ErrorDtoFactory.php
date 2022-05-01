<?php

namespace App\Exceptions;

class ErrorDtoFactory
{
    /**
     *
     * CODE 0-*: System errors
     * CODE 1-*: Edis Login Error
     *
     *
     */

    public static function undefined($variables = null): ErrorDto
    {
        return new ErrorDto('0-0', 'Fatal Error. Check ErrorDtoFactory.php');
    }

    public static function loginError($variables = null): ErrorDto
    {
        return new ErrorDto('1-1', 'Bad credentials to login into e-distribucion ', $variables);
    }

}
