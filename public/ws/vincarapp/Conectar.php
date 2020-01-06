<?php

    class Conectar{
        private $driver;
        private $host, $user, $pass, $database, $charset;

        public function __construct() {

            if(file_exists('database.php'))
                $db_cfg = require 'database.php';

            $this->driver=$db_cfg["driver"];
            $this->host=$db_cfg["host"];
            $this->user=$db_cfg["user"];
            $this->pass=$db_cfg["pass"];
            $this->database=$db_cfg["database"];
            $this->charset=$db_cfg["charset"];
        }

        public function conexion(){

            if($this->driver=="mysql" || $this->driver==null){
                $con=new mysqli($this->host, $this->user, $this->pass, $this->database);
                $con->query("SET NAMES '".$this->charset."'");

            }

            if($this->driver=="pgsql" || $this->driver==null){
                $pg = pg_connect( "user=".$this->user." ".
                    "password=".$this->pass." ".
                    "host=".$this->host." ".
                    "dbname=".$this->database
                ) or die( "Error al conectar: ".pg_last_error() );

                $con = new db($pg);

                $con->query("SET NAMES '".$this->charset."'");



            }

            return $con;
        }

        public function startFluent(){
            require_once "FluentPDO/FluentPDO.php";

            if($this->driver=="mysql" || $this->driver==null){
                $pdo = new PDO($this->driver.":dbname=".$this->database, $this->user, $this->pass);
                $fpdo = new FluentPDO($pdo);
            }

            if($this->driver=="pgsql" || $this->driver==null){
                $pdo = new PDO($this->driver.":dbname=".$this->database, $this->user, $this->pass);
                $fpdo = new FluentPDO($pdo);
            }

            return $fpdo;
        }
    }

    class db{
     private $con;
        public function __construct($con) {
            $this->con=$con;
        }

        public function query($sql){
            $rs =    pg_query($this->con, $sql);

            if( $rs ) {


                if(is_numeric(strpos(strtolower($sql),"select")))
                if(strpos(strtolower($sql),"select")>=0) {


                    if (pg_num_rows($rs) > 0) {

                        $res = new res($rs);

                        return $res;

                    } else {
                        return false;
                    }
                }



                return $rs;

            }else{
                return false;
            }

        }


    }

    class res {

        private $rec;

        public function __construct($rec) {
            $this->rec=$rec;
        }

    public function fetch_object(){
           return pg_fetch_object($this->rec);
    }

        public function count(){
            return pg_num_rows($this->rec);
        }

    }