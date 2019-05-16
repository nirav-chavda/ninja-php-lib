<?php
/** 
 * Base Controller
 * Loads the models and views
*/

class Controller
{
    private $middlewares = array();
    private $except = array();

    public function middleware($values, $except = '')
    {
        if (gettype($values) == 'string')
            $this->middlewares[0] = $values;
        else
            $this->middlewares = $values;
        if (isset($except['except']) && count($except['except']) > 0)
            $this->except = $except['except'];
    }

    public function getMiddlewares()
    {
        return [
            'middlewares' => $this->middlewares,
            'except' => $this->except
        ];
    }

    public function view($view, $data = [])
    {

        $root = substr(__DIR__, 0, strpos(__DIR__, '\vendor'));

        if (file_exists($root . "\\app\\views\\" . $view . '.php')) {
            require_once $root . "\\app\\views\\" . $view . '.php';
        } else {
            die($view . ' view not found');
        }
    }
}
