<?php
class RestStatistiche{
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
    // Ritorna il numero di esami per ogni tipologia
    public function getNumPerTipologia() {		
		$query = "
			SELECT tipologia,
                SUM(CASE WHEN terminato=1 THEN 1 ELSE 0 END) AS num_terminati,
                SUM(CASE WHEN terminato=0 THEN 1 ELSE 0 END) AS num_non_terminati
            FROM Esami
            GROUP BY tipologia";	
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);

		$numEsami = array();
		while( $record = mysqli_fetch_assoc($resultData) ) {
			array_push($numEsami, $record);
		}
        mysqli_stmt_close($stmt);

		header('Content-Type: application/json');
        echo json_encode($numEsami, JSON_PRETTY_PRINT);	
	}
    // Ritorna il numero di esami terminati e non terminati per un utente
    public function getNumEsamiTerminatiUtente($codice_utente) {		
		$query = "
			SELECT 
                SUM(CASE WHEN terminato=1 THEN 1 ELSE 0 END) AS num_terminati,
                SUM(CASE WHEN terminato=0 THEN 1 ELSE 0 END) AS num_non_terminati
            FROM Esami
            WHERE codice_utente = ?";	
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $codice_utente);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);

		$num_esami;
		while( $record = mysqli_fetch_assoc($resultData) ) {
			$num_esami = $record;
		}
        mysqli_stmt_close($stmt);

		header('Content-Type: application/json');
        echo json_encode($num_esami, JSON_PRETTY_PRINT);	
	}
    public function getNumUtenti() {		
		$query = "
			SELECT count(*) AS numero
            FROM Utenti";	
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);

		$num_utenti;
		while( $record = mysqli_fetch_assoc($resultData) ) {
			$num_utenti = $record;
		}
        mysqli_stmt_close($stmt);

		header('Content-Type: application/json');
        echo json_encode($num_utenti, JSON_PRETTY_PRINT);	
	}
    public function getNumUtentiSesso(){
        $query= "
        SELECT sesso,
            COUNT(DISTINCT Utenti.codice_fiscale) AS num_utenti,
            COUNT(CASE WHEN Esami.terminato = 1 AND Esami.terminato IS NOT NULL THEN 1 ELSE NULL END) AS num_esami_terminati,
            COUNT(CASE WHEN Esami.terminato = 0 AND Esami.terminato IS NOT NULL THEN 1 ELSE NULL END) AS num_esami_non_terminati
        FROM Utenti
        LEFT JOIN Esami ON Utenti.codice_fiscale = Esami.codice_utente
        GROUP BY sesso
        ORDER BY sesso ASC";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);

        $num_utenti = array();
		while( $record = mysqli_fetch_assoc($resultData) ) {
			array_push($num_utenti, $record);
		}
        mysqli_stmt_close($stmt);

		header('Content-Type: application/json');
        echo json_encode($num_utenti, JSON_PRETTY_PRINT);
    }
}