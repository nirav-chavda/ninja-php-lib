<?php

namespace Ninja;

use Ninja\Session;
use Model;

class Auth {

    public static function user() {
        $db = new Model;
        $db->query('SELECT * FROM users WHERE id = :id');
        $db->bind(':id',Session::get('user_id'));
        return $db->first();
    }

    public static function id() {
        return Session::get('user_id');    
    }

    public static function set($email) {
        $db = new Model;
        $db->query('SELECT id FROM users WHERE email = :email');
        $db->bind(':email',$email);
        $row = $db->first();
        Session::set('user_id',$row->id);
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
            Session::unset('auth');
            Session::unset('user_id');
            Session::terminate();
        } else {
            die('Auth not setted');
        }
    }
}