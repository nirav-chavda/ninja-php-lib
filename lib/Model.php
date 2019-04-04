<?php
    /*
     * PDO DATABASE CLASS
     * CONNECT TO DATABASE
     * CREATE PREPARED STATEMENTS
     * BIND VALUES
     * RETURNS ROWS AND RESULTS
     */

    class Model {

        private $driver = DB_DRIVER;
        private $host = DB_HOST;
        private $port = DB_PORT;
        private $dbname = DB_DATABASE;
        private $user = DB_USERNAME;
        private $password = DB_PASSWORD;

        private $dbh; // database handler
        private $stmt;
        private $error;

        protected $table;

        public function __construct() {
            
            $options = array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );

            // Creating instance
            try {
                if($this->driver=='sqlite') {
                    $dsn = 'sqlite:' . $this->dbname;
                    $this->dbh = new PDO($dsn,$options);
                } else {
                    $dsn = $this->driver . ':host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->dbname . ';user=' . $this->user . ';password=' . $this->password;
                    $this->dbh = new PDO($dsn, $this->user, $this->password, $options);
                }                
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                echo $this->error;
                exit();
            }
        }

        public function query($sql) {
            $this->stmt = $this->dbh->prepare($sql);
        }

        public function bind($param, $value, $type=null) {

            if(is_null($type)) {
                switch(true) {
                    case is_int($value) : 
                        $type = PDO::PARAM_INT;
                        break;
                    case is_bool($value) : 
                        $type = PDO::PARAM_BOOL;
                        break;
                    case is_null($value) : 
                        $type = PDO::PARAM_NULL;
                        break;
                    default:
                        $type = PDO::PARAM_STR;
                        break;
                }
            }

            $this->stmt->bindValue($param,$value,$type);
        }

        public function execute() {
            return $this->stmt->execute();
        }

        public function get() {
            $this->execute();
            return $this->stmt->fetchAll(PDO::FETCH_OBJ);
        }

        public function first() {
            $this->execute();
            return $this->stmt->fetch(PDO::FETCH_OBJ);
        }

        public function rowCount() {
            return $this->stmt->rowCount();
        }
    }
