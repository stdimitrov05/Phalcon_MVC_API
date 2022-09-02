<?php

namespace App\Controllers;

use App\Exceptions\HttpExceptions\Http500Exception;
use App\Exceptions\ServiceException;
use Phalcon\Mvc\Controller;

/**
 * Frontend controller
 */
class FrontendController extends AbstractController
{
    /**
     * Index
     *
     * @return array
     */
    public function indexAction()
    {
        try {
            $response = $this->frontendService->index();

        } catch (ServiceException $e) {
            throw new Http500Exception('Internal Server Error', $e->getCode(), $e);
        }

        return $response;
    }
    public  function  homeAction()
    {
        try {
            $response = $this->frontendService->home();
        }catch (ServiceException $e) {
            throw new Http500Exception('Internal Server Error', $e->getCode(), $e);
        }
        return $response;
    }

}

