<?php

namespace App\Services;

use App\Exceptions\ServiceException;
use App\Models\Users;
use App\Models\Tokens;

class TokensService extends AbstractService
{
    /**
     * Token list
     *
     * @return array
     *
     */

    public function listTokens()
    {
        $sql = "SELECT id, token FROM `users_tokens` ORDER BY  id";

        $state = $this->db->prepare($sql);
        $state->execute();
        while ($data = $state->fetchAll())
        {
            if (!$data) {
                throw new ServiceException(
                    'Token not found',
                    self::ERROR_TOKEN_NOT_FOUND
                );
            }
            return $data;
        }


        $sql = "DELETE FROM `users_tokens` WHERE  tokenLife + INTERVAL 30 MINUTE < current_time ";
        $state = $this->db->prepare($sql);
        $state->execute();


    }

    /**
     * Token create
     *
     * @param array $data => user_id,token
     * @return array
     *
     */

    public function create(array $data)
    {

        try {
            $sql = "INSERT INTO users_tokens (user_id,token) VALUES (:id,:token)";
            $stm = $this->db->prepare($sql);
            $stm->bindParam('id', $data['user_id']);
            $token = bin2hex(random_bytes(25));
            $stm->bindParam('token', $token);
          var_dump("f7594d643caaeaceeb24f3d82eba72e2");
            $result = $stm->execute();

            if (!$result) {
                throw new ServiceException(
                    'Unable to create token',
                    self::ERROR_UNABLE_CREATE_TOKEN
                );
            }


        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }

        return [
            'tokenId' => $stm->id
        ];

    }

    /**
     * Token details
     *
     * @param int $id
     * @return array
     */

    public function details(int $id)
    {
        $data = [];
        try {

            $sql = "SELECT * FROM `users_tokens` WHERE user_id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('id', $id);
            $stmt->execute();
            $details = $stmt->fetchAll();

            if (!$details) {
                throw new ServiceException(
                    'Token not found',
                    self::ERROR_TOKEN_NOT_FOUND
                );
            }

        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }

        return $details;

    }


    /**
     * Token delete
     *
     * @return array
     *
     */

    public function deleteTokens()
    {

        return null;
    }

}
