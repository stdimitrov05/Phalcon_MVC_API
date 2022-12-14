<?php

namespace App\Exceptions\HttpExceptions;

use App\Exceptions\AbstractHttpException;

/**
 * Class Http500Exception
 *
 * Exception class for Internal Server Error (500)
 *
 * @package App\Lib\Exceptions
 */
class Http500Exception extends AbstractHttpException
{
    protected $httpCode = 500;
    protected $httpMessage = 'Internal Server Error';
}
