<?php

namespace App\Controllers;


use App\Exceptions\HttpExceptions\Http404Exception;
use App\Exceptions\HttpExceptions\Http422Exception;
use App\Exceptions\HttpExceptions\Http500Exception;
use App\Exceptions\ServiceException;
use App\Services\AbstractService;
use App\Validation\SignupValidation;
use App\Validation\TokenValidation;
use Phalcon\Db\Result\Pdo;


/**
 * Profile controller
 */

class ProfileController extends AbstractController
{
    /**
     * Select All Products from products table
     *
     * @return array
     */

    public function listAction()
    {
        try {
            $response = $this->profilesService->listUsers();
        } catch (ServiceException $e) {
            throw new Http500Exception('Internal Server Error', $e->getCode(), $e);
        }
        return $response;
    }


    /**
     * Select Curent Products from products table
     * @param $id
     * @return array
     */

    public function detailsAction($id)
    {
        try {
            $response = $this->profilesService->details((int)$id);

        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case AbstractService::ERROR_USER_NOT_FOUND:
                    throw new Http404Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception('Internal Server Error', $e->getCode(), $e);
            }
        }

        return $response;
    }

    /**
     * Delete Curent Products from products table
     * @param $id
     * @return array
     */

    public function deleteAction($id)
    {
        try {
            $response = $this->profilesService->delete((int)$id);

        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case AbstractService::ERROR_USER_NOT_FOUND:
                    throw new Http404Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception('Internal Server Error', $e->getCode(), $e);
            }
        }

        return $response;
    }

    /**
     * Delete Curent Products from products table
     * @param string $user
     * @return array
     */

    public function deleteNameAction($id)
    {
        try {
            $response = $this->profilesService->deleteByUsername((int)$id);

        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case AbstractService::ERROR_USER_NOT_FOUND:
                    throw new Http404Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception('Internal Server Error', $e->getCode(), $e);
            }
        }

        return $response;
    }

    /**
     * Update Curent Products from products table
     * @param $id
     * @return array
     */

    public function updateAction($id)
    {
        //Arr for data
        $data = [];

        // Collect and trim request params
        foreach ($this->request->getPut() as $key => $value) {
            $data[$key] = $this->request->getPut($key, ['string', 'trim']);
        }
        try {
            //Passing data to business logic and prepare the response

            $this->profilesService->update($id,$data);
        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case AbstractService::ERROR_UNABLE_CREATE_USER:
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception('Internal Server Error', $e->getCode(), $e);
            }
        }

        return null;
    }

    /**
     * Create New User
     * @param $id
     * @return array
     */

    public function createAction()
    {
        //Arr for data
        $data = [];

        // Collect and trim request params
        foreach ($this->request->getPost() as $key => $value) {
            $data[$key] = $this->request->getPost($key, ['string', 'trim']);
        }

        // Start validation with data
        $validation = new SignupValidation();
        $messages = $validation->validate($data);

        if (count($messages)) {
            $this->throwValidationErrors($messages);
        }

        try {

            //Passing data to business logic and prepare the response
            $token = $this->profilesService->create($data);

        } catch (ServiceException $e) {
            switch ($e->getCode()) {

                case AbstractService::ERROR_UNABLE_CREATE_USER:
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    if ($this->db->getErrorInfo()){
                        throw new Http404Exception($e->getMessage(),$e->getCode(),$e);
                    }else{
                        throw new Http500Exception('Internal Server Error', $e->getCode(), $e);
                    }

            }
        }

        return $token;
    }


    /**
     * Confirm user email
     *
     * @return null
     */
    public function emailAction()
    {
        //Define Error arr
        $error = [];
        //Get token from url
        $token =  trim($this->request->getPost('token'));

        //Check token != 0
        if (empty($token))
        {
            $error['token'] = 'Missing token';
        }

        //Check errors
        if ($error) {
            $exception = new Http400Exception(
                'Input parameters validation error',
                self::ERROR_INVALID_REQUEST
            );
            throw $exception->addErrorDetails($error);
        }

        try {
            $response = $this->profilesService->confirmEmail($token);

        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case AbstractService::ERROR_CONFIRMATION_TOKEN_NOT_EXIST:
                case AbstractService::ERROR_CONFIRMATION_TOKEN_EXPIRED:
                case AbstractService::ERROR_CONFIRMATION_CONFIRMED:
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                case AbstractService::ERROR_USER_NOT_FOUND:
                    throw new Http404Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception('Internal Server Error', $e->getCode(), $e);
            }
        }

        return $response;
    }



}