<?php
class RestEsame{
    private $conn = false;
    private $tabellaEsami = "Esami";
    public function __construct($connection)
    {
        if(!$connection){
            die("Errore nella connessione al database");
        }else{
            $this->conn = $connection;
        }
    }
    public function createEsame($data){
        $terminato = $data["terminato"];
        $tipologia = $data["tipologia"];
        $data_esame = $data["data"];
        $ora_esame = $data["ora"];
        $codice_utente = $data["codice_utente"];
        $id_operatore = $data["id_operatore"];

        $sql = "INSERT INTO " .$this->tabellaEsami. " 
        (terminato, tipologia, data, ora, codice_utente, id_operatore) VALUES(?,?,?,?,?,?);";
        $stmt = mysqli_stmt_init($this->conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $risposta = array(
                'state' => 0,
                'message' => "Creazione esame fallita."
            );
            header('Content-Type: application/json');
            echo json_encode($risposta, JSON_PRETTY_PRINT);
            return;
        }

        mysqli_stmt_bind_param($stmt, "issssi", 
                                    $terminato,
                                    $tipologia,
                                    $data_esame,
                                    $ora_esame,
                                    $codice_utente,
                                    $id_operatore);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $message = "Esame creato con successo.";
            $state = 1;
        } else {
            $message = "Creazione esame fallita.";
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

    public function getEsame($id) {		
		$query = "
			SELECT id, terminato, tipologia, data, ora, codice_utente, id_operatore 
			FROM ".$this->tabellaEsami." 
            WHERE id = ?";	
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);

		$esame;
		while( $record = mysqli_fetch_assoc($resultData) ) {
			$esame = $record;
		}
        mysqli_stmt_close($stmt);

		header('Content-Type: application/json');
        echo json_encode($esame, JSON_PRETTY_PRINT);	
	}
    public function getEsamiData($data_esami) {
		$query = "
			SELECT id, terminato, tipologia, data, ora, codice_utente, id_operatore 
			FROM ".$this->tabellaEsami." 
            WHERE data = ?
			ORDER BY id ASC";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $data_esami);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);

		$esami = array();
		while( $record = mysqli_fetch_assoc($resultData) ) {
            array_push($esami, $record);
		}
        mysqli_stmt_close($stmt);

		header('Content-Type: application/json');
        echo json_encode($esami, JSON_PRETTY_PRINT);	
	}
    public function getEsamiUtente($codice_fiscale) {
		$query = "
			SELECT id, terminato, tipologia, data, ora, codice_utente, id_operatore 
			FROM ".$this->tabellaEsami." 
            WHERE codice_utente = ?
			ORDER BY data ASC";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $codice_fiscale);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);

		$esami = array();
		while( $record = mysqli_fetch_assoc($resultData) ) {
            array_push($esami, $record);
		}
        mysqli_stmt_close($stmt);

		header('Content-Type: application/json');
        echo json_encode($esami, JSON_PRETTY_PRINT);	
	}
    public function getEsami() {
		$query = "
			SELECT id, terminato, tipologia, data, ora, codice_utente, id_operatore 
			FROM ".$this->tabellaEsami."
			ORDER BY id ASC";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);
        
		$esami = array();
		while( $record = mysqli_fetch_assoc($resultData) ) {
            array_push($esami, $record);
		}
        mysqli_stmt_close($stmt);

		header('Content-Type: application/json');
        echo json_encode($esami, JSON_PRETTY_PRINT);	
	}
    public function deleteEsame($id) {
        if($id){
		    $query = "
		    	DELETE FROM ".$this->tabellaEsami." 
		    	WHERE id = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
		    if( mysqli_stmt_affected_rows($stmt) > 0 ) {
		    	$messaggio = "Esame cancellato con successo.";
		    	$state = 1;			
		    } else {
		    	$messaggio = "Cancellazione esame fallita.";
		    	$state = 0;			
		    }		
            mysqli_stmt_close($stmt);
	    } 
        else {
		    $messaggio = "Richiesta non valida.";
		    $state = 0;
	    }
	    $rispostaEsame = array(
	    	'state' => $state,
	    	'messaggio' => $messaggio
	    );

	    header('Content-Type: application/json');
	    echo json_encode($rispostaEsame, JSON_PRETTY_PRINT);	
	}
    function updateEsame($data){ 		
		if($data["id"]) {
			$terminato=$data["terminato"];
			$tipologia=$data["tipologia"];
			$data_esame=$data["data"];
			$codice_utente=$data["codice_utente"];		
			$id_operatore=$data["id_operatore"];
			$query="
				UPDATE ".$this->tabellaEsami." 
				SET terminato=?, tipologia=?, data=?, 
                codice_utente=?, id_operatore=? 
				WHERE id = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "isssii", 
                                    $terminato,
                                    $tipologia,
                                    $data_esame,
                                    $codice_utente,
                                    $id_operatore,
                                    $data["id"]);
            mysqli_stmt_execute($stmt);
			if( mysqli_stmt_affected_rows($stmt) > 0 ) {
				$messaggio = "Esame aggiornato con successo.";
				$state = 1;			
			} else {
				$messaggio = "Aggiornamento esame fallito.";
				$state = 0;			
			}
            mysqli_stmt_close($stmt);
		} 
        else {
			$messaggio = "Richiesta non valida.";
			$state = 0;
		}
		$rispostaEsame = array(
			'state' => $state,
			'messaggio' => $messaggio
		);
        
		header('Content-Type: application/json');
		echo json_encode($rispostaEsame, JSON_PRETTY_PRINT);
	}
}