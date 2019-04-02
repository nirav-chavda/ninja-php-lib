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