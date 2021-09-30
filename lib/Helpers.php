<?php

    /**
     * redirect
     * redirect to the passed URL
     * @param string $url
     * @return void
     */
    function redirect($url) {
        if($url=='/') {
            header('Location:'.APP_URL);
        } else {
            header('Location:'.APP_URL.'/'.$url);
        }
    }

    /**
     * public_path
     * returns path to the public folder
     * @return string
     */
    function public_path() {
        return APP_ROOT . 'public/';
    }

    /**
     * app_path
     * returns path to the app folder
     * @return string
     */
    function app_path() {
        return APP_ROOT . 'app/';
    }

    /**
     * redirect_back
     * redirects to the path accessed last
     * @return void
     */
    function redirect_back() {

        if(Ninja\Session::has('referer'))
            $referer = Ninja\Session::get('referer');
        elseif(isset($_SERVER['HTTP_REFERER']))
            $referer = $_SERVER['HTTP_REFERER'];
        else
            $referer = '/';    
        
        redirect($referer);
    }

    /**
     * csrf_field
     * prints the csrf tag
     */
    function csrf_field() {
        echo '<input type="hidden" value="' . Ninja\CSRF::getToken() . '" name="random_token">';
    }

    /**
     * panic
     * throws new exceptions with passed message
     * @param string $message
     * @return void
     */
    function panic($message) {
        throw new Exception($message);
    }