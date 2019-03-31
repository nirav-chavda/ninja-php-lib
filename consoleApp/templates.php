<?php

$controller_template = 
"<?php
            
class Name extends Controller {

    public function __construct() {
        # name of Model in the blanks
        //\$this->____Model = \$this->model('____');    
    }
}";

$model_template = 
"<?php
            
class Name {

    private \$db;

    public function __construct() {
        // taking connection
        \$this->db = new Model;
    }

}";