<?php

namespace Ninja;

session_start();

class Session {

    public static function flash($name='',$message='') {

        if(!empty($message)) {
            $_SESSION[$name] = $message;
        } else {
            if(isset($_SESSION[$name])) {
                $message = $_SESSION[$name];
                unset($_SESSION[$name]);
                return $message;
            } else {
                die('Session Variable Name is Not Set');
            }
        }
    }

    public static function has($name) {
        if(isset($_SESSION[$name])) {
            return true;
        }
        if else(isset($_SESSION[$name])) {
                $message = $_SESSION[$name];
                unset($_SESSION[$name]);
                return $message;
            else {
            return false;
        }
    }

    public static function get($name='') {
        if(empty($name)) 
            return $_SESSION;
        else
            return $_SESSION[$name];
    }

    public static function set($name,$message) {
        $_SESSION[$name] = $message;
    }

    public function terminate() {
        session_destroy();
    }

    public function unset($key) {
        unset($_SESSION[$key]);
    }

    public function unsetAll() {
        session_unset();
    }
}
