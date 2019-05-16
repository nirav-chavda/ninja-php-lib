<?php

class Request
{

    function __construct()
    {
        $this->bootstrapSelf();
    }

    # this will set all SERVER COOKIE and FILES properties as a data members of the class
    private function bootstrapSelf()
    {
        $unsafe_vars = ['DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'];

        foreach ($_SERVER as $key => $value) {
            if (!in_array($key, $unsafe_vars))
                $this->{$key} = $value;
        }
        foreach ($_COOKIE as $key => $value) {
            $this->{$key} = $value;
        }
        foreach ($_FILES as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function getBody()
    {
        if ($this->REQUEST_METHOD == "GET") {
            return null;
        }

        if ($this->REQUEST_METHOD == "POST") {

            $body = array();

            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS); # sanitization
            }

            return $body;
        }
    }
}
