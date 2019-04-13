<?php

namespace Ninja;

use Ninja\Session;
use DB;

class Auth {

    public static function user() {
        $db = new DB;
        $db->query('SELECT * FROM users WHERE id = :id');
        $db->bind(':id',Session::get('user_id'));
        return $db->first();
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
            die('Auth not setted');
        }
    }
}
