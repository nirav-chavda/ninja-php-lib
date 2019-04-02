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

    public function __construct() {
        // taking connection
        \$this->db = new Model;
        // name of the associated table
        \$this->table = '';
    }

    public function insert(\$data) {
            
        \$sql = 'INSERT INTO '. \$this->table . '(';
        \$values = ' VALUES(';
        
        foreach (\$data as \$key => \$value) {
            \$sql .= \$key . ',';
            \$values .= ':' . \$key . ',';
        }

        \$sql = rtrim(\$sql,',');
        \$values = rtrim(\$values,',');

        \$sql .= ')';
        \$values .= ')';

        \$sql .= \$values;

        \$this->db->query(\$sql);
        foreach (\$data as \$key => \$value) {
            \$this->db->bind(':' . \$key , \$value);
        }
        \$this->db->execute();
    }
}";