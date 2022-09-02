<?php

namespace App\Controllers;

use App\Exceptions\HttpExceptions\Http422Exception;
use App\Exceptions\HttpExceptions\Http500Exception;
use App\Exceptions\ServiceException;
use App\Services\AbstractService;
use App\Validation\SignupValidation;


/**
 * Profile controller
 */
class ProfileController extends AbstractController
{
    /**
     * Index
     *
     * @return array
     */
    public function createUserAction()
    {
        //Arr for data
        $data = [];

        // Collect and trim request params
        foreach ($this->request->getPost() as $key => $value) {
            $data[$key] = $this->request->getPost($key, ['string', 'trim']);
        }

        // Start validation
        $validation = new SignupValidation();
        $messages = $validation->validate($data);

        if (count($messages)) {
            $this->throwValidationErrors($messages);
        }

        try {
            //Passing data to business logic and prepare the response
            $token = $this->profileService->createUser($data);

        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case AbstractService::ERROR_UNABLE_CREATE_USER:
                    throw new Http422Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception('Internal Server Error', $e->getCode(), $e);
            }
        }

        return $token;
    }

    public function getUsersAction()
    {
        try {
            $response = $this->profileService->getUsers();
        } catch (ServiceException $e) {
            throw new Http500Exception('Internal Server Error', $e->getCode(), $e);
        }
        return $response;
    }

    public function getUsersByID()
    {
        try {
            $response = $this->profileService->getUserProfilebyId();
        } catch (ServiceException $e) {
            throw new Http500Exception('Internal Server Error', $e->getCode(), $e);
        }
        return $response;
    }


}

