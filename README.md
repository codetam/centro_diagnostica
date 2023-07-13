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

## 7. Testing

Per testare l'installazione, puoi far partire lo script *test_script.sh* dalla cartella della repository clonata.
~~~shell
user@computer:~/centro_diagnostica $ chmod 777 test_script.sh
user@computer:~/centro_diagnostica $ ./test_script.sh
~~~
Output previsto:
~~~shell
1. Aggiungo un utente nel database

{
    "state": 1,
    "message": "Utente creato con successo"
}

2. Verifico la presenza dell'utente

{
    "codice_fiscale": "TEST1234TEST1234",
    "nome": "NomeTest",
    "cognome": "CognomeTest",
    "email": "user@test.com",
    "telefono": "3334445566",
    "data_nascita": "2000-01-01",
    "citta_nascita": "CittaNascita",
    "provincia_nascita": "PN",
    "citta_residenza": "CittaResidenza",
    "provincia_residenza": "PR",
    "via": "Via",
    "numero": 10
}

3. Aggiungo un operatore nel database

{
    "state": 1,
    "message": "Operatore creato con successo."
}

4. Verifico che l'operatore sia stato aggiunto

[
    {
        "id": 3,
        "nome": "Marco",
        "cognome": "Rossi",
        "email": "marco.rossi@swbd.com",
        "num_esami": 8
    },
    {
        "id": 4,
        "nome": "Laura",
        "cognome": "Bianchi",
        "email": "laura.bianchi@swbd.com",
        "num_esami": 3
    },
    {
        "id": 5,
        "nome": "Andrea",
        "cognome": "Russo",
        "email": "andrea.russo@swbd.com",
        "num_esami": 3
    },
    {
        "id": 6,
        "nome": "Giulia",
        "cognome": "Esposito",
        "email": "giulia.esposito@swbd.com",
        "num_esami": 3
    },
    {
        "id": 12,
        "nome": "OperatoreNomeTest",
        "cognome": "OperatoreCognomeTest",
        "email": "operatore@test.com",
        "num_esami": 0
    }
]

5. Aggiungo un esame nel database

{
    "state": 1,
    "message": "Esame creato con successo."
}

6. Visualizzo gli esami associati all'utente

[
    {
        "id": 42,
        "terminato": 0,
        "tipologia": "ecografia",
        "data": "2023-12-12",
        "ora": "10:00:00",
        "codice_utente": "TEST1234TEST1234",
        "id_operatore": 3
    }
]
~~~
