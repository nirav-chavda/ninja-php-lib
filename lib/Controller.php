<?php
    /* 
     * Base Controller
     * Loads the models and views
     */
    
    class Controller {

        public function model($model) {

            if(file_exists(__DIR__ . "\\..\\..\\app\\models\\" . $model . '.php')) {
                require_once __DIR__ . "\\..\\..\\app\\models\\" . $model . '.php';
                return new $model;
            } else {
                die($model . ' model not exists');
            }
        }

        public function view($view, $data = []) {

            if(file_exists(__DIR__ . "\\..\\..\\app\\views\\" . $view .'.php')) {
                require_once __DIR__ . "\\..\\..\\app\\views\\" . $view .'.php';
            } else {
                die($view . ' view not found');
            }
        }
    }