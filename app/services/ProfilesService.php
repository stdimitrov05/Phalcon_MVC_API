<?php

namespace App\Services;

use App\Exceptions\ServiceException;
use App\Lib\Helper;
use App\Models\EmailConfirmations;
use App\Models\Users;
use App\Models\Tokens;

class ProfilesService extends AbstractService
{
    /**
     * Users list
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
     * User create
     *
     * @param array $data => user_id,item_name ...
     * @return array
     *
     */

    public function create(array $data)
    {

        try {
            //Starting Transaction
            $this->db->begin();
            $users = new Users();
            $users->assign($data); //table data
            $result = $users->create(); // create

            if (!$result) {
                throw new ServiceException(
                    'Unable to create user',
                    self::ERROR_UNABLE_CREATE_USER
                );
            }

            //User data
            $ipAddress = $this->request->getClientAddress();
            $userAgent = $this->request->getUserAgent();
            //Random string Token
            $token = Helper::generateToken();
            //Send Email with verify token
            $emailConfirmations = new EmailConfirmations();
            $emailConfirmations->user_id = $users->id;
            $emailConfirmations->token = $token;
            if ($ipAddress) $emailConfirmations->ip_address = $ipAddress;
            if ($userAgent) $emailConfirmations->user_agent = $userAgent;
            $emailConfirmations->save();
            //Send Email
            //  $this->mailer->welcome($user->email, $user->username, $token);

            $this->db->commit();


        } catch (\PDOException $e) {
            $this->db->rollback();
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }

        return [
            'usersId' => $users->id
        ];

    }


    /**
     * User details
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
                    'User not found',
                    self::ERROR_USER_NOT_FOUND
                );
            }

        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }

        return $details;

    }


    public function confirmEmail($token)
    {
        try {
            // Check token form emailconfirmations table
            $emailConfirmation = EmailConfirmations::findFirstByToken($token);

            if (empty($emailConfirmation)) {
                throw new ServiceException(
                    'Confirmation token is not valid.',
                    self::ERROR_CONFIRMATION_TOKEN_NOT_EXIST
                );
            }

            $expiredToken = time() - $emailConfirmation->created_at > 24 * 60 * 60;

            // Is the token expired
            if ($expiredToken === true) {
                throw new ServiceException(
                    'Confirmation token has expired.',
                    self::ERROR_CONFIRMATION_TOKEN_EXPIRED
                );
            }

            // Is email already confirmed
            if ($emailConfirmation->confirmed != 0) {
                throw new ServiceException(
                    'Your email is already confirmed.',
                    self::ERROR_CONFIRMATION_CONFIRMED
                );
            }

            // Find user that matches the token
            $user = Users::findById($emailConfirmation->user_id);

            if (!$user) {
                throw new ServiceException(
                    "User not found",
                    self::ERROR_USER_NOT_FOUND
                );
            }

            // Change user to active
            $user->active = 1;
            $user->update();

            // Make current confirm token invalid
            $emailConfirmation->confirmed = 1;
            $emailConfirmation->save();

            // Logs the user in
            $aut =new AuthService();
            $tokens = $aut->authenticateUser($user, ['remember' => 1]);

        } catch (\PDOException $e) {
            throw new ServiceException($e->getMessage(), $e->getCode(), $e);
        }

        return $tokens;

    }


}
