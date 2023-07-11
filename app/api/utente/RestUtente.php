<?php
class RestUtente{
    private $conn = false;
    private $tabellaUtenti = "Utenti";
    private $tabellaLuoghi = "Luoghi";
    private $tabellaResidenze = "Residenze";
    public function __construct($connection)
    {
        if(!$connection){
            die("Errore nella connessione al database");
        }else{
            $this->conn = $connection;
        }
    }
    private function searchLuogo($citta, $provincia){
        $checkSql = "SELECT id FROM " . $this->tabellaLuoghi . " WHERE citta = ? AND provincia = ?";
        $checkStmt = mysqli_stmt_init($this->conn);
        if (!mysqli_stmt_prepare($checkStmt, $checkSql)) {
            return 0;
        }
        mysqli_stmt_bind_param($checkStmt, "ss", $citta, $provincia);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_bind_result($checkStmt, $id);
        mysqli_stmt_fetch($checkStmt);
        mysqli_stmt_close($checkStmt);

        if ($id !== null) {
            return $id;
        }
        else{
            return 0;
        }
    }
    private function createLuogo($citta, $provincia){
        $sql = "INSERT INTO " .$this->tabellaLuoghi. " 
        (citta, provincia) VALUES(?,?);";
        $stmt = mysqli_stmt_init($this->conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            return false;
        }

        mysqli_stmt_bind_param($stmt, "ss", 
                                    $citta,
                                    $provincia);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            mysqli_stmt_close($stmt);
            return true;
        }
        else {
            mysqli_stmt_close($stmt);
            return false;
        }
    }
    private function createResidenza($codice_utente, $id_luogo, $via, $numero){
        $sql = "INSERT INTO " .$this->tabellaResidenze. " 
        (codice_utente, id_luogo, via, numero) VALUES(?,?,?,?);";
        $stmt = mysqli_stmt_init($this->conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            return false;
        }

        mysqli_stmt_bind_param($stmt, "sisi", 
                                    $codice_utente,
                                    $id_luogo,
                                    $via,
                                    $numero);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            return true;
        }
        else {
            return false;
        }
    }
    private function createUtenteHelper($codice_fiscale, $nome, $cognome, $email, $password, $telefono, $data_nascita, $id_luogo_nascita, $sesso){
        $sql = "INSERT INTO " .$this->tabellaUtenti. " 
        (codice_fiscale, nome, cognome, email, password, telefono, luogo_nascita, data_nascita, sesso) VALUES(?,?,?,?,?,?,?,?,?);";
        $stmt = mysqli_stmt_init($this->conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            return false;
        }
        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "ssssssiss", 
                                    $codice_fiscale,
                                    $nome,
                                    $cognome,
                                    $email,
                                    $hashedPwd,
                                    $telefono,
                                    $id_luogo_nascita,
                                    $data_nascita,
                                    $sesso);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            mysqli_stmt_close($stmt);
            return false;
        }
    }
    private function creazioneFallita($messaggio){
        $this->conn->rollback();
        $risposta = array(
            'state' => 0,
            'message' => $messaggio
        );
        header('Content-Type: application/json');
        echo json_encode($risposta, JSON_PRETTY_PRINT);
        return;
    }
    public function createUtente($data){
        $codice_fiscale = $data["codice_fiscale"];
        $nome = $data["nome"];
        $cognome = $data["cognome"];
        $email = $data["email"];
        $password = $data["password"];
        $telefono = $data["telefono"];
        $sesso = $data["sesso"];
        $data_nascita = $data["data_nascita"];
        $citta_nascita = $data["citta_nascita"];
        $provincia_nascita = $data["provincia_nascita"];
        $citta_residenza = $data["citta_residenza"];
        $provincia_residenza = $data["provincia_residenza"];
        $via_residenza = $data["via_residenza"];
        $numero_residenza = $data["numero_residenza"];

        
        $this->conn->begin_transaction();
        try{
            // Controllo sul luogo di nascita
            $id_luogo_nascita = $this->searchLuogo($citta_nascita, $provincia_nascita);
            if($id_luogo_nascita == 0){                                                 // Il luogo non esiste
                if($this->createLuogo($citta_nascita, $provincia_nascita) == false){    // La creazione del luogo fallisce
                    return $this->creazioneFallita("Errore nella creazione del luogo");
                }                                                              
                $id_luogo_nascita = $this->searchLuogo($citta_nascita, $provincia_nascita); 
                if($id_luogo_nascita == 0){                                             // L'identificazione del luogo fallisce
                    return $this->creazioneFallita("Errore nella creazione del luogo");
                }
            }
            
            // Viene aggiunto un nuovo Utente
            if($this->createUtenteHelper($codice_fiscale, $nome, $cognome, $email, $password, $telefono, $data_nascita, $id_luogo_nascita, $sesso) == false){
                return $this->creazioneFallita("Creazione utente fallita");
            }

            // Controlla che il luogo di residenza già esista
            $id_luogo_residenza = $this->searchLuogo($citta_residenza, $provincia_residenza);
            if($id_luogo_residenza == 0){                                                // Il luogo non esiste
                if($this->createLuogo($citta_residenza, $provincia_residenza) == false){     // La creazione del luogo fallisce
                    return $this->creazioneFallita("Errore nella creazione del luogo");
                }                                                                   
                $id_luogo_residenza = $this->searchLuogo($citta_residenza, $provincia_residenza); // Luogo creato con successo
                if($id_luogo_residenza == 0){
                    return $this->creazioneFallita("Errore nella creazione del luogo");
                }
            }
            if($this->createResidenza($codice_fiscale, $id_luogo_residenza, $via_residenza, $numero_residenza) == false){
                return $this->creazioneFallita("Residenza associata ad un altro utente");
            }
            $this->conn->commit();
        }
        catch(Exception $e){
            // C'è stato un errore, rollback
            return $this->creazioneFallita("Errore nella registrazione, probabile che la residenza sia associata ad un altro utente, oppure che il codice fiscale sia errato.");
        }
        // Tutto è andato a buon fine
        $risposta = array(
            'state' => 1,
            'message' => "Utente creato con successo"
        );
        
		header('Content-Type: application/json');
		echo json_encode($risposta, JSON_PRETTY_PRINT);
    }
    public function getUtente($codice_fiscale) {		
		$query = "
			SELECT codice_fiscale, nome, cognome, email, telefono, luogo_nascita, data_nascita, sesso
			FROM ".$this->tabellaUtenti." 
            WHERE codice_fiscale = ?";	
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $codice_fiscale);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);

		$utente = array();
		while( $record = mysqli_fetch_assoc($resultData) ) {
			$utente[] = $record;
		}
        mysqli_stmt_close($stmt);

		header('Content-Type: application/json');
        echo json_encode($utente, JSON_PRETTY_PRINT);	
	}
    
    public function getUtenteAllInfo($codice_fiscale) {		
		$query = "
        SELECT 
            Utenti.codice_fiscale, 
            Utenti.nome, 
            Utenti.cognome, 
            Utenti.email, 
            Utenti.telefono, 
            Utenti.data_nascita, 
            LuoghiNascita.citta AS citta_nascita, 
            LuoghiNascita.provincia AS provincia_nascita, 
            LuoghiResidenza.citta AS citta_residenza, 
            LuoghiResidenza.provincia AS provincia_residenza,
            Residenze.via,
            Residenze.numero 
        FROM Utenti 
            JOIN Luoghi AS LuoghiNascita ON Utenti.luogo_nascita = LuoghiNascita.id 
            JOIN Residenze ON Utenti.codice_fiscale = Residenze.codice_utente 
            JOIN Luoghi AS LuoghiResidenza ON Residenze.id_luogo = LuoghiResidenza.id 
        WHERE Utenti.codice_fiscale = ?";

        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $codice_fiscale);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);

		$utente;

		while( $record = mysqli_fetch_assoc($resultData) ) {
			$utente = $record;
		}
        mysqli_stmt_close($stmt);

		header('Content-Type: application/json');
        echo json_encode($utente, JSON_PRETTY_PRINT);	
	}
    public function deleteUtente($codice_fiscale) {
        if($codice_fiscale){
		    $query = "
		    	DELETE FROM ".$this->tabellaUtente." 
		    	WHERE codice_fiscale = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $codice_fiscale);
            mysqli_stmt_execute($stmt);
		    if( mysqli_stmt_affected_rows($stmt) > 0 ) {
		    	$messaggio = "Utente cancellato con successo.";
		    	$state = 1;			
		    } else {
		    	$messaggio = "Cancellazione utente fallita.";
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
    function updateUtente($data){
        $codice_fiscale = $data["codice_fiscale"];
        $nome = $data["nome"];
        $cognome = $data["cognome"];
        $email = $data["email"];
        $password = $data["password"];
        $telefono = $data["telefono"];
        $sesso = $data["sesso"];
        $data_nascita = $data["data_nascita"];

        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
        $query="
            UPDATE ".$this->tabellaUtenti." 
            SET nome=?, cognome=?, email=?,
            password=?, telefono=?, sesso=?,
            data_nascita=?
            WHERE codice_fiscale = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "ssssssss",
                                $nome,
                                $cognome, 
                                $email,
                                $hashedPwd,
                                $telefono, 
                                $sesso,
                                $data_nascita,
                                $codice_fiscale);
        mysqli_stmt_execute($stmt);

        if( mysqli_stmt_affected_rows($stmt) > 0 ) {
            $messaggio = "Utente aggiornato con successo.";
            $state = 1;			
        } else {
            $this->creazioneFallita("Errore nell'aggiornamento.");		
        }
        $risposta = array(
	    	'state' => $state,
	    	'messaggio' => $messaggio
	    );
        mysqli_stmt_close($stmt);
	    
        header('Content-Type: application/json');
	    echo json_encode($risposta, JSON_PRETTY_PRINT);	
	}
}