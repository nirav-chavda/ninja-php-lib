<?php 

namespace Ninja;

use Ninja\Session;

class CSRF {

    public static function getToken() {

        if(Session::has('token_id')) { 
            return Session::get('token_id');
        } else {
            return static::createToken();
        }
    }
    public static function setToken() {

        if(Session::has('token_id')) { 
            return Session::get('token_id');
        } else {
            return static::createToken();
        }
    }

    public static function validate() {

        if(isset($_POST['random_token']) && Session::has('token_id') && ($_POST['random_token'] == Session::get('token_id'))) {
            static::dropToken();
            return true;
        } else {
            return false;   
        }
    }

    public static function dropToken() {
        Session::unset('token_id');
    }

    public static function createToken() {
        $token_id = md5(uniqid(rand(),true));
        Session::set('token_id',$token_id); 
        return $token_id;
    }
}
