<?php

namespace App\Services;

use App\Models\Users;
use App\Services\AbstractService;

class ProfileService extends AbstractService
{
    public function getUsers()
    {
        $sql = "SELECT * FROM `users` ORDER BY id";
        $state = $this->db->prepare($sql);
        $state->execute();
        $data = $state->fetchAll();
        return $data;
    }

    public function getProfileById()
    {
        $sql = "SELECT * FROM `users` WHERE id=?";
        $res = $this->db->prepare($sql);
        $str = 4;
        $res->bindParam(1, $str);
        $res->execute();
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row ;

    }
    public function createUser_old($data)
    {
        $createAt =  time();
        $sql = "INSERT INTO users (username, email, verify, ban, createAt)
                            VALUES ( :username, :email, :verify, :ban, :createAt)";
        $statement = $this->db->prepare($sql);
        $statement->bindParam('username', $data['username'], \PDO::PARAM_STR);
        $statement->bindParam('email', $data['email'], \PDO::PARAM_STR);
        $statement->bindParam('verify', $data['verify'], \PDO::PARAM_INT);
        $statement->bindParam('ban', $data['ban'], \PDO::PARAM_INT);
        $statement->bindParam('createAt', $createAt, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function createUser(array $data)
    {
        try {
            $user = new Users();
            $user->assign($data);
            $result = $user->create();

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
            'userId' => $user->id
        ];

    }


}


