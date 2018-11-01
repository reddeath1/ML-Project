<?php
/**
 * Created by PhpStorm.
 * User: reddeath
 * Date: 10/27/2018
 * Time: 10:44 PM
 */


class DB
{
    public $con;
    const db_user = 'root';
    const db_host =  'localhost';
    const db_pass = '';
    const port = 3306;
    const db = 'ml';

    public function __construct(){
       $this->connect();
    }

    private function connect(){
        $sql = new mysqli(self::db_host,self::db_user,self::db_pass,self::db,self::port);

        if($sql->connect_errno){
            $this->con = $sql->connect_error;
        }else
        {
            $this->con = $sql;
        }
    }

    public function insert($param){
        $param = json_decode($param);
        $table = $param->table;
        $data = $param->data;

        return ($this->con->query("INSERT INTO $table SET $data")) ? "<p>Data inserted into $table table!</p>" : $this->con->error;
    }

    public function delete($param){
        $param = json_decode($param);
        $table = $param->table;
        $data = $param->data;

        return $this->con->query("DELETE FROM $table WHERE $data");
    }

    public function update($param){
        $param = json_decode($param);
        $table = $param->table;
        $data = $param->data;
        $where = (isset($param->where)) ? " WHERE ".$param->where : '';

        return $this->con->query("UPDATE $table SET $data $where");
    }

    public function select($param){
        $param = json_decode($param);
        $table = $param->table;
        $cols = $param->cols;
        $where = (isset($param->where)) ? " WHERE ".$param->where : '';

        $sql  = $this->con->query("SELECT $cols FROM $table $where");

        return $sql;
    }

    public function query($param){
        $param = json_decode($param);
        $query = $param->query;

        return $this->con->query("$query");
    }

    public function createTable($param){
        $param = json_decode($param);
        $table = $param->name;
        $value = $param->value;

        $sql = $this->con->query("CREATE TABLE IF NOT EXISTS $table ($value)");

        return ($sql) ? "<p>Table $table created!</p>" : $this->con->error;
    }

    public function dropTable($param){
        $param = json_decode($param);
        $table = $param->name;

        return $this->con->query("
                SET FOREIGN_KEY_CHECKS = 0;
                drop table if exists $table;
                SET FOREIGN_KEY_CHECKS = 1;");
    }

    public function createDB($name){
        $s = new mysqli(self::db_host,self::db_user,self::db_pass,self::port);

        $sql = $s->query(json_encode(array('query'=>"
            CREATE DATABASE $name;
        ")));

       return !$sql ? $s->error : "OK";
    }
}

$DB = new DB();
