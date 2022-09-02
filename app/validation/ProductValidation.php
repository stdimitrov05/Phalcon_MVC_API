<?php

namespace App\Validation;

use App\Models\Users;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class ProductValidation extends Validation
{
    /*
     * id int not null AUTO_INCREMENT,
       userid int REFERENCES users(id),
       item_name varchar(255),
       price int not null ,*/
    public function initialize()
    {
        $this->rules(
            'itemName',
            [
                new PresenceOf([
                    'message' => 'Product name is required.',
                    'cancelOnFail' => true
                ]),
                new StringLength([
                    'min' => 10,
                    'messageMinimum' => 'Product name must be at least 10 characters.'
                ])

            ]
        );

        $this->rules(
            'price', [
                new PresenceOf([
                    'message' => 'Product price is required.',
                    'cancelOnFail' => true
                ]),
                new StringLength([
                    'min' => 3,
                    'messageMinimum' => 'Product name must be at least 2$.'
                ])
            ]
        );
    }
}