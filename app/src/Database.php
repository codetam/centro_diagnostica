<?php

class Database{
    // Dati sul database
    private $host = 'database';
    private $user = 'root';
    private $password = "WkG7vqWUF65W9o!z";
    private $name = "centro_diagnostica";
    private $conn = false;
    public function __construct()
    {
        $new_conn = new mysqli($this->host, $this->user, $this->password, $this->name);
        if($new_conn->connect_error){
            die("Errore nella connessione al database: " . $new_conn->connect_error);
        }else{
            $this->conn = $new_conn;
        }
    }
    public function getConnection(){
        return $this->conn;
    }
}
