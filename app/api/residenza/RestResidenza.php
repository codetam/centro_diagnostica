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
    public function getResidenza($codice_fiscale) {		
		$query = "
			SELECT codice_utente, id_luogo, via, numero
			FROM ".$this->tabellaResidenze." 
            WHERE codice_utente = ?";	
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $codice_fiscale);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);

		$residenza = array();
		while( $record = mysqli_fetch_assoc($resultData) ) {
			$residenza[] = $record;
		}
        mysqli_stmt_close($stmt);

		header('Content-Type: application/json');
        echo json_encode($residenza, JSON_PRETTY_PRINT);	
	}
}