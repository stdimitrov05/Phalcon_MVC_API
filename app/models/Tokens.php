<?php

namespace App\Models;

use Phalcon\Db\Column;

class Tokens extends \Phalcon\Mvc\Model
{
    /*
     * Tokens Model
     *
     * */
    public $token;

    //Table products
    public function initialize()
    {
        $this->setSource('users_tokens');
    }
}