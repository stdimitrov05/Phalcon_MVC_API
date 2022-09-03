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

class TokenController extends AbstractController
{
    /**
     * Select All Tokens
     *
     * @return array
     */

    public function listAction()
    {
        try {
            $response = $this->tokensService->listTokens();
        } catch (ServiceException $e) {
            throw new Http500Exception('Internal Server Error', $e->getCode(), $e);
        }
        return $response;
    }


    /**
     * Select  Token by user_id
     * @param $id
     * @return array
     */

    public function detailsAction($id)
    {
        try {
            $response = $this->tokensService->details((int)$id);

        } catch (ServiceException $e) {
            switch ($e->getCode()) {
                case AbstractService::ERROR_TOKEN_NOT_FOUND:
                    throw new Http404Exception($e->getMessage(), $e->getCode(), $e);
                default:
                    throw new Http500Exception('Internal Server Error', $e->getCode(), $e);
            }
        }

        return $response;
    }

    /**
     * Create New Token
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

        // Start validation
     /*   $validation = new TokenValidation();
        $messages = $validation->validate($data);

        if (count($messages)) {
            $this->throwValidationErrors($messages);
        }
*/
        try {
            //Passing data to business logic and prepare the response
            $token = $this->tokensService->create($data);

        } catch (ServiceException $e) {
            switch ($e->getCode()) {

                case AbstractService::ERROR_UNABLE_CREATE_TOKEN:
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
     * Delete Tokens < Now time
     *
     * @return array
     */

    public function deleteAction()
    {
        try {
            $response = $this->tokensService->deleteTokens();
        } catch (ServiceException $e) {
            throw new Http500Exception('Internal Server Error', $e->getCode(), $e);
        }
    }
}