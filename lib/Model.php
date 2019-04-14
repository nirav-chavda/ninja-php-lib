<?php

class Model {

    public function insert($data) {
            
        $sql = 'INSERT INTO '. $this->table . '(';
        $values = ' VALUES(';
        
        foreach ($data as $key => $value) {
            $sql .= $key . ',';
            $values .= ':' . $key . ',';
        }

        $sql = rtrim($sql,',');
        $values = rtrim($values,',');

        $sql .= ')';
        $values .= ')';

        $sql .= $values;

        $this->db->query($sql);
        foreach ($data as $key => $value) {
            $this->db->bind(':' . $key , $value);
        }
        $this->db->execute();
    }

    public function get($key=null, $name=null) {
        if( empty($key) || empty($name) ) {
            $sql = 'SELECT * FROM ' . $this->table;
            $this->db->query($sql);
            return $this->db->get();
        } else {
            $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $key . ' = :' . $key;
            $this->db->query($sql);
            $this->db->bind(':' . $key ,$name);
            return $this->db->get();
        }        
    }

    public function first($key, $name) {
        if( empty($key) || empty($name) ) {
            $sql = 'SELECT * FROM ' . $this->table;
            $this->db->query($sql);
            return $this->db->first();
        } else {
            $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . $key . ' = :' . $key;
            $this->db->query($sql);
            $this->db->bind(':' . $key ,$name);
            return $this->db->first();
        }        
    }
}