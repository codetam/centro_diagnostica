<?php
class RestImmagine{
    private $conn = false;
    private $tabellaImmagini = "Immagini";
    public function __construct($connection)
    {
        if(!$connection){
            die("Errore nella connessione al database");
        }else{
            $this->conn = $connection;
        }
    }
    public function createImmagine($data) {
        $contenuto = $data["contenuto"];
        $id_esame = $data["id_esame"];
        $nome = $data["nome"];
    
        $sql = "INSERT INTO " . $this->tabellaImmagini . " (contenuto, id_esame, nome) VALUES(?, ?, ?);";
        $stmt = mysqli_stmt_init($this->conn);
    
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $risposta = array(
                'state' => 0,
                'message' => "Salvataggio immagine fallito."
            );
            header('Content-Type: application/json');
            echo json_encode($risposta, JSON_PRETTY_PRINT);
            return;
        }
    
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "bis", $content_param, $id_esame, $nome);
    
        // Send the blob content as a stream of chunks
        mysqli_stmt_send_long_data($stmt, 0, $contenuto);
    
        // Execute the statement
        mysqli_stmt_execute($stmt);
    
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $message = "Immagine salvata con successo.";
            $state = 1;
        } else {
            $message = "Salvataggio immagine fallito.";
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
    
    public function getImmagine($id) {		
		$query = "
			SELECT id, contenuto, nome, id_esame
			FROM ".$this->tabellaImmagini." 
            WHERE id = ?
			ORDER BY id ASC";	
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);

		$immagine;
		while( $record = mysqli_fetch_assoc($resultData) ) {
			$immagine = $record;
		}
        mysqli_stmt_close($stmt);

		header('Content-Type: application/json');
        echo json_encode($immagine, JSON_PRETTY_PRINT);	
	}
    public function getImmaginiReferto($id_esame) {
		$query = "
			SELECT id, contenuto, nome, id_esame
			FROM ".$this->tabellaImmagini." 
            WHERE id_esame = ?
			ORDER BY id ASC";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id_esame);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);

		$immagini = array();
		while( $record = mysqli_fetch_assoc($resultData) ) {
            array_push($immagini, $record);
		}
        mysqli_stmt_close($stmt);

		header('Content-Type: application/json');
        echo json_encode($immagini, JSON_PRETTY_PRINT);	
	}
    public function deleteImmagine($id) {
        if($id){
		    $query = "
		    	DELETE FROM ".$this->tabellaImmagini." 
		    	WHERE id = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
		    if( mysqli_stmt_affected_rows($stmt) > 0 ) {
		    	$messaggio = "Immagine cancellata con successo.";
		    	$state = 1;			
		    } else {
		    	$messaggio = "Cancellazione immagine fallita.";
		    	$state = 0;			
		    }		
            mysqli_stmt_close($stmt);
	    } 
        else {
		    $messaggio = "Richiesta non valida.";
		    $state = 0;
	    }
	    $rispostaImmagine = array(
	    	'state' => $state,
	    	'messaggio' => $messaggio
	    );

	    header('Content-Type: application/json');
	    echo json_encode($rispostaImmagine, JSON_PRETTY_PRINT);	
	}
}