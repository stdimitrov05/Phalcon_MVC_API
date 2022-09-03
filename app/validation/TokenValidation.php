<?php

namespace App\Validation;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;

class TokenValidation extends Validation
{
    /*
     *	id Primary	int		UNSIGNED		AUTO_INCREMENT
	   	user_id Index	int		UNSIGNED
		token	varchar(25)	utf8mb4_0900_ai_ci
		createAt	int			Yes	NULL
    ,*/
    public function initialize()
    {
        $this->rules(
            'token',
            [
                new PresenceOf([
                    'message' => 'Token name is required.',
                    'cancelOnFail' => true
                ]),
                new StringLength([
                    'max' => 25,
                    'messageMaximum' => 'Token must be at most 25 characters.',
                ])

            ]
        );

    }
}