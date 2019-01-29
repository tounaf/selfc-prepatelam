<?php

namespace Telma\Selfcare\PrepaidBundle\Util;

/**
 * Created by PhpStorm.
 * User: Harinjatovo
 * Date: 28/01/2019
 * Time: 15:28
 */
class Util
{
    /**
     * @param int $size
     * @return string
     *
     */
    public static function generatePassword($size = 6)
    {
        $chars = '0123456789';
        $login = '';
        for ($i = 0; $i < $size; $i++) {
            $login .= $chars[rand(0, strlen($chars) - 1)];
        }

        return $login;
    }
}