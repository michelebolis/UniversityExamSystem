# **Triggers:**

---

## Struttura della documentazione

- nomeFunzioneTrigger: descrizione  
ON schema.nomeTabella
WHEN evento  
FOR ...
EXCEPTION ...

---

- **insert_media**: all'inserimento di un nuovo studente, inizializza la media di un nuovo studente di un corso di laurea a 0 nella vista materializzata  
ON uni.studente  
WHEN AFTER INSERT
FOR EACH ROW

---

- **update_media**: all'inserimento nella vista materializzata carriera_studente di un esame, che quindi è stato superato, aggiorna la vista materializzata media_studente dato il nuovo esito
ON uni.carriera_studente  
WHEN AFTER INSERT  
FOR EACH ROW

---
---

- **check_esame_trigger**: all'inserimento di una nuova sessione di esame controlla che non siano gia previsti esami nello stesso giorno per insegnamenti previsti nello stesso anno nel manifesto degli studi  
ON uni.esame
WHEN BEFORE INSERT  
FOR EACH ROW
EXCEPTION SE nella data selezionata è gia presente un esame dello stesso anno per un corso di laurea dell insegnamento  
EXCEPTION SE il docente che sta inserendo l'esame non è attualmente responsabile dell insegnamento  

---

- **check_insertesito**: all'inserimento di un nuovo esito, verifico che il nuovo stato sia Iscritto e che lo studente NON abbia già accetato un esito positivo per lo stesso insegnamento, sia del corso di laurea dell'insegnamento e che abbia superato gli esami propedeutici a tale insegnamento  
ON uni.esito
WHEN BEFORE INSERT
EXCEPTION SE stato!='Iscritto'  
EXCEPTION SE lo studente si è gia iscritto a questa sessione dell esame  
EXCEPTION SE l'insegnamento dell esame non è previsto per il corso di laurea dello studente  
EXCEPTION SE lo studente ha gia accettato un esito positivo per l'insegnamento  
EXCEPTION SE lo studente non ha superato gli esami propedeutici richiesti

---

- **check_updateesito**: alla modifica dell'esito, verifico che lo stato non sia ancora Iscritto e verifico la correttezza delle informazioni associate al nuovo stato tenendo in considerazione anche lo stato precedente  
ON uni.esito  
WHEN BEFORE UPDATE
FOR EACH ROW  
EXCEPTION SE OLD.stato!='Iscritto' E NEW.stato='Iscritto' OR 'Ritirato' OR 'Bocciato' OR 'In attesa di accerrazione' OR 'RIfiutato'  
EXCEPTION SE NEW.stato='Ritirato' E il voto e la lode non sono NULL  
EXCEPTION SE NEW.stato='Bocciato' E il voto non è minore di 18 o la lode non è false  
EXCEPTION SE NEW.stato='In attesa di accettazione' E il voto è minore di 18 o il voto è diverso da 30 e la lode è true  
EXCEPTION SE NEW.stato='Accettato' E OLD.Stato!='In attesa di accettazione'  

---

- **check_deleteesito_trigger**: all'eliminazione di un esito, verifico che questo abbia uno stato di iscritto e una data successiva a quella odierna  
ON uni.esito  
WHEN BEFORE DELETE
FOR EACH ROW  
EXCEPTION SE si sta cancellando un esito che non ha lo stato 'Iscritto'  
EXCEPTION SE l'esame si è gia tenuto

---

- **num_responsabile**: all'inserimento o modifica di un insegnamento controlla che il nuovo docente sia responsabile di al massimo 3 insegnamenti e che il precedente responsabile dopo la modifica sia ancora responsabile di almeno 1 insegnamento  
ON uni.insegnamento  
WHEN BEFORE UPDATE OR INSERT
FOR EACH ROW  
EXCEPTION SE si sta sostituendo come responsabile un docente ha solo quell'insegnamento come insegnamento
EXCEPTION SE il docente a cui si sta affidando l'insegnamento ha già 3 insegnamenti di cui è responsabile  

---

- **move_to_storico_insegnamento**: al cambiamento del docente responsabile, sposta i record associati in storico_insegnamento  
ON uni.insegnamento  
WHEN BEFORE UPDATE
FOR EACH ROW  
EXCEPTION SE il nuovo docente è lo stesso docente precedente
Nota: presuppongo che si possa modificare solo l'id del docente responsabile

---

- **move_to_storico_studente**: all'eliminazione di un record in studente, sposta i record associati in storico_studente e storico_esame  
ON uni.studente
WHEN AFTER DELETE  
FOR EACH ROW

---

- **check_registrazione_laurea**: all'inserimento di un iscrizione ad una sessione di laurea in laurea, controlla SE lo studente ha superato tutti gli insegnamenti del corso e SE li ha passati, il voto di laurea, l'incremento e la lode devono essere NULL  
ON uni.laurea  
WHEN BEFORE INSERT  
FOR EACH ROW  
EXCEPTION SE lo studente non ha passato tutti gli insegnamenti del corso

---

- **move_to_storico**: alla modifica del voto di laurea di uno studente, elimino tale studente in quanto è diventato un ex-studente  
ON uni.laurea  
WHEN BEFORE UPDATE
FOR EACH ROW  

---

- **hash**: all'inserimento o alla modifica di un utente, applico la funzione hash md5 alla password.
ON uni.utente  
BEFORE INSERT OR UPDATE  
FOR EACH ROW

---
