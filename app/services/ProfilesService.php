<?php

namespace App\Services;

use App\Exceptions\ServiceException;
use App\Models\Users;
use App\Services\AbstractService;

class ProfilesService extends AbstractService
{
    /**
     * Product list
     *
     * @return array
     *
     */

    public function listUsers()
    {
        $sql = "SELECT * FROM `users` ORDER BY id";
        $state = $this->db->prepare($sql);
        $state->execute();
        $data = $state->fetchAll();
        return $data;
    }

    /**
     * Product create
     *
     * @param array $data => user_id,item_name ...
     * @return array
     *
     */

    public function create(array $data)
    {
        try {

            $users = new Users();
            $users->assign($data); //table data
            $result = $users->create(); // create

            if (!$result) {
                throw new ServiceException(
                    'Unable to create user',
                    self::ERROR_UNABLE_CREATE_USER
                );
            }

        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }

        return [
            'usersId' => $users->id
        ];

    }

    /**
     * Product details
     *
     * @param int $id
     * @return array
     */

    public function details(int $id)
    {
        $data = [];
        try {

            $sql = "SELECT * FROM `users` WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('id', $id);
            $stmt->execute();
            $details = $stmt->fetchAll();

            if (!$details) {
                throw new ServiceException(
                    'Product not found',
                    self::ERROR_PRODUCT_NOT_FOUND
                );
            }

        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }

        return $details;

    }

    /**
     * Product update
     *
     * @param int $id
     * @param  array $data
     * @return array
     *
     */

    public function update(int $id ,array $data)
    {

        try {
            $sql = "SELECT id FROM `users` WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('id', $id);
            $stmt->execute();
            $table_id = $stmt->fetch();
            if (!$table_id) {
                throw new ServiceException(
                    'User not found',
                    self::ERROR_USER_NOT_FOUND
                );
            }
            // True
            $sql = "UPDATE `users` 
                    Set username = :username ,  email = :email, password = :password 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('id', $id);
            $stmt->bindParam('username', $data['username']);
            $stmt->bindParam('email', $data['email']);
            $stmt->bindParam('password', $data['password']);
            $reslut = $stmt->execute();
            if (!$reslut) {
                throw new ServiceException(
                    'Unable to update user',
                    self::ERROR_UNABLE_UPDATE_USER
                );
            }

        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }

        return null;
    }

    /**
     * Product delete by id
     * @param int $id
     * @return array
     *
     */

    public function delete(int $id)
    {
        try {
            $sql = "SELECT id FROM `users` WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('id', $id);
            $stmt->execute();
            $details = $stmt->fetch();

            if (!$details) {
                throw new ServiceException(
                    'User not found',
                    self::ERROR_USER_NOT_FOUND
                );
            }
            // True
            $sql = "DELETE FROM `users`
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('id', $id);
            $reslut = $stmt->execute();

            if (!$reslut) {
                throw new ServiceException(
                    'User not delete!',
                    self::ERROR_USER_NOT_DELETE
                );
            }

        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }

        return null;
    }


}
