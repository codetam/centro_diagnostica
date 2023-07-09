<?php
class RestLuogo{
    private $conn = false;
    private $tabellaLuoghi = "Luoghi";
    public function __construct($connection)
    {
        if(!$connection){
            die("Errore nella connessione al database");
        }else{
            $this->conn = $connection;
        }
    }
    public function createLuogo($data){
        $citta = $data["citta"];
        $provincia = $data["provincia"];
        // Controlla che il luogo già esista
        $checkSql = "SELECT COUNT(*) AS count FROM " . $this->tabellaLuoghi . " WHERE citta = ? AND provincia = ?";
        $checkStmt = mysqli_stmt_init($this->conn);

        if (!mysqli_stmt_prepare($checkStmt, $checkSql)) {
            $risposta = array(
                'state' => 0,
                'message' => "Errore nella preparazione della query."
            );
            header('Content-Type: application/json');
            echo json_encode($risposta, JSON_PRETTY_PRINT);
            return;
        }

        mysqli_stmt_bind_param($checkStmt, "ss", $citta, $provincia);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_bind_result($checkStmt, $count);
        mysqli_stmt_fetch($checkStmt);
        mysqli_stmt_close($checkStmt);

        if ($count > 0) {
            $risposta = array(
                'state' => 0,
                'message' => "Il luogo esiste già nel database."
            );
            header('Content-Type: application/json');
            echo json_encode($risposta, JSON_PRETTY_PRINT);
            return;
        }

        $sql = "INSERT INTO " .$this->tabellaLuoghi. " 
        (citta, provincia) VALUES(?,?);";
        $stmt = mysqli_stmt_init($this->conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $risposta = array(
                'state' => 0,
                'message' => "Creazione luogo fallita."
            );
            header('Content-Type: application/json');
            echo json_encode($risposta, JSON_PRETTY_PRINT);
            return;
        }

        mysqli_stmt_bind_param($stmt, "ss", 
                                    $citta,
                                    $provincia);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $message = "Luogo creato con successo.";
            $state = 1;
        } else {
            $message = "Creazione luogo fallita.";
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

    public function getLuogo($id) {		
		$query = "
			SELECT id, citta, provincia
			FROM ".$this->tabellaLuoghi." 
            WHERE id = ?";	
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);

		$luoghi = array();
		while( $record = mysqli_fetch_assoc($resultData) ) {
			$luoghi[] = $record;
		}
        mysqli_stmt_close($stmt);

		header('Content-Type: application/json');
        echo json_encode($luoghi, JSON_PRETTY_PRINT);	
	}
    public function getLuoghi() {		
		$query = "
			SELECT id, citta, provincia
			FROM ".$this->tabellaLuoghi."
			ORDER BY id ASC";	
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);

		$luoghi = array();
		while( $record = mysqli_fetch_assoc($resultData) ) {
            array_push($luoghi, $record);
		}
        mysqli_stmt_close($stmt);

		header('Content-Type: application/json');
        echo json_encode($luoghi, JSON_PRETTY_PRINT);	
	}
    public function deleteLuogo($id) {
        if($id){
		    $query = "
		    	DELETE FROM ".$this->tabellaLuoghi." 
		    	WHERE id = ?
                ORDER BY id DESC";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
		    if( mysqli_stmt_affected_rows($stmt) > 0 ) {
		    	$messaggio = "Luogo cancellato con successo.";
		    	$state = 1;			
		    } else {
		    	$messaggio = "Cancellazione luogo fallita.";
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
    function updateLuogo($data){ 		
		if($data["id"]) {
			$citta=$data["citta"];
			$provincia=$data["provincia"];
			$query="
				UPDATE ".$this->tabellaLuoghi." 
				SET citta=?, provincia=?
				WHERE id = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "ssi", 
                                    $citta,
                                    $provincia,
                                    $data["id"]);
            mysqli_stmt_execute($stmt);
			if( mysqli_stmt_affected_rows($stmt) > 0 ) {
				$messaggio = "Luogo aggiornato con successo.";
				$state = 1;			
			} else {
				$messaggio = "Aggiornamento luogo fallito.";
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