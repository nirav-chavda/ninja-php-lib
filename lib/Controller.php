<?php
    /* 
     * Base Controller
     * Loads the models and views
     */
    
    class Controller {

        public function model($model) {

            $root = substr(__DIR__,0,strpos(__DIR__,'\vendor'));

            if(file_exists($root . "\\app\\models\\" . $model . '.php')) {
                require_once $root . "\\app\\models\\" . $model . '.php';
                return new $model;
            } else {
                die($model . ' model not exists');
            }
        }

        public function view($view, $data = []) {

            $root = substr(__DIR__,0,strpos(__DIR__,'\vendor'));

            if(file_exists($root . "\\app\\views\\" . $view .'.php')) {
                require_once $root . "\\app\\views\\" . $view .'.php';
            } else {
                die($view . ' view not found');
            }
        }
    }