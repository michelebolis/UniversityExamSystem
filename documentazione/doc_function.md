## **Functions / Procedures:**
---
### Struttura della documentazione
- schema.nomeFunzione: descrizione  
RETURNS ROW (SE una sola riga) / ROWS (piu righe) : campiRestituiti  
Parametri: 
    - Elenco dei parametri  
    Eventuali note
--- 
---
- **uni.insert_corso_laurea**: permette di inserire un nuovo corso di laurea   
RETURNS NOTHING  
Parametri:  
    - IDCorso varchar(20)
    - nome varchar(100) 
    - anniTotali uni.tipoLaurea
    - valoreLode integer  
EXCEPTION SE il valore della lode < 30  
Nota: il corso di laurea è di default attivo
---
- **uni.insert_insegnamento**: permette di inserire un nuovo insegnamento    
RETURNS NOTHING  
Parametri:  
    - IDDocente integer
    - nome varchar(200)
    - descrizione text
    - crediti integer
    - annoAttivazione integer  
Nota: IDDocente puo essere eventualmente NULL
---
- **uni.insert_utente**: permette di inserire un nuovo utente  
RETURNS NOTHING  
Parametri:  
    - ruolo uni.ruolo 
    - nome varchar(50)
    - cognome varchar(50)
    - new_email varchar(100)
    - password varchar(32)  
    - cellulare varchar(20)   
---
- **uni.insert_docente**: permette di inserire un nuovo docente  
RETURNS NOTHING  
Parametri:  
    - nome varchar(50), 
    - cognome varchar(50), 
    - new_email varchar(100), 
    - password varchar(32), 
    - cellulare varchar(20), 
    - inizioRapporto date, 
    - insegnamentoToUpdate integer
---
- **uni.new_matricola**: restituisce la matricola da assegnare a uno nuovo studente   
RETURNS char(6)   
Nota: LIMITE 999999 studenti
---
- **uni.modifica_utente**: data un utente, è possibile modificarne la mail, la password e il cellulare  
RETURNS NOTHING  
Parametri:   
    - the_IDUtente integer,   
    - new_email varchar(100),   
    - new_password varchar(32),   
    - new_cellulare varchar(20)  
Nota: SE non si vuole modficare uno o piu di questi campi, gli argomenti devono essere NULL
---
- **uni.cambio_corso_laurea**: procedura usata internamente per iscrivere uno studente ad un nuovo corso di laurea, recuperandone le informazioni in quanto già iscritto in precedenza  
RETURNS NOTHING  
Parametri:  
    - the_matricola char(6),  
    - the_IDCorso varchar(20),  
    - dataImmatricolazione date,  
    - new_email varchar(100),  
    - password varchar(32),  
    - cellulare varchar(20)  
---
- **uni.insert_studente**: permette di inserire un nuovo studente date le informazioni biografiche, quelle di accesso e il corso di laurea a cui si sta iscrivendo  
RETURNS NOTHING  
Parametri:  
    - nome varchar(50),   
    - cognome varchar(50),   
    - new_email varchar(100),   
    - password varchar(32),   
    - cellulare varchar(20),   
    - the_codiceFiscale varchar(16),   
    - the_IDCorso varchar(20),   
    - dataImmatricolazione date  
EXCEPTION SE il corso di laurea a cui si sta iscrivendo non ha ancora nessun insegnamento associato
---
- **uni.insert_segreteria**: permette di inserire un nuovo utente della segreteria date le informazioni biografiche e quelle di accesso  
RETURNS NOTHING  
Parametri:
    - nome varchar(50), 
    - cognome varchar(50), 
    - new_email varchar(100), 
    - password varchar(32), 
    - cellulare varchar(20)
---
- **uni.insert_esame**: permette di inserire per un docente che è responsabile di un insegnamento un esame di quest'ultimo in una data  
RETURNS NOTHING  
Parametri:  
    - the_IDDocente integer, 
    - the_IDInsegnamento integer, 
    - the_data date, 
    - orario time  
EXCEPTION SE il docente non è responsabile dell'insegnamento  
EXCEPTION SE nella data scelta è già presente un esame previsto per lo stesso anno dell'insegnamento nel manifesto degli studi di un corso di laurea   
---
- **uni.insert_propedeuticita**: permette di inserire, per un insegnamento, un altro insegnamento di cui è richiesto il superamento  
RETURNS NOTHING  
Parametri:
    - insegnamento integer,   
    - insegnamentoRichiesto integer  
EXCEPTION SE insegnamento e insegnamentoRichiesto sono uguali
---
- **uni.iscrizione_esame**: data una matricola e un identificativo di un esame, permette di iscriversi all'esame
RETURNS NOTHING  
Parametri:
    - matricola char(6)
    - IDEsame integer
---
- **uni.annulla_iscrizione_esame**: data una matricola e un identificativo di un esame, permette di annullare l'iscrizione a tale esame  
RETURNS NOTHING  
Parametri:  
    - the_matricola char(6), 
    - the_IDEsame integer  
Nota: l'annullamento dell'iscrizione è possibile solo se l esame non si è gia tenuto
---
- **uni.insert_manifesto**: permette di inserire un insegnamento nel manifesto di studi di un corso di laurea in un anno  
RETURNS NOTHING  
Parametri: 
    - IDInsegnamento integer,  
    - the_IDCorso varchar(20),  
    - anno uni.annoCorso  
EXCEPTION: SE l'anno in cui si prevede l'insegnamento è il 3° ma il corso di laurea è di 2 anni
---
- **uni.insert_sessione_laurea**: permette di inserire una sessione di laurea di un corso di laurea in una data
RETURNS NOTHING  
Parametri: 
    - data date, 
    - IDCorso varchar(20)
---
- **uni.iscrizione_laurea**: permette ad una matricola di iscriversi ad una sessione di laurea in una data   
RETURNS NOTHING  
Parametri:  
    - matricola char(6),   
    - data date,   
    - IDCorso varchar(20)  
---
- **uni.registrazione_esito_esame**: data una matricola che si è iscritta ad un esame, permette di registrarne il voto e l'eventuale lode  
RETURNS NOTHING  
Parametri:  
    - the_matricola char(6),   
    - the_IDEsame integer,   
    - the_voto uni.voto,   
    - the_lode boolean  
EXCEPTION SE l'esito era già stato registrato  
EXCEPTION SE la lode è assegnata ad un voto inferiore di 30  
NOTA: se la coppia matricola, esame non è presente: non viene registrato nessun esito  
---
- **uni.accetta_esito**: data una matricola che si è iscritta ad un esame, permette di accetare o rifiutare l'esito  
RETURNS NOTHING  
Parametri:  
    - the_matricola char(6),   
    - the_IDEsame integer,   
    - accettato boolean  
NOTA: se la coppia matricola, esame non è presente: non viene accettato nessun esito
---
- **uni.delete_studente**: permette di cancellare uno studente data la sua matricola iscritta ed il corso di laurea a cui è iscritto  
RETURNS NOTHING  
Parametri:
    - the_matricola char(6), 
    - the_IDCorso varchar(20)
---
- **uni.calcola_voto_laurea**: permette di calcolare il voto finale della laurea data la matricola dello studente iscritta ad un corso di laurea e l'eventuale incrememnto   
RETURNS decimal
Parametri:
    - the_matricola char(6), 
    - the_IDCorso varchar(20), 
    - incremento integer  
---
- **uni.registrazion_esito_laurea**: permette di registrarre l'esito della sessione di laurea, in una specifica data, di uno studente di un corso di laurea a cui è stato assegnato un eventuale incremento e l'eventuale lode
RETURNS NOTHING  
Parametri:
    - the_matricola char(6), 
    - the_IDCorso varchar(20), 
    - the_data date, 
    - incremento integer, 
    - the_lode boolean
---
- **uni.get_id_ruolo**: data la mail e password di un utente, restituisce l'id dell'utente e il suo ruolo  
RETURNS ROW: idutente, ruolo
Parametri: 
    - the_email varchar(100), 
    - the_password varchar(32)  
Nota: SE email e password non corrispondono a nessun utente, restituisce una riga vuota
---
- **uni.get_utente_bio**: permette di ottenere tutti i dati dell'utente dato il suo identificativo  
RETRURNS ROW: 
Parametri: idutente, ruolo, nome, cognome, email, password, cellulare
    - the_idutente integer  
NOTA: la password è cifrata quindi non comprensibile
--- 
- **uni.get_id_studente**: permette di ottenere l'idutente data la matricola dello studente  
RETURNS integer
Parametri: 
    - the_matricola char(6)  
---
- **uni.get_next_exam_bydoc**: permette di ottenere le informazioni dei prossimi esami di un docente   
RETURNS ROW: idesame, idinsegnamento, data, ora
Parametri: 
    - the_IDDocente integer  
---
- **uni.get_past_esame**: restituisce gli esami passati dato un docente  
RETURNS ROW: idesame, idinsegnamento, iddocente, data  
Parametri:  
    - the_IDDocente integer
---
- **uni.get_studente_bio**: restituisce le informazioni biografiche di uno studente data la sua matricola e il corso di laurea a cui è iscritto  
RETURNS ROW: matricola, nome, cognome, email, cellulare, idcorso, dataImmatricolazione  
Parametri: 
    - the_matricola varchar(6), 
    - the_corso varchar(20)
---
- **uni.get_studente_bio**: restituisce le informazioni biografiche di uno studente dato il suo identificativo come utente  
RETURNS ROW: matricola, nome, cognome, email, cellulare, idcorso, dataImmatricolazione  
Parametri: 
    - the_idutente integer 
---
- **uni.get_exstudente_bio**: permette di recuperare la matricola e i corsi di laurea a cui è stato iscritto un ex studente dato il suo identificativo come utente  
RETURNS ROW: matricola, idcorso
Parametri:  
    - the_idutente integer
---
- **uni.get_laurea**: restituisce eventuali lauree data una matricola  
RETURNS ROW: matricola, data, idcorso, voto, incremento, lode  
Parametri:   
    - the_matricola varchar(6) 
---
- **uni.get_insegnamento**: restituisce le informazioni di un insegnamento  
RETURNS ROW: idinsegnamento, iddocente, nome, descrizione, crediti, annoattivazione  
Parametri: 
    - the_IDInsegnamento integer
---
- **uni.get_manifesto**: restituisce gli insegnamenti presenti nel manifesto degli studi di un corso di laurea  
RETURNS ROW: idcorso, idinsegnamento, anno  
Parametri:
    - the_IDCorso varchar(20)
--- 
- **uni.get_esame**: restituisce le informazioni di un esame  
RETURNS ROW: idesame, idinsegnamento, iddocente, data, ora  
Parametri: 
    - the_IDEsame integer   
--- 
- **uni.get_corso**: restituisce le informazioni di un corso di laurea  
RETURNS ROW: idcorso, nome, annitotali, valorelode, attivo  
Parametri: 
    - the_IDCorso varchar(20)  
---
- **uni.get_all_insegnamento**: restituisce le informazioni di tutti gli insegnamenti  
RETURNS ROW: idinsegnamento, iddocente, nome, descrizione, crediti, annoattivazione  
Parametri: 
    - Nessuno   
---
- **uni.get_all_insegnamento_mancante**: restituisce gli identificativi degli insegnamenti mancanti ad una matricola  
RETURNS ROW: idinsegnamento  
Parametri:  
    - the_matricola char(6)
---
- **uni.get_all_corso**: restituisce le informazioni di tutti i corsi di laurea  
RETURNS ROW: idcorso, nome, annitotali, valorelode, attivo  
Parametri:
    - Nessuno
---
- **uni.get_all_docente**: restituisce le informazioni di tutti i docenti  
RETURNS ROW: iddocente, iniziorapporto, finerapporto  
Parametri:
    - Nessuno
---
- **uni.get_all_studente**: restituisce le informazioni di tutti gli studenti iscritti  
RETURNS ROW: matricola, idcorso, dataimmatricolazione  
Parametri:
    - Nessuno
---
- **uni.get_all_studente_bycorso**: restituisce le informazioni di tutti gli studenti iscritti ad un corso di laurea 
RETURNS ROW: matricola, idcorso, dataimmatricolazione  
Parametri:
    - corso varchar(20) 
---
- **uni.get_all_sessione**: restituisce le informazioni su tutte le sessioni di laurea  
RETURNS ROW: idcorso, data  
Parametri:
    - Nessuno
---
- **uni.get_all_sessione_bycorso**: restituisce le informazioni su tutte le sessioni di laurea di un corso di laurea specificato    
RETURNS ROW: idcorso, data  
Parametri:
    - the_corso varchar(20)
---
- **uni.get_all_iscrizione**: restituisce le informazioni degli esami a cui una matricola è iscritta e per cui non ha ancora ricevuto un'esito  
RETURNS ROW: idesame, idinsegnamento, iddocente, data, ora  
Parametri:
    - the_matricola char(6)
---
- **uni.get_all_nextiscrizione**: restituisce le informazioni degli esami futuri a cui una matricola è iscritta  
RETURNS ROW: idesame, idinsegnamento, iddocente, data  
Parametri: 
    - the_matricola char(6)
---
- **uni.get_all_esito_attesa_acc**: restituisce gli esiti in attesa di accettazione per una matricola  
RETURNS ROW: matricola, idesame, voto, stato, lode
Parametri:
    - the_matricola char(6)
---
- **uni.get_sessione_esame**: restituisce gli esami futuri previsti per un insegnamento  
RETURNS ROW: idesame, idinsegnamento, iddocente, data, ora  
Parametri:  
    - the_IDInsegnamento integer 
---
- **uni.get_insegnamenti**: restituisce gli insegnamenti di cui è responsabile un docente  
RETURNS ROW: idinsegnamento, iddocente, nome, descrizione, crediti, annoattivazione  
Parametri:
    - the_IDDocente integer
---
- **uni.get_iscritti_esame**: restituisce le matricole iscritte ad un esame senza ancora un esito
RETURNS ROW: matricola
Parametri:
    - the_IDEsame integer
--- 
- **uni.get_iscritti_laurea**: restituisce le matricola iscritte ad una sessione di laurea senza ancora il voto finale registrato  
RETURNS ROW: matricola  
Parametri:
    - the_IDCorso varchar(20)
    - the_data date
---
- **uni.get_past_corso**: restiuisce i corsi di laurea a cui è stato iscritto uno studente, data la sua matricola  
RETURNS ROW: matricola, idcorso, dataimmatricolazione, datarimozione   
Parametri:
    - the_matricola varchar(20)
---
- **uni.get_studente_stats**: restituisce le statistiche di uno studente, data la sua matricola e un corso di laurea  
RETURNS ROW: matricola, idcorso, media, crediti  
Parametri:
    - the_matricola char(6)
    - corso varchar(20)
---
- **uni.get_num_esami_passati**: restituisce il numero di esami superati da uno studente mentre è/era iscritto ad un corso di laurea  
RETURNS ROW: integer  
Parametri:
    - the_matricola char(6) 
    - corso varchar(20)
---
- **uni.get_carriera_studente**: restituisce gli esami superati da uno studente iscritto, data la sua matricola  
RETURNS ROW: matricola, idcorso, idinsegnamento, nome, iddocente, data, voto, lode  
Parametri:
    - the_matricola char(6)
---
- **uni.get_carriera_studente**: restituisce gli esami superati da uno studente, data la sua matricola e un corso id laurea  
RETURNS ROW: matricola, idcorso, idinsegnamento, nome, iddocente, data, voto, lode  
Parametri:
    - the_matricola char(6)
    - the_IDCorso varchar(20)
---
- **uni.get_carriera_completa_studente**: restituisce tutti gli esami a cui si è iscritto uno studente iscritto, data la sua matricola  
RETURNS ROW: matricola, idcorso, idinsegnamento, nome, iddocente, data, voto, lode  
Parametri:
    - the_matricola char(6)
---
- **uni.get_carriera_passata_studente**: restituisce tutti gli esami a cui era iscritto un ex studente data la sua matricola  
RETURNS ROW: idstorico, matricola, idcorso, idinsegnamento, iddocente, voto, stato, lode, data  
Parametri: 
    - the_matricola char(6)
---
- **uni.is_iscritto**: restituisce SE una matricola è iscritta o meno ad un esame  
RETURNS boolean  
Parametri:
    - the_matricola char(6)
    - the_IDEsame integer  
---