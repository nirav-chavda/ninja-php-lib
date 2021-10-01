<?php

namespace Ninja;

use Ninja\Session;
use Illuminate\Database\Capsule\Manager as Capsule;

class Auth {

    /**
     * user
     * returns current authenticated user object
     * @return object
     */
    public static function user() {
        $user = Capsule::table('users')->where('id', Session::get('user_id'))->first();
        return $user;
    }

    /**
     * id
     * returns current authenticated user's id
     * @return string
     */
    public static function id() {
        return Session::get('user_id');    
    }

    /**
     * set
     * set used_id in session
     * @return void
     */
    public static function set($id) {
        Session::set('user_id',$id);
        Session::set('auth',true);
    }

    /**
     * guest
     * check if user is guest or authenticated
     * @return bool
     */
    public static function guest() {
        if(Session::has('auth')) {
            return 0;
        } else {
            return 1;
        }
    }

    /**
     * deAuth
     * sign out the authenticated user
     * @return void
     */
    public static function deAuth() {

        if(Session::has('auth')) {
            Session::unsetAll();
            Session::terminate();
        } else {
            panic('Auth not set');
        }
    }
   
}
