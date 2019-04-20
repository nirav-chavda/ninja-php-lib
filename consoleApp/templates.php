<?php

$controller_template = 
"<?php
          
namespace *Namespace*;

use Controller;

class *ClassName* extends Controller {

    public function __construct() {
        # name of Model in the blanks
        # \$this->model = \$this->model('____');    
    }
}";

$model_template = 
"<?php
            
class Name extends Model {

    protected \$db;
    protected \$table;

    public function __construct() {
        # taking connection
        \$this->db = new DB;
        # name of the associated table
        \$this->table = '';
    }
}";