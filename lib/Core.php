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
            
            $root = substr(__DIR__,0,strpos(__DIR__,'\vendor'));

            require_once $root . '\\app\\routes.php';

            $url = $this->getURL();
            $route = array();

            if(strpos($url,'/:') != false) {
                die('Invalid URL passed');
            }

            foreach ($routes as $value) {
                
                $pos = strpos($value[1],'/:');

                if($pos == false) {
                    if($value[0]==$_SERVER['REQUEST_METHOD'] && $value[1]==$url) {
                        $route = $value;
                        $this->params = [];
                        break;
                    }    
                } else {

                    $count = 0;
                    $route_url = str_split($value[1]);      // Array of defined route
                    $passed_url = str_split($url);          // Array of passed route

                    for($i=0;$i<strlen($value[1]);$i++) {
                        if($route_url[$i]!=$passed_url[$i])
                            break;
                        $count++;
                    }

                    if($route_url[$count] != ':'){
                        continue;
                    }

                    $count=$count-2;  // count will be on : , we have to count slashes so we need to go before slash

                    $route_count = 0;
                    $passed_count = 0;
                    $passed_values = array();

                    for($i=$count;$i<count($route_url);$i++) {
                        if($route_url[$i]=='/')
                            $route_count++;
                    }
                    
                    for($i=$count;$i<count($passed_url);$i++) {
                        if($passed_url[$i]=='/') {
                            $passed_count++;
                            $passed_values[$passed_count-1] = $i;
                        }
                    }

                    if($passed_count != $route_count) {
                        continue;
                    }
                    
                    $data = array();

                    for($i=0;$i<count($passed_values);$i++) {
                        if($i<count($passed_values)-1)
                            $data[$i] = substr($url,$passed_values[$i]+1,$passed_values[$i+1] - ($passed_values[$i]+1));
                        else
                            $data[$i] = substr($url,$passed_values[$i]+1);
                    }

                    if($value[0]==$_SERVER['REQUEST_METHOD']) {
                        $route = $value;
                        $this->params = $data;
                    }
                }
            }

            if(count($route)<1) {
                die("No Such Route Defined");
            }

            $method = $route[0];
            $url = $route[1];
            $controller_name = substr($route[2],0,strpos($route[2],'@'));
            $method_name = substr($route[2],strpos($route[2],'@')+1);

            $controller_name = str_replace('/','\\',$controller_name);

            if(file_exists($root . "\\app\\controllers\\" . $controller_name . '.php')) {
                if(strpos($controller_name,'\\'))
                    $this->currentController = substr($controller_name,strripos($controller_name,'\\')+1);
                else
                    $this->currentController = $controller_name;            
            } else {
                $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
                header($protocol . ' 404 ' . 'Controller ' . $url[0] . ' Not Found');
                exit(0);
            }

            require_once $root . "\\app\\controllers\\" . $controller_name . ".php";

            // Instantiate the controller class (Bydefault Home)
            $this->currentController = new $this->currentController;

            if(method_exists($this->currentController,$method_name)) {
                $this->currentMethod = $method_name;
            } else {
                echo "Method Not Exists";
                exit(0);
            }

            call_user_func_array([$this->currentController,$this->currentMethod],[$this->params]);
        }

        public function getURL() {
            $url = rtrim($_SERVER['REQUEST_URI'],'/');
            if($url) {
                $url = filter_var($url,FILTER_SANITIZE_URL);
                return $url;
            }
            return '/';
        }
    } 