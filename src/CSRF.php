<?php 

namespace Ninja;

use Ninja\Session;

class CSRF {

    /**
     * getToken
     * returns the csrf token stored in a session
     * @return string
     */
    public static function getToken() {
        if(Session::has('token_id')) { 
            return Session::get('token_id');
        } else {
            return static::createToken();
        }
    }

    /**
     * validate
     * validates the authenticity of the token
     * @return bool
     */
    public static function validate() {
        if(isset($_POST['random_token']) && Session::has('token_id') && ($_POST['random_token'] == Session::get('token_id'))) {
            static::dropToken();
            return true;
        } else {
            return false;   
        }
    }

    /**
     * dropToken
     * invalidates the csrf token
     * @return void
     */
    public static function dropToken() {
        Session::unset('token_id');
    }

    /**
     * createToken
     * creates a new csrf token and returns it 
     * @return string
     */
    public static function createToken() {
        $token_id = md5(uniqid(rand(),true));
        Session::set('token_id',$token_id); 
        return $token_id;
    }
}