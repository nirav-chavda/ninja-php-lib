<?php

namespace Ninja;

use Ninja\Session;
use Illuminate\Database\Capsule\Manager as Capsule;

class Auth {

    public static function user() {
        $user = Capsule::table('users')->where('id', Session::get('user_id'))->first();
        return $user;
    }

    public static function id() {
        return Session::get('user_id');    
    }

    public static function set($id) {
        Session::set('user_id',$id);
        Session::set('auth',true);
    }

    public static function guest() {
        if(Session::has('auth'))
            return 0;
        else
            return 1;
    }

    public static function deAuth() {

        if(Session::has('auth')) {
            Session::unsetAll();
            Session::terminate();
        } else {
            die('Auth not set');
        }
    }
}
