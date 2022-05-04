<?php

namespace App\Exceptions;

class ErrorDtoFactory
{
    /**
     *
     * CODE 0-*: System errors
     * CODE 1-*: Edis Login Error
     * CODE 2-*: User Error
     *
     */

    public static function undefined($variables = null): ErrorDto
    {
        return new ErrorDto('0-0', 'Fatal Error. Check ErrorDtoFactory.php', $variables);
    }

    public static function routeNotFound($variables = null): ErrorDto
    {
        return new ErrorDto('0-1', 'Route not found', $variables);
    }

    public static function validation($variables = null): ErrorDto
    {
        return new ErrorDto('0-1', 'Validation error', $variables);
    }

    public static function loginEdisError($variables = null): ErrorDto
    {
        return new ErrorDto('1-1', 'Bad credentials to login into e-distribucion ', $variables);
    }

    public static function cantStorageInformation($variables = null): ErrorDto
    {
        return new ErrorDto('1-2', 'Something wrong during storing Edis Login information', $variables);
    }

    public static function tokenInvalid($variables = null): ErrorDto
    {
        return new ErrorDto('2-1', 'Login error - Token Invalid', $variables);
    }

    public static function tokenExpired($variables = null): ErrorDto
    {
        return new ErrorDto('2-2', 'Login error - Token Expired', $variables);
    }

    public static function tokenAbsent($variables = null): ErrorDto
    {
        return new ErrorDto('2-3', 'Login error - Authorization Token not found', $variables);
    }

    public static function existingEmail($variables = null): ErrorDto
    {
        return new ErrorDto('2-4', 'The email it is already registered', $variables);
    }

    public static function invalidCredentials($variables = null): ErrorDto
    {
        return new ErrorDto('2-5', 'Invalid credentials', $variables);
    }

    public static function couldNotCreateToken($variables = null): ErrorDto
    {
        return new ErrorDto('2-6', 'Could not create valid token', $variables);
    }

    public static function userNotFound($variables = null): ErrorDto
    {
        return new ErrorDto('2-7', 'User not found', $variables);
    }


}
