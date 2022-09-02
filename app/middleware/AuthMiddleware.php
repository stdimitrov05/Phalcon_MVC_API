<?php

namespace App\Middleware;

use App\Exceptions\HttpExceptions\Http500Exception;
use App\Exceptions\ServiceException;
use App\Services\AbstractService;
use Phalcon\Config;
use Phalcon\Events\Event;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use App\Exceptions\HttpExceptions\Http403Exception;

/**
 * AuthenticationMiddleware
 *
 * Checks auth token to allow or deny route
 */
class AuthMiddleware implements MiddlewareInterface
{
    // Ignored urls
    protected $ignoreUri;

    /**
     * Before anything happens
     *
     * @param Event $event
     * @param Micro $app
     * @return bool
     */
    public function beforeHandleRoute(Event $event, Micro $app)
    {
        try {
            if ($this->isIgnoreUri($app) === false) {
                $app['authService']->verifyToken();
            }
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case AbstractService::ERROR_BAD_TOKEN:
                    throw new Http403Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception($e->getMessage(), $e->getCode(), $e);
            }
        }

        return true;
    }

    /**
     * Calls the middleware
     *
     * @param Micro $app
     *
     * @return bool
     */
    public function call(Micro $app)
    {
        return true;
    }

    /**
     * Checks if the URI and HTTP METHOD can bypass the authentication.
     *
     * @return bool
     */
    public function isIgnoreUri(Micro $app): bool
    {
        // access request object
        $request = $app['request'];

        // url
        $uri = $request->getURI();

        // http method
        $method = $request->getMethod();

        // ignored URIs
        $ignoreUri = $app['config']->auth->ignoreUri;

        return $this->hasMatchIgnoreUri($uri, $method, $ignoreUri);
    }

    /**
     * Checks the uri and method if it has a match in the passed self::$ignoreUris.
     *
     * @param string $requestUri
     * @param string $requestMethod HTTP METHODS
     * @param array $ignoreUri
     *
     * @return bool
     */
    protected function hasMatchIgnoreUri(string $requestUri, string $requestMethod, Config $ignoreUri): bool
    {
        foreach ($ignoreUri as $uri) {
            if (strpos($uri, 'regex:') === false) {
                $type = 'str';
            } else {
                $type = 'regex';
                $uri = str_replace('regex:', '', $uri);
            }

            list($pattern, $methods) = (strpos($uri, ':') === false ? [$uri, false] : explode(':', $uri));
            $methods = (!$methods || empty($methods) ? false : explode(',', $methods));
            $match = ($type == 'str' ? $requestUri == $pattern : preg_match("#^$pattern$#", $requestUri));

            if ($match && (!$methods || in_array($requestMethod, $methods))) {
                return true;
            }
        }
        return false;
    }

}
