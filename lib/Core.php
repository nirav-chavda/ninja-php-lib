<?php
    /*
     * App Core Class
     * Creates URL & loads core controller
     * URL Format - /controller/method/params
     */
     
    class Core {

        protected $currentController = 'HomeController';
        protected $currentMethod = 'index';
        protected $params = [];

        public function __construct() {
            
            $url = $this->getURL();
            $root = substr(__DIR__,0,strpos(__DIR__,'\vendor'));

            if($url[0]!='/') {

                $url[0] = ucwords($url[0]) . 'Controller';

                // check if controller exists
                if(file_exists($root . "\\app\\controllers\\" . ucwords($url[0]) . '.php')) {

                    // set contoller 
                    $this->currentController = ucwords($url[0]);
                    
                    // unset index 0 from the array
                    unset($url[0]);

                } else {
                    $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
                    header($protocol . ' 404 ' . 'Controller ' . $url[0] . ' Not Found');
                    exit(0);
                }
            }

            require_once $root . "\\app\\controllers\\" . $this->currentController . ".php";

            // Instantiate the controller class (Bydefault Home)
            $this->currentController = new $this->currentController;

            if(isset($url[1])) {

                if(method_exists($this->currentController,$url[1])) {
                    $this->currentMethod = $url[1];
                    unset($url[1]);
                } else {
                    echo "Method Not Exists";
                    exit(0);
                }
            }

            $this->params = $url ? array_values($url) : [];

            call_user_func_array([$this->currentController,$this->currentMethod],[$this->params]);
        }

        public function getURL() {

            $url = rtrim($_SERVER['REQUEST_URI'],'/');

            if($url) {
                $url = ltrim($url,'/');
                $url = filter_var($url,FILTER_SANITIZE_URL);
                $url = explode('/', $url);
                return $url;
            }

            return array('/');
        }
    } 