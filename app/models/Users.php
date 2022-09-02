<?php
namespace App\Models;

use App\Lib\Slug;
use Phalcon\Db\Column;
use Phalcon\Mvc\ModelInterface;

class Users extends \Phalcon\Mvc\Model
{
    /*
     * Model for Users Table to
     *
     * */
    // username - varchar(50)
    public $username;
    //email - varchar(255)
    public $email;
    //password - int unsigned not null
    public $password;
    //verify - int
    public  $verify;
    //ban - int
    public $ban;
    //status - int
    public $status;
    //createAt - int
    public $createAt;

    public function initialize()
    {
        $this->setSource('users');
    }

    public function beforeValidationOnCreate()
    {
        $this->createAt = time();
    }


}