<?php

namespace App\Services;


/**
 * Business-logic for site frontend
 *
 * Class FrontendService
 */
class FrontendService extends AbstractService
{

    /**
     * Index
     *
     * @return array
     */
    public function index()
    {
        return [
            'status' => 'Working'
        ];
    }


    public function getUserProfile()
    {
        $user = new \UserProfile();
        $data = $user->getUsers();

        return $data ;
    }

    public function getUserProfilebyId()
    {
        $user_data = new \UserProfile();
        $data = $user_data->getProfileById();

        return $data;
    }

}
