<?php

    function redirect($url) {
        if($url=='/') {
            header('Location:'.APP_URL);
        } else {
            header('Location:'.APP_URL.'/'.$url);
        }
    }

    function public_path() {
        return APP_ROOT . 'public/';
    }

    function app_path() {
        return APP_ROOT . 'app/';
    }

    function redirect_back() {

        if(Ninja\Session::has('referer'))
            $referer = Ninja\Session::get('referer');
        elseif(isset($_SERVER['HTTP_REFERER']))
            $referer = $_SERVER['HTTP_REFERER'];
        else
            $referer = '/';    
        
        redirect($referer);
    }

    function csrf_field() {
        echo '<input type="hidden" value="' . Ninja\CSRF::getToken() . '" name="random_token">';
    }