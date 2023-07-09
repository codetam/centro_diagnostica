<?php
class RestReferto{
    private $conn = false;
    private $tabellaReferti = "Referti";
    public function __construct($connection)
    {
        if(!$connection){
            die("Errore nella connessione al database");
        }else{
            $this->conn = $connection;
        }
    }
    public function createReferto($data){
        $id_esame = $data["id_esame"];
        $testo = $data["testo"];

        $sql = "INSERT INTO " .$this->tabellaReferti. " 
        (id_esame, testo) VALUES(?,?);";
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
                                    $testo);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $message = "Referto creato con successo.";
            $state = 1;
        } else {
            $message = "Creazione referto fallita.";
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

    public function getReferto($id_esame) {		
		$query = "
			SELECT id_esame, testo
			FROM ".$this->tabellaReferti." 
            WHERE id_esame = ?";	
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id_esame);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);

		$referto;
		while( $record = mysqli_fetch_assoc($resultData) ) {
			$referto = $record;
		}
        mysqli_stmt_close($stmt);

		header('Content-Type: application/json');
        echo json_encode($referto, JSON_PRETTY_PRINT);	
	}
    public function deleteReferto($id_esame) {
        if($id_esame){
		    $query = "
		    	DELETE FROM ".$this->tabellaReferti." 
		    	WHERE id_esame = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id_esame);
            mysqli_stmt_execute($stmt);
		    if( mysqli_stmt_affected_rows($stmt) > 0 ) {
		    	$messaggio = "Referto cancellato con successo.";
		    	$state = 1;			
		    } else {
		    	$messaggio = "Cancellazione referto fallita.";
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
    function updateReferto($data){ 		
		if($data["id_esame"]) {
			$testo = $data["testo"];
			$query="
				UPDATE ".$this->tabellaReferti." 
				SET testo=?
				WHERE id_esame = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "si", 
                                    $testo,
                                    $data["id_esame"]);
            mysqli_stmt_execute($stmt);
			if( mysqli_stmt_affected_rows($stmt) > 0 ) {
				$messaggio = "Referto aggiornato con successo.";
				$state = 1;			
			} else {
				$messaggio = "Aggiornamento referto fallito.";
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