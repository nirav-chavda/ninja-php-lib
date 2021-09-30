<?php

use App\Middlewares\VerifyCSRFToken;

/**
 * App Core Class
 * Creates URL & loads core controller
 * URL Format - /controller/method/params
*/
class Core
{
    protected $currentController = 'HomeController';
    protected $currentMethod = 'index';
    protected $params = [];

    /**
     * Constructor method
     */
    public function __construct()
    {
        $root = substr(__DIR__, 0, strpos(__DIR__, '\vendor'));

        require_once $root . '\\app\\routes.php';

        $url = $this->getURL();
        $route = array();

        if (strpos($url, '/:') != false) {
            panic('Invalid URL passed');
        }

        foreach ($routes as $value) {

            $pos = strpos($value[1], '/:');

            if ($pos == false) {
                if ($value[0] == $_SERVER['REQUEST_METHOD'] && $value[1] == $url) {
                    # validating CSRF token
                    if (strtolower($value[0]) == 'post') {
                        if (!in_array($value[1], ($obj = new VerifyCSRFToken)->except)) {
                            if (!Ninja\CSRF::validate()) {
                                panic("Session Timeout");
                            }
                        }
                    }
                    $route = $value;
                    $this->params = [];
                    break;
                }
            } else {

                $count = 0;
                $route_url = str_split($value[1]);      # Array of defined route
                $passed_url = str_split($url);          # Array of passed route
                $method = $value[0];

                for ($i = 0; $i < strlen($value[1]); $i++) {
                    if ($route_url[$i] != $passed_url[$i])
                        break;
                    $count++;
                }

                if ($route_url[$count] != ':') {
                    continue;
                }

                $count = $count - 2;  # count will be on : , we have to count slashes so we need to go before slash

                $route_count = 0;
                $passed_count = 0;
                $passed_values = array();

                for ($i = $count; $i < count($route_url); $i++) {
                    if ($route_url[$i] == '/')
                        $route_count++;
                }

                for ($i = $count; $i < count($passed_url); $i++) {
                    if ($passed_url[$i] == '/') {
                        $passed_count++;
                        $passed_values[$passed_count - 1] = $i;
                    }
                }

                if ($passed_count != $route_count) {
                    continue;
                }

                $data = array();
                $data_values = array();

                # creates array key from route
                for ($i = 0; $i < count($route_url); $i++) {
                    if ($route_url[$i] == ':') {
                        $i++;
                        $j = 0;
                        $word = array();
                        while ($i < count($route_url) && $route_url[$i] != '/') {
                            $word[$j++] = $route_url[$i++];
                        }
                        $data[implode('', $word)] = "";
                        --$i;
                    }
                }

                for ($i = 0; $i < count($passed_values); $i++) {
                    if ($i < count($passed_values) - 1)
                        $data_values[$i] = substr($url, $passed_values[$i] + 1, $passed_values[$i + 1] - ($passed_values[$i] + 1));
                    else
                        $data_values[$i] = substr($url, $passed_values[$i] + 1);
                }

                $temp_counter = 0;
                foreach ($data as $key => $val) {
                    $data[$key] = $data_values[$temp_counter++];
                }

                if ($method == $_SERVER['REQUEST_METHOD']) {
                    # validating CSRF token
                    if (strtolower($method) == 'post') {
                        if (!in_array($route[1], ($obj = new VerifyCSRFToken)->except)) {
                            if (!Ninja\CSRF::validate()) {
                                panic("Session Timeout");
                            }
                        }
                    }
                    $route = $value;
                    $this->params = $data;
                }
            }
        }

        if (count($route) < 1) {
            panic("No Such Route Defined");
        }

        $method = $route[0];
        $url = $route[1];
        $action = $route[2];

        if (gettype($action) == 'string') {

            $controller_name = substr($route[2], 0, strpos($route[2], '@'));

            $method_name = substr($route[2], strpos($route[2], '@') + 1);

            $controller_name = str_replace('/', '\\', $controller_name);

            if (!file_exists($root . "\\app\\controllers\\" . $controller_name . '.php')) {
                $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
                header($protocol . ' 404 ' . 'Controller ' . $url . ' Not Found');
                exit(0);
            }

            $this->currentController = $controller_name;

            $this->currentController = 'App\\Controllers\\' . $this->currentController;

            // Instantiate the controller class (By default Home)                        
            $this->currentController = new $this->currentController;

            if (method_exists($this->currentController, $method_name)) {
                $this->currentMethod = $method_name;
            } else {
                panic("Method Not Exists");
            }

            # Getting Dictionary for Middleware
            $dictionary = new App\Dictionary;
            $dictionary = $dictionary->middlewareDictionary;

            $middlewares = $this->currentController->getMiddlewares()['middlewares'];
            $except = $this->currentController->getMiddlewares()['except'];

            if (count($middlewares) > 0) {
                if (!in_array($this->currentMethod, $except)) {
                    foreach ($middlewares as $middleware) {
                        new $dictionary[$middleware];
                    }
                }
            }
            # Applying Auth middleware in all functions
            if (strpos($controller_name, 'Auth\\') > 0) {
                # if there is no middleware set then auth will be applied
                if (count($middlewares) == 0) {
                    new $dictionary['auth'];
                }
                # if the current method is in except in case of other than auth middleware then auth will be applied in except method                
                else if (!in_array('auth', $middlewares)) {
                    if (in_array($this->currentController, $except)) {
                        new $dictionary['auth'];
                    }
                } else if (in_array('auth', $middlewares)) {
                    if (in_array($this->currentController, $except)) {
                        new $dictionary['guest'];
                    }
                }
            }

            Ninja\Session::set('referer', Ninja\Session::has('current_uri') ? Ninja\Session::get('current_uri') : '/');
            Ninja\Session::set('current_uri', ltrim($url, '/'));

            $request = new Request;

            if (gettype($action) == 'string') {
                call_user_func_array([$this->currentController, $this->currentMethod], [$request, $this->params]);
            } else {
                call_user_func_array($action, [$request, $this->params]);
            }
        }
    }

    /**
     * getURL
     * get request url
     * @return string
     */
    public function getURL()
    {
        $url = rtrim($_SERVER['REQUEST_URI'], '/');
        if ($url) {
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return $url;
        }
        return '/';
    }
}
