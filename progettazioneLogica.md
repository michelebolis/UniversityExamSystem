## Progettazione logica DB: uni
| Nome Tabella           | Attributi             | Tipo         | Chiave  | Vincoli         |
| ---------------------- | --------------------- | ------------ | ------- | --------------- |
| utente                 | IDUtente              | SERIAL       | PK      |                 |
|                        | ruolo                 | ruolo        |         | NOTNULL         |
|                        | email                 | varchar(50)  |         | NOTNULL         |
|                        | password              | varchar(32)  |         | NOTNULL         |
|                        | cellulare             | varchar(20)  |         | NOTNULL         |
|                        |                       |              |         |                 |
| docente                | IDDocente             | integer      | PK, FK  |                 |
|                        | inizioRapporto        | date         |         | NOTNULL         |
|                        | fineRapporto          | date         |         |                 |
|                        |                       |              |         |                 |
| studente               | IDCorso               | varchar(20)  | PPK, FK |                 |
|                        | matricola             | varchar(6)   | PPK, FK |                 |
|                        | dataImmatricolazione  | date         |         | NOTNULL         |
|                        |                       |              |         |                 |
| matricola              | matricola             | varchar(6)   | PK      |                 |
|                        | codiceFiscale         | varchar(16)  |         | NOTNULL, UNIQUE |
|                        | IDUtente              | SERIAL       | FK      |                 |
|                        |                       |              |         |                 |
| Corso_Laurea           | IDCorso               | varchar(20)  | PK      |                 |
|                        | Nome                  | varchar(100) |         | NOTNULL         |
|                        | AnniTotali            | tipoLaurea   |         | NOTNULL         |
|                        | ValoreLode            | integer      |         | NOTNULL         |
|                        | Attivo                | boolean      |         | NOTNULL         |
|                        |                       |              |         |                 |
| Insegnamento           | IDInsegnamento        | SERIAL       | PK      |                 |
|                        | IDDocente             | integer      | FK      |                 |
|                        | Nome                  | varchar(200) |         | NOTNULL         |
|                        | Descrizione           | varchar(200) |         |                 |
|                        | Crediti               | integer      |         | NOTNULL         |
|                        | AnnoAttivazione       | integer      |         | NOTNULL         |
|                        |                       |              |         |                 |
| Manifesto_Insegnamenti | IDInsegnamento        | integer      | PPK, FK |                 |
|                        | IDCorso               | varchar(20)  | PPK, FK |                 |
|                        | Anno                  | annoCorso    |         | NOTNULL         |
|                        |                       |              |         |                 |
| propedeuticita         | insegnamento          | integer      | PPK, FK |                 |
|                        | insegnamentoRichiesto | integer      | PPK, FK |                 |
|                        |                       |              |         |                 |
| Esame                  | IDEsame               | SERIAL       | PK      |                 |
|                        | IDDocente             | integer      | FK      |                 |
|                        | IDInsegnamento        | integer      | FK      |                 |
|                        | Data                  |              |         | NOTNULL         |
|                        |                       |              |         |                 |
| Esito                  | matricola             | varchar(6)   | PPK, FK |                 |
|                        | IDEsame               | SERIAL       | PPK, FK |                 |
|                        | Voto                  | voto         |         |                 |
|                        | Stato                 | statoEsito   |         |                 |
|                        | Lode                  | boolean      |         |                 |
|                        |                       |              |         |                 |
| Storico_Insegnamento   | IDDocente             | integer      | PPK, FK |                 |
|                        | IDInsegnamento        | integer      | PPK, FK |                 |
|                        | Nome                  | varchar(200) |         | NOTNULL         |
|                        | Crediti               | integer      |         | NOTNULL         |
|                        | AnnoInizio            | integer      |         | NOTNULL         |
|                        | AnnoFine              | integer      |         | NOTNULL         |
|                        |                       |              |         |                 |
| manifesto_passato      | IDDocente             | integer      | PPK, FK |                 |
|                        | IDInsegnamento        | integer      | PPK, FK |                 |
|                        | IDCorso               | varchar(20)  | PPK, FK |                 |
|                        |                       |              |         |                 |
| Storico_Studente       | matricola             | varchar(6)   | PPK, FK |                 |
|                        | IDCorso               | varchar(20)  | PPK, FK |                 |
|                        | DataImmatricolazione  | date         |         | NOTNULL         |
|                        | DataRimozione         | date         |         | NOTNULL         |
|                        |                       |              |         |                 |
| Storico_Esame          | IDStorico             | SERIAL       | PK      |                 |
|                        | matricola             | varchar(6)   | FK      |                 |
|                        | IDCorso               | varchar(20)  | FK      |                 |
|                        | IDInsegnamento        | SERIAL       | FK      |                 |
|                        | IDDocente             | SERIAL       | FK      |                 |
|                        | Voto                  | voto         |         |                 |
|                        | Stato                 | statoEsito   |         | NOTNULL         |
|                        | Lode                  | boolean      |         |                 |
|                        | Data                  | date         |         | NOTNULL         |
|                        |                       |              |         |                 |
| Laurea                 | matricola             | varchar(6)   | PPK, FK |                 |
|                        | Data                  | date         | PPK, FK |                 |
|                        | IDCorso               | varchar(20)  | PPK, FK |                 |
|                        | Voto                  | votoLaurea   |         | NOTNULL         |
|                        | VotoProva             | voto         |         | NOTNULL         |
|                        | Lode                  | boolean      |         |                 |
|                        |                       |              |         |                 |
| Sessione_Laurea        | Data                  | date         | PPK     |                 |
|                        | IDCorso               | varchar(20)  | PPK, FK |                 |
|                        | CreditiLaurea         | integer      |         |                 |