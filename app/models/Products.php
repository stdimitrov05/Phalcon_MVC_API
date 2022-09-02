<?php

namespace App\Models;

use Phalcon\Db\Column;

class Products extends \Phalcon\Mvc\Model
{
    /*
     * Products Model
     *
     * id int not null AUTO_INCREMENT,
       userid int REFERENCES users(id),
       item_name varchar(255),
       price int not null ,
     * */
    public $item_name;
    public $price;

    //Table products
    public function initialize()
    {
        $this->setSource('products');
    }
}