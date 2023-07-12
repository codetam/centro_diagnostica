<?php
/* Se email o password sono vuote ritorna false */
function emptyInputLogin($email, $password)
{
    if (empty($email) || empty($password)) {
        return true;
    } else {
        return false;
    }
}

/* Se l'email esiste ritorna la row associata, altrimenti ritorna false */
function utenteEmailExists($conn, $email)
{
    $sql = "SELECT * FROM Utenti WHERE email = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return false;
    }
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        mysqli_stmt_close($stmt);
        return $row;
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}

/* Ritorna true solo se username e password matchano */
function loginUser($conn, $email, $password)
{
    if(emptyInputLogin($email, $password)){
        return false;
    }
    $row = utenteEmailExists($conn, $email);
    if ($row === false) {
        return false;
    }
    $pwdHashed = $row["password"];
    $checkedPwd = password_verify($password, $pwdHashed);
    if ($checkedPwd === false) {
        return false;
    } else {
        return true;
    }
}

function operatoreEmailExists($conn, $email)
{
    $sql = "SELECT * FROM Operatori WHERE email = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return false;
    }
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        mysqli_stmt_close($stmt);
        return $row;
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}

/* Ritorna true solo se username e password matchano */
function loginOperatore($conn, $email, $password)
{
    if(emptyInputLogin($email, $password)){
        return false;
    }
    $row = operatoreEmailExists($conn, $email);
    if ($row === false) {
        return false;
    }
    $pwdHashed = $row["password"];
    $checkedPwd = password_verify($password, $pwdHashed);
    if ($checkedPwd === false) {
        return false;
    } else {
        return true;
    }
}

/* Crea un nuovo utente e lo inserisce nel database */
function createUser($nome, $cognome, $email, $password, 
                    $telefono, $codice_fiscale, $sesso, $data_nascita, 
                    $citta_nascita, $provincia_nascita, $citta_residenza, 
                    $provincia_residenza, $via_residenza, $numero_residenza)
{
    // API endpoint
    $url = 'http://localhost/api/utente/create';

    // Dati da inviare con la richiesta POST
    $data = array(
        'nome' => $nome,
        'cognome' => $cognome,
        'email' => $email,
        'password' => $password,
        'telefono' => $telefono,
        'codice_fiscale' => $codice_fiscale,
        'sesso' => $sesso,
        'data_nascita' => $data_nascita,
        'citta_nascita' => $citta_nascita,
        'provincia_nascita' => $provincia_nascita,
        'citta_residenza' => $citta_residenza,
        'provincia_residenza' => $provincia_residenza,
        'via_residenza' => $via_residenza,
        'numero_residenza' => $numero_residenza    
    );
    // Conversione dei dati in www-form-urlencoded
    $formData = http_build_query($data);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $formData);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);

    if ($response === false) {
        return false;
    }
    curl_close($curl);
    return $response;
}

/* Se un campo è vuoto ritorna false */
function emptyInputSignup($nome, $cognome, $email, $password, 
                        $telefono, $codice_fiscale, $sesso, $data_nascita, 
                        $citta_nascita, $provincia_nascita, $citta_residenza, 
                        $provincia_residenza, $via_residenza, $numero_residenza )
{
    if(empty($nome) || empty($cognome) || empty($email) || empty($password) || empty($telefono) || 
                    empty($codice_fiscale) || empty($sesso) || empty($data_nascita) || empty($citta_nascita) || 
                    empty($citta_residenza) || empty($provincia_nascita) || empty($provincia_residenza) || 
                    empty($via_residenza) || empty($numero_residenza)) {
        return true;
    } else {
        return false;
    }
}

function prenotaEsame($tipologia, $data, $ora, $codice_utente, $id_operatore){
    // API endpoint
    $url = 'http://localhost/api/esame/create';
    $dati_da_inviare = array(
        'terminato' => 0,
        'tipologia' => $tipologia,
        'data' => $data,
        'ora' => $ora,
        'codice_utente' => $codice_utente,
        'id_operatore' => $id_operatore   
    );
    // Conversione dei dati in www-form-urlencoded
    $formData = http_build_query($dati_da_inviare);
    $formData = str_replace('%3A', ':', $formData);
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $formData);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    if ($response === false) {
        return false;
    }
    curl_close($curl);
    return $response;
}

function getAvailableOrarioOptions($data_scelta){
    $json_esami = file_get_contents('http://localhost/api/esame/read/data/' . $data_scelta);
    $obj_esami = json_decode($json_esami);
    $ore_occupate = array();
    foreach ($obj_esami as $esame){
        array_push($ore_occupate, $esame->ora);
    }
    $orario_options = '<option value="0" disabled>Orario</option>';
    for ($i = 9; $i < 18; $i++) {
        $time1 = $i . ':00:00';
        $time2 = $i . ':30:00';
        if($i == 9){
            $time1 = '09:00:00';
            $time2 = '09:30:00';
        }
        if (!in_array($time1, $ore_occupate)) {
            $orario_options .= '<option value="' . $time1 . '">' . $i . ':00 - ' . $i . ':30</option>';
        }
        if (!in_array($time2, $ore_occupate)) {
            $orario_options .= '<option value="' . $time2 . '">' . $i . ':30 - ' . ($i + 1) . ':00</option>';
        }
    }
    echo $orario_options;
}

function getPrenotazioni($data_scelta, $id_operatore){
    $json_esami = file_get_contents('http://localhost/api/esame/read/data/' . $data_scelta);
    $obj_esami = json_decode($json_esami);
    $stringa_completa = '';
    foreach ($obj_esami as $esame){
        if($esame->id_operatore == $id_operatore){
            $orario = substr($esame->ora, 0, 5);
            $completato = "No";
            if($esame->terminato == 1){
                $completato = "Si";
            }
            $stringa_completa .= '<div class="row">';
            $stringa_completa .= '<div class="col-sm-2"><p><b>ID</b></p></div><div class="col-sm-10"><p>' . $esame->id . '</p></div>';
            $stringa_completa .= '<div class="col-sm-2"><p><b>Tipologia</b></p></div><div class="col-sm-10"><p>' . $esame->tipologia . '</p></div>';
            $stringa_completa .= '<div class="col-sm-2"><p><b>Ora</b></p></div><div class="col-sm-10"><p>' . $orario . '</p></div>';
            $stringa_completa .= '<div class="col-sm-2"><p><b>Codice fiscale</b></p></div><div class="col-sm-10"><p>' . $esame->codice_utente . '</p></div>';
            $stringa_completa .= '<div class="col-sm-2"><p><b>Completato</b></p></div><div class="col-sm-10"><p>' . $completato . '</p></div>';
            $stringa_completa .= '<div class="col-sm-2"><form action="esame.php" method="get">
                                        <input type="hidden" name="id_esame" value="' . $esame->id . '">
                                        <button type="submit" class="btn btn-primary">Gestisci</button>
                                    </form></div>';  
            $stringa_completa .= '<div class="col-sm-2"><form action="src/check_delete_esame.php" method="get">
                                    <input type="hidden" name="id_esame" value="' . $esame->id . '">
                                    <button type="submit" class="btn btn-danger")>Elimina</button>
                                </form></div><div class="col-8"></div><br><br>';                   
            $stringa_completa .= '</div><hr>';
        }
    }

    echo $stringa_completa;
}

function scriviReferto($id_esame, $testo, $request){
    $json_esame = file_get_contents('http://localhost/api/esame/read/' . $id_esame);
    $obj_esame = json_decode($json_esame);
    
    if($request == "crea"){
        $url = 'http://localhost/api/referto/create';
    }
    elseif($request == "modifica"){
        $url = 'http://localhost/api/referto/update';
    }
    $dati_da_inviare = array(
        'id_esame' => $id_esame,
        'testo' => $testo
    );

    // Conversione dei dati in www-form-urlencoded
    $formData = http_build_query($dati_da_inviare);
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $formData);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    
    if ($response === false) {
        return false;
    }
    curl_close($curl);
    return $response;
}

function emptyInputReferto($id_esame, $testo)
{
    if (empty($id_esame) || empty($testo)) {
        return true;
    } else {
        return false;
    }
}