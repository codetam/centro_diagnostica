<?php
class RestOperatore{
    private $conn = false;
    private $tabellaOperatori = "Operatori";
    public function __construct($connection)
    {
        if(!$connection){
            die("Errore nella connessione al database");
        }else{
            $this->conn = $connection;
        }
    }
    public function createOperatore($data){
        $nome = $data["nome"];
        $cognome = $data["cognome"];
        $email = $data["email"];
        $password = $data["password"];

        $sql = "INSERT INTO " .$this->tabellaOperatori. " 
        (nome, cognome, email, password) VALUES(?,?,?,?);";
        $stmt = mysqli_stmt_init($this->conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $risposta = array(
                'state' => 0,
                'message' => "Creazione operatore fallita."
            );
            header('Content-Type: application/json');
            echo json_encode($risposta, JSON_PRETTY_PRINT);
            return;
        }

        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "ssss", 
                                    $nome,
                                    $cognome,
                                    $email,
                                    $hashedPwd);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $message = "Operatore creato con successo.";
            $state = 1;
        } else {
            $message = "Creazione operatore fallita.";
            $state = 0;
        }
        $risposta = array(
            'state' => $state,
            'message' => $message
        );
        mysqli_stmt_close($stmt);
        
		header('Content-Type: application/json');
		echo json_encode($risposta, JSON_PRETTY_PRINT);
    }
    public function getOperatore($id) {		
		$query = "
			SELECT id, nome, cognome, email, num_esami
			FROM ".$this->tabellaOperatori." 
            WHERE id = ?";	
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);

		$operatore;
		while( $record = mysqli_fetch_assoc($resultData) ) {
			$operatore = $record;
		}
        mysqli_stmt_close($stmt);

		header('Content-Type: application/json');
        echo json_encode($operatore, JSON_PRETTY_PRINT);	
	}
    public function getOperatori() {		
		$query = "
			SELECT id, nome, cognome, email, num_esami
			FROM ".$this->tabellaOperatori;

        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);

		$operatori = array();
		while( $record = mysqli_fetch_assoc($resultData) ) {
			array_push($operatori,$record);
		}
        mysqli_stmt_close($stmt);

		header('Content-Type: application/json');
        echo json_encode($operatori, JSON_PRETTY_PRINT);	
	}
    public function deleteOperatore($id) {
        if($id){
		    $query = "
		    	DELETE FROM ".$this->tabellaOperatori." 
		    	WHERE id = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
		    if( mysqli_stmt_affected_rows($stmt) > 0 ) {
		    	$messaggio = "Operatore cancellato con successo.";
		    	$state = 1;			
		    } else {
		    	$messaggio = "Cancellazione operatore fallita.";
		    	$state = 0;			
		    }		
            mysqli_stmt_close($stmt);
	    } 
        else {
		    $messaggio = "Richiesta non valida.";
		    $state = 0;
	    }
	    $risposta = array(
	    	'state' => $state,
	    	'messaggio' => $messaggio
	    );

	    header('Content-Type: application/json');
	    echo json_encode($risposta, JSON_PRETTY_PRINT);	
	}
    function updateOperatore($data){ 		
		if($data["id"]) {
			$nome=$data["nome"];
			$cognome=$data["cognome"];
			$email=$data["email"];
			$password=$data["password"];
			$num_esami=$data["nun_esami"];
			$query="
				UPDATE ".$this->tabellaOperatori." 
				SET nome=?, cognome=?, email=?, password=?, num_esami=?
				WHERE id = ?";
            $stmt = mysqli_prepare($this->conn, $query);

            $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "ssssii", 
                                    $nome,
                                    $cognome,
                                    $email,
                                    $hashedPwd,
                                    $num_esami,
                                    $data["id"]);
            mysqli_stmt_execute($stmt);
			if( mysqli_stmt_affected_rows($stmt) > 0 ) {
				$messaggio = "Operatore aggiornato con successo.";
				$state = 1;			
			} else {
				$messaggio = "Aggiornamento operatore fallito.";
				$state = 0;
			}
            mysqli_stmt_close($stmt);
		} 
        else {
			$messaggio = "Richiesta non valida.";
			$state = 0;
		}
		$risposta = array(
			'state' => $state,
			'messaggio' => $messaggio
		);
        
		header('Content-Type: application/json');
		echo json_encode($risposta, JSON_PRETTY_PRINT);
	}
}