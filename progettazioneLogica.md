## Progettazione logica DB: uni
| Nome Tabella           | Attributi             | Tipo         | Chiave  | Vincoli         | Default   |
| ---------------------- | --------------------- | ------------ | ------- | --------------- | --------- |
| utente                 | IDUtente              | SERIAL       | PK      |                 |           |
|                        | ruolo                 | ruolo        |         | NOTNULL         |           |
|                        | email                 | varchar(50)  |         | NOTNULL         |           |
|                        | password              | varchar(32)  |         | NOTNULL         |           |
|                        | cellulare             | varchar(20)  |         | NOTNULL         |           |
|                        |                       |              |         |                 |           |
| docente                | IDDocente             | integer      | PK, FK  |                 |           |
|                        | inizioRapporto        | date         |         | NOTNULL         |           |
|                        | fineRapporto          | date         |         |                 | NULL      |
|                        |                       |              |         |                 |           |
| studente               | IDCorso               | varchar(20)  | PPK, FK |                 |           |
|                        | matricola             | varchar(6)   | PPK, FK |                 |           |
|                        | dataImmatricolazione  | date         |         | NOTNULL         |           |
|                        |                       |              |         |                 |           |
| matricola              | matricola             | varchar(6)   | PK      |                 |           |
|                        | codiceFiscale         | varchar(16)  |         | NOTNULL, UNIQUE |           |
|                        | IDUtente              | SERIAL       | FK      |                 |           |
|                        |                       |              |         |                 |           |
| corso_laurea           | IDCorso               | varchar(20)  | PK      |                 |           |
|                        | nome                  | varchar(100) |         | NOTNULL         |           |
|                        | anniTotali            | tipoLaurea   |         | NOTNULL         |           |
|                        | valoreLode            | integer      |         | NOTNULL         |           |
|                        | attivo                | boolean      |         | NOTNULL         | True      |
|                        |                       |              |         |                 |           |
| insegnamento           | IDInsegnamento        | SERIAL       | PK      |                 |           |
|                        | IDDocente             | integer      | FK      |                 | NULL      |
|                        | nome                  | varchar(200) |         | NOTNULL         |           |
|                        | descrizione           | text |         |                 |           |
|                        | crediti               | integer      |         | NOTNULL         |           |
|                        | annoAttivazione       | integer      |         | NOTNULL         |           |
|                        |                       |              |         |                 |           |
| manifesto_Insegnamenti | IDInsegnamento        | integer      | PPK, FK |                 |           |
|                        | IDCorso               | varchar(20)  | PPK, FK |                 |           |
|                        | anno                  | annoCorso    |         | NOTNULL         |           |
|                        |                       |              |         |                 |           |
| propedeuticita         | insegnamento          | integer      | PPK, FK |                 |           |
|                        | insegnamentoRichiesto | integer      | PPK, FK |                 |           |
|                        |                       |              |         |                 |           |
| esame                  | IDEsame               | SERIAL       | PK      |                 |           |
|                        | IDDocente             | integer      | FK      |                 |           |
|                        | IDInsegnamento        | integer      | FK      |                 |           |
|                        | data                  |              |         | NOTNULL         |           |
|                        |                       |              |         |                 |           |
| esito                  | matricola             | varchar(6)   | PPK, FK |                 |           |
|                        | IDEsame               | SERIAL       | PPK, FK |                 |           |
|                        | voto                  | voto         |         |                 | NULL      |
|                        | stato                 | statoEsito   |         |                 | In attesa |
|                        | lode                  | boolean      |         |                 | NULL      |
|                        |                       |              |         |                 |           |
| storico_insegnamento   | IDDocente             | integer      | PPK, FK |                 |           |
|                        | IDInsegnamento        | integer      | PPK, FK |                 |           |
|                        | nome                  | varchar(200) |         | NOTNULL         |           |
|                        | crediti               | integer      |         | NOTNULL         |           |
|                        | annoInizio            | integer      |         | NOTNULL         |           |
|                        | annoFine              | integer      |         | NOTNULL         |           |
|                        |                       |              |         |                 |           |
| manifesto_passato      | IDDocente             | integer      | PPK, FK |                 |           |
|                        | IDInsegnamento        | integer      | PPK, FK |                 |           |
|                        | IDCorso               | varchar(20)  | PPK, FK |                 |           |
|                        |                       |              |         |                 |           |
| storico_studente       | matricola             | varchar(6)   | PPK, FK |                 |           |
|                        | IDCorso               | varchar(20)  | PPK, FK |                 |           |
|                        | dataImmatricolazione  | date         |         | NOTNULL         |           |
|                        | dataRimozione         | date         |         | NOTNULL         |           |
|                        |                       |              |         |                 |           |
| storico_esame          | IDStorico             | SERIAL       | PK      |                 |           |
|                        | matricola             | varchar(6)   | FK      |                 |           |
|                        | IDCorso               | varchar(20)  | FK      |                 |           |
|                        | IDInsegnamento        | SERIAL       | FK      |                 |           |
|                        | IDDocente             | SERIAL       | FK      |                 |           |
|                        | voto                  | voto         |         |                 |           |
|                        | stato                 | statoEsito   |         | NOTNULL         |           |
|                        | lode                  | boolean      |         |                 |           |
|                        | data                  | date         |         | NOTNULL         |           |
|                        |                       |              |         |                 |           |
| laurea                 | matricola             | varchar(6)   | PPK, FK |                 |           |
|                        | data                  | date         | PPK, FK |                 |           |
|                        | IDCorso               | varchar(20)  | PPK, FK |                 |           |
|                        | voto                  | votoLaurea   |         | NOTNULL         |           |
|                        | votoProva             | voto         |         | NOTNULL         |           |
|                        | lode                  | boolean      |         |                 | NULL      |
|                        |                       |              |         |                 |           |
| sessione_laurea        | data                  | date         | PPK     |                 |           |
|                        | IDCorso               | varchar(20)  | PPK, FK |                 |           |
|                        | creditiLaurea         | integer      |         | NOTNULL         |           |