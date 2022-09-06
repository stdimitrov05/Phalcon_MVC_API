<?php
namespace App\Lib;

class Helper
{

    //Create token for email verify
    public static function generateToken()
    {
        return bin2hex(random_bytes(16));
    }
}
