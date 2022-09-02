<?php

namespace App\Exceptions\HttpExceptions;

use App\Exceptions\AbstractHttpException;

/**
 * Class Http403Exception
 *
 * Exception class for Forbidden Error (403)
 *
 * @package App\Lib\Exceptions
 */
class Http403Exception extends AbstractHttpException
{
    protected $httpCode = 403;
    protected $httpMessage = 'Forbidden';
}
