<?php
    class DatabaseConnection {
        private $db_dsn;
        private $db_user;
        private $db_pass;
        private $db_con;

        public function __construct($db_host, $db_name, $db_usr, $db_pwd)
        {
            $this->db_dsn = 'mysql:host='.$db_host.';dbname='.$db_name;
            $this->db_user = $db_usr;
            $this->db_pass = $db_pwd;
            $this->db_con = null;
        }

        public function openConnection()
        {
            try
            {
                $this->db_con = new PDO($this->db_dsn, $this->db_user, $this->db_pass);
                $this->db_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch (PDOException $exc) {
                printf($this->db_dsn."\n");
                die("Database not available : " . $exc->getMessage() );
            }
            return $this->db_con;
        }

        public function closeConnection()
        {
            $this->db_con = null;
        }
    }

?>