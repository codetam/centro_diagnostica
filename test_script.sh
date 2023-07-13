#!/bin/sh

echo "\n1. Aggiungo un utente nel database\n"
curl -X POST -d 'codice_fiscale=TEST1234TEST1234&nome=NomeTest&cognome=CognomeTest&email=user@test.com&password=1234&telefono=3334445566&sesso=M&data_nascita=2000-01-01&citta_nascita=CittaNascita&provincia_nascita=PN&citta_residenza=CittaResidenza&provincia_residenza=PR&via_residenza=Via&numero_residenza=10' http://localhost/api/utente/create

echo "\n\n2. Verifico la presenza dell'utente\n"
curl http://localhost/api/utente/read/all/TEST1234TEST1234

echo "\n\n3. Aggiungo un operatore nel database\n"
curl -X POST -d 'nome=OperatoreNomeTest&cognome=OperatoreCognomeTest&email=operatore@test.com&password=1234' http://localhost/api/operatore/create

echo "\n\n4. Verifico che l'operatore sia stato aggiunto\n"
curl http://localhost/api/operatore/read

echo "\n\n5. Aggiungo un esame nel database\n"
curl -X POST -d 'tipologia=ecografia&terminato=0&data=2023-12-12&ora=10:00:00&codice_utente=TEST1234TEST1234&id_operatore=3' http://localhost/api/esame/create

echo "\n\n6. Visualizzo gli esami associati all'utente\n"
curl http://localhost/api/esame/read/utente/TEST1234TEST1234