<?php

Class Database {
    
    private $host;
    private $username;
    private $passwd;
    private $dbname;
    private $link;
    
    public function __construct($host = 'localhost', $username = 'root', $passwd = '', $dbname = 'quality') {
        $this->host = $host;
        $this->username = $username;
        $this->passwd = $passwd;
        $this->dbname = $dbname;
        $this->link = $this->connect();
    }
    
    private function connect(){
        if(!$link = mysqli_connect($this->host, $this->username, $this->passwd, $this->dbname)){
            header('Location: /front/conexao.php');
            exit();
        } else {
            mysqli_set_charset($link, "utf8");
            return $link;
        }
    }

    public function insert($table, $columns, $values) {
        if($this->link){
            $cols = implode(',', $columns);
            $vals = '';

            foreach($values as $value){
                if(strlen($value)>0 && $value != 'null'){
                    $vals .= "'$value',";
                } else {
                    $vals .= 'null,'; 
                }
            }

            $vals = rtrim($vals,',');
            $sql = "INSERT INTO $table (".$cols.") VALUES (".$vals.")";

            if(!mysqli_query($this->link,$sql)){
                if(!is_dir('error_log')){
                    mkdir("error_log", 0755);
                }
                file_put_contents('error_log/'.uniqid().".txt", "Mysql Erro: ".mysqli_error($this->link)." SQL QUERY: ".$sql);
                return false;
            } else {
                $id = mysqli_insert_id($this->link);
                return $id;
            }
        }
    }
    
    public function update($table, $columns, $values, $where) {
        if($this->link){
            $sql = "UPDATE $table SET ";
            foreach($columns as $key => $col){
                if($values[$key] != 'null'){
                    $val = ltrim($values[$key],"'");
					$val = rtrim($val,"'");
                    $sql .= "$col = '{$val}',"; 
                } else {
                    $sql .= "$col = null,"; 
                }
            }

            $sql = rtrim($sql,",");
            $sql .= " WHERE $where";
            
            if(!mysqli_query($this->link,$sql)){
                if(!is_dir('error_log')){
                    mkdir("error_log", 0755);
                }
                file_put_contents('error_log/'.uniqid().".txt", "Mysql Erro: ".mysqli_error($this->link)." SQL QUERY: ".$sql);
                return false;
            } else {
                return true;
            }
        }
    }

    public function delete($table, $value, $column = 'id') {
        if($this->link) {
            $sql = "DELETE FROM $table WHERE $column = $value";

            $result = mysqli_query($this->link, $sql);
            if($result) {
                return true;
            } else {
                if(!is_dir('error_log')){
                    mkdir("error_log", 0755);
                }
                file_put_contents('error_log/'.uniqid().".txt", "Mysql Erro: ".mysqli_error($this->link)." SQL QUERY: ".$sql);
                return false;
            }
        }
    }
    
    public function selectQueryAssoc($sql) {
        if($this->link){
            $result = mysqli_query($this->link, $sql);
            if($result) {
                $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
                mysqli_free_result($result);
                return $data;
            } else {
                if(!is_dir('error_log')){
                    mkdir("error_log", 0755);
                }
                file_put_contents('error_log/'.uniqid().".txt", "Mysql Erro: ".mysqli_error($this->link)." SQL QUERY: ".$sql);
                return false;
            }
        }
    }

    public function disconnect(){
        if($this->link){
            mysqli_close($this->link);
        }
    }
}