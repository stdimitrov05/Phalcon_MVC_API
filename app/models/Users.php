<?php

namespace App\Models;

use App\Lib\Slug;
use Phalcon\Db\Column;
use Phalcon\Security;

class Users extends \Phalcon\Mvc\Model
{
    //Model Users

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=32, nullable=false)
     */
    public $id;

    /**
     * @var string
     * @Column (type="string",lenght=20, nullable=false)
     * */
    public $username;

    /**
     * @var string
     * @Column (type="string",lenght=255, nullable=false)
     * */
    public $email;

    /**
     * @var string
     * @Column (type="string",lenght=255, nullable=false)
     * */
    public $password;

    /**
     * @var string
     * @Column (type="string",lenght=60, nullable=false)
     * */
    public $url;

    /**
     * @var integer 0 or 1 => online or offline
     * @Column (type="integer",lenght=1, nullable=false)
     * */

    public $active;

    /**
     * @var integer  0 or 1
     * @Column (type="integer",lenght=1, nullable=false)
     * */
    public $banned;

    /**
     * @var integer
     * @Column (type="integer",lenght=11, nullable=false)
     * */
    public $created_at;

    /**
     * @var integer
     * @Column (type="integer",lenght=11, nullable=false)
     * */
    public $delete_at;

    public function initialize()
    {
        $this->setSource('users');
    }

    public function beforeValidationOnCreate()
    {
        // Created user url
        $this->url = Slug::generate($this->username);
        //Insert time in created_at
        $this->created_at = time();
        //Hashed password
        $security = new Security();
        $this->password = $security->hash($this->password);
    }
    public function findById($id)
    {
        return parent::findFirst([
            'columns' => '*',
            'conditions' => 'id = ?1 AND  banned = 0',
            'bind' => [1 => $id],
            'bindTypes'  => [Column::BIND_PARAM_INT]
        ]);
    }

}