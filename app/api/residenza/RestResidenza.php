<?php
class RestResidenza{
    private $conn = false;
    private $tabellaResidenze = "Residenze";
    public function __construct($connection)
    {
        if(!$connection){
            die("Errore nella connessione al database");
        }else{
            $this->conn = $connection;
        }
    }
    public function createResidenza($data){
        $codice_utente = $data["codice_utente"];
        $id_luogo = $data["id_luogo"];
        $via = $data["via"];
        $numero = $data["numero"];

        $sql = "INSERT INTO " .$this->tabellaResidenze. " 
        (codice_utente, id_luogo, via, numero) VALUES(?,?,?,?);";
        $stmt = mysqli_stmt_init($this->conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $risposta = array(
                'state' => 0,
                'message' => "Creazione residenza fallita."
            );
            header('Content-Type: application/json');
            echo json_encode($risposta, JSON_PRETTY_PRINT);
            return;
        }

        mysqli_stmt_bind_param($stmt, "sisi", 
                                    $codice_utente,
                                    $id_luogo,
                                    $via,
                                    $numero);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $message = "Residenza creata con successo.";
            $state = 1;
        } else {
            $message = "Creazione residenza fallita.";
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
    public function getResidenza($codice_fiscale) {		
		$query = "
			SELECT codice_utente, id_luogo, via, numero
			FROM ".$this->tabellaResidenze." 
            WHERE codice_utente = ?";	
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $codice_fiscale);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);

		$residenza;
		while( $record = mysqli_fetch_assoc($resultData) ) {
			$residenza = $record;
		}
        mysqli_stmt_close($stmt);

		header('Content-Type: application/json');
        echo json_encode($residenza, JSON_PRETTY_PRINT);	
	}
    public function deleteResidenza($codice_utente) {
        if($codice_utente){
		    $query = "
		    	DELETE FROM ".$this->tabellaResidenze." 
		    	WHERE codice_utente = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $codice_utente);
            mysqli_stmt_execute($stmt);
		    if( mysqli_stmt_affected_rows($stmt) > 0 ) {
		    	$messaggio = "Residenza cancellata con successo.";
		    	$state = 1;			
		    } else {
		    	$messaggio = "Cancellazione residenza fallita.";
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
    function updateResidenza($data){ 		
		if($data["codice_utente"]) {
            $id_luogo = $data["id_luogo"];
            $via = $data["via"];
            $numero = $data["numero"];
			$query="
				UPDATE ".$this->tabellaResidenze." 
				SET id_luogo=?, via=?, numero=?
				WHERE codice_utente = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "isis", 
                                    $id_luogo,
                                    $via,
                                    $numero,
                                    $data["codice_utente"]);
            mysqli_stmt_execute($stmt);
			if( mysqli_stmt_affected_rows($stmt) > 0 ) {
				$messaggio = "Residenza aggiornata con successo.";
				$state = 1;			
			} else {
				$messaggio = "Aggiornamento residenza fallito.";
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