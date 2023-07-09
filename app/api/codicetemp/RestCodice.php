<?php
class RestCodice{
    private $conn = false;
    private $tabellaCodici = "CodiciTemporanei";
    public function __construct($connection)
    {
        if(!$connection){
            die("Errore nella connessione al database");
        }else{
            $this->conn = $connection;
        }
    }
    private function checkCodice($id_esame){
        $query = "
			SELECT id_esame, codice_univoco
			FROM ".$this->tabellaCodici." 
            WHERE id_esame = ?";	
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id_esame);
        mysqli_stmt_execute($stmt);
        
        $resultData = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

		if (mysqli_num_rows($resultData) > 0) {
            $query = "
                DELETE FROM ".$this->tabellaCodici." 
                WHERE id_esame = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id_esame);
            mysqli_stmt_execute($stmt);
            if( mysqli_stmt_affected_rows($stmt) == 0 ) {
                return false;
            }
        } 
        return true;
    }
    public function createCodice($data){
        $id_esame = $data["id_esame"];

        if(!$this->checkCodice($id_esame)){
            $risposta = array(
                'state' => 0,
                'message' => "Errore nella creazione del codice."
            );
            
            header('Content-Type: application/json');
            echo json_encode($risposta, JSON_PRETTY_PRINT);
        }

        $codice_univoco = bin2hex(random_bytes(16));

        $sql = "INSERT INTO " .$this->tabellaCodici. " 
        (id_esame, codice_univoco) VALUES(?,?);";
        $stmt = mysqli_stmt_init($this->conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $risposta = array(
                'state' => 0,
                'message' => "Creazione referto fallita."
            );
            header('Content-Type: application/json');
            echo json_encode($risposta, JSON_PRETTY_PRINT);
            return;
        }

        mysqli_stmt_bind_param($stmt, "is", 
                                    $id_esame,
                                    $codice_univoco);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $message = "Codice creato con successo.";
            $state = 1;
        } else {
            $message = "Creazione codice fallita.";
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

    public function getCodice($id_esame) {		
		$query = "
			SELECT id_esame, codice_univoco
			FROM ".$this->tabellaCodici." 
            WHERE id_esame = ?";	
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id_esame);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);

		$codice;
		while( $record = mysqli_fetch_assoc($resultData) ) {
			$codice = $record;
		}
        mysqli_stmt_close($stmt);

		header('Content-Type: application/json');
        echo json_encode($codice, JSON_PRETTY_PRINT);	
	}
    public function getCodiceCode($codice_univoco) {	
        $query = "
        SELECT 
            CodiciTemporanei.codice_univoco,
            CodiciTemporanei.id_esame,
            Esami.tipologia,
            Esami.data,
            Esami.ora,
            Esami.codice_utente,
            Esami.terminato,
            Esami.id_operatore,
            Referti.testo
        FROM
            CodiciTemporanei
            JOIN Esami ON Esami.id = CodiciTemporanei.id_esame
            JOIN Referti ON Referti.id_esame = CodiciTemporanei.id_esame
        WHERE
            CodiciTemporanei.codice_univoco = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $codice_univoco);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);

		$codice;
		while( $record = mysqli_fetch_assoc($resultData) ) {
			$codice = $record;
		}
        mysqli_stmt_close($stmt);

		header('Content-Type: application/json');
        echo json_encode($codice, JSON_PRETTY_PRINT);	
	}
    public function deleteCodice($id_esame) {
        if($id_esame){
		    $query = "
		    	DELETE FROM ".$this->tabellaCodici." 
		    	WHERE id_esame = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id_esame);
            mysqli_stmt_execute($stmt);
		    if( mysqli_stmt_affected_rows($stmt) > 0 ) {
		    	$messaggio = "Codice cancellato con successo.";
		    	$state = 1;			
		    } else {
		    	$messaggio = "Cancellazione codice fallita.";
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