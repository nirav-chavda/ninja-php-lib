<?php

namespace Ninja;

session_start();

class Session {

    /**
     * flash
     * sets a message that can be used only once
     * @param string $name
     * @param string $message
     * @return void|string
     */
    public static function flash($name='',$message='') {

        if(!empty($message)) {
            $_SESSION[$name] = $message;
        } else {
            if(isset($_SESSION[$name])) {
                $message = $_SESSION[$name];
                unset($_SESSION[$name]);
                return $message;
            } else {
                panic('Session Variable Name is Not Set');
            }
        }
    }

    /**
     * has
     * checks if the passed key is present or not in session
     * @param string $name
     * @return bool
     */
    public static function has($name) {
        return isset($_SESSION[$name]);
    }

    /**
     * get
     * returns value associated with passed name
     * if name is empty then return all values stored in session
     * @param string $name optional
     * @return string|array
     */
    public static function get($name='') {
        if(empty($name)) 
            return $_SESSION;
        else
            return $_SESSION[$name];
    }

    /**
     * set
     * sets a value to session
     * @param string $name
     * @param string $message
     * @return void
     */
    public static function set($name,$message) {
        $_SESSION[$name] = $message;
    }

    /**
     * terminate
     * destroy's the current session
     * @return void
     */
    public function terminate() {
        session_destroy();
    }

    /**
     * unset
     * unset the value associate with the passed key 
     * @return void
     */
    public function unset($key) {
        unset($_SESSION[$key]);
    }

    /**
     * unsetAll
     * unset all values from session
     * @return void
     */
    public function unsetAll() {
        session_unset();
    }
}
