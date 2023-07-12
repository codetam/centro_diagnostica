# Centro di diagnostica - progetto SWBD

# Istruzioni per l'installazione

Ambiente di sviluppo: 

- Windows 10/11 con WSL2
- Distribuzione Linux che permette il download di Docker


## 1. Installa Docker e docker-compose

Segui le istruzioni proposte dalla guida ufficiale: 
https://docs.docker.com/engine/install/

E' raccomandata l'installazione di Docker Desktop con backend WSL-2 per i dispositivi Windows.

## 2. Clona la repository sul dispositivo

Apri il terminale e digita:

    user@computer:~ $ git clone git@github.com:codetam/centro_diagnostica.git

## 3. Cambia la password per accedere al database

Modifica il file docker-compose.yml e cambia la password in corrispondenza di **MYSQL_ROOT_PASSWORD**.
~~~docker
database:
    ports:
      - "3306:3306"
    build: './build/mysql'
    environment:
      MYSQL_ROOT_PASSWORD: SECRET_PASSWORD
      MYSQL_DATABASE: centro_diagnostica
    volumes:
      - ./db:/var/lib/mysql
    networks:
      - default
~~~
Modifica il file *app/src/Database.php* e cambia la password in corrispondenza di **$password**.
~~~php
<?php

class Database{
    private $host = 'database';
    private $user = 'root';
    private $password = "SECRET_PASSWORD";
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
~~~

## 4. Fai partire il comando docker compose

Entra nella cartella clonata e fai partire il comando docker compose. 
~~~shell
user@computer:~ $ cd centro_diagnostica
user@computer:~/centro_diagnostica $ docker compose up -d
~~~
Dopo l'esecuzione di questo comando, tutti i container necessari per la creazione del webserver e del database saranno in funzione.

## 5. Copia del dump SQL

Sposta il dump nella cartella *db/* per facile accesso dal container mysql.
~~~shell
user@computer:~/centro_diagnostica $ sudo mv centro_diagnostica_dump.sql db
~~~
Accedi al container mysql e fai il restore del dump.
~~~shell
user@computer:~/centro_diagnostica $ docker exec -it centro_diagnostica-database-1 /bin/bash
bash-4.4$ mysql -u root -p centro_diagnostica < /var/lib/mysql/centro_diagnostica_dump.sql
Enter password: # Scrivi la SECRET_PASSWORD cambiata precedentemente
~~~

## 6. Installazione terminata

L'installazione è terminata, accedendo a http://localhost si può visualizzare il sito web.

Phpmyadmin: http://localhost:8080
