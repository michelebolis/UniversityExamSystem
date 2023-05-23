## Procedures:
- uni.insert_corso_laurea: permette di inserire un nuovo corso di laurea  
uni.insert_corso_laurea(  
    IDCorso varchar(20), (NOT NULL)  
    nome varchar(100), (NOT NULL)  
    anniTotali uni.tipoLaurea, (NOT NULL)  
    valoreLode integer (NOT NULL)  
)

- uni.insert_insegnamento: permette di inserire un nuovo insegnamento eventualmente assegnandogli un docente responsabile  
uni.insert_insegnamento(  
    IDDocente integer,   
    nome varchar(200), (NOT NULL)  
    descrizione text,   
    crediti integer, (NOT NULL)  
    annoAttivazione integer (NOT NULL)  
)

- uni.cambia_responsabile: dato un insegnamento, permette di cambiargli il docente responsabile. Nota: se non si sa ancora il nuovo docente responsabile, passare come newDocente NULL   
uni.cambia_responsabile(  
    insegnamentoToUpdate integer, (NOT NULL)  
    newDocente integer   
)

- uni.insert_utente: permette di inserire un nuovo utente. Nota: la email non deve essere gia presente  
uni.insert_utente(  
    ruolo uni.ruolo, (NOT NULL)  
    nome varchar(50), (NOT NULL)  
    cognome varchar(50), (NOT NULL)  
    new_email varchar(100), (NOT NULL)  
    password varchar(32), (NOT NULL)  
    cellulare varchar(20) (NOT NULL)  
)  

- uni.insert_docente: permette di inserire un nuovo docente ed assegnarlo come responsabile ad un insegnamento  
uni.insert_docente(  
    nome varchar(50), (NOT NULL)  
    cognome varchar(50), (NOT NULL)  
    new_email varchar(100), (NOT NULL)  
    password varchar(32), (NOT NULL)  
    cellulare varchar(20), (NOT NULL)  
    inizioRapporto date, (NOT NULL)  
    fineRapporto date,  
    insegnamentoToUpdate integer (NOT NULL)  
) 

- uni.modifica_utente: dato un utente, permette modificarne le credenziali di accesso e il recapito telefonico  
uni.modifica_utente(  
    the_IDUtente integer,   
    new_email varchar(100),   
    new_password varchar(32),   
    new_cellulare varchar(20)  
)

- uni.cambio_corso_laurea: data una matricola di uno studente, permette di cambiargli il corso di laurea eventualmente cambiando le credenziali di accesso (SE non si vogliono modificare, inserire NULL come argomenti nei corrispondenti campi)  
uni.cambio_corso_laurea(  
    the_matricola char(6), (NOT NULL)  
    IDCorso varchar(20), (NOT NULL)  
    dataImmatricolazione date, (NOT NULL)  
    new_email varchar(100),   
    password varchar(32),   
    cellulare varchar(20)  
)

- uni.insert_studente: permette di inserire un nuovo studente ad un corso di laurea  
uni.insert_studente(  
    nome varchar(50), (NOT NULL)  
    cognome varchar(50), (NOT NULL)  
    new_email varchar(100), (NOT NULL)  
    password varchar(32), (NOT NULL)  
    cellulare varchar(20), (NOT NULL)  
    the_codiceFiscale varchar(16), (NOT NULL)  
    IDCorso varchar(20), (NOT NULL)  
    dataImmatricolazione date (NOT NULL)  
) 

- uni.insert_segreteria: permette di inserire un nuovo utente della segreteria  
uni.insert_segreteria(  
    nome varchar(50), (NOT NULL)  
    cognome varchar(50), (NOT NULL)  
    new_email varchar(100), (NOT NULL)  
    password varchar(32), (NOT NULL)  
    cellulare varchar(20) (NOT NULL)  
) 

- uni.insert_esame: permette di inserire in una data stabilita un'esame di un insegnamento di cui è responsabile un docente  
uni.insert_esame(  
    the_IDDocente integer,   
    the_IDInsegnamento integer,   
    data date  
)

- uni.insert_propedeuticita: permette di inserire la propedeuticita che richiede un esame (IDInsegnamento1) rispetto ad un altro esame richiesto (IDInsegnamento2)  
uni.insert_propedeuticita(  
    IDInsegnamento1 integer,   
    IDInsegnamento2 integer  
)

- uni.iscrizione_esame: dato un idesame, permette ad uno studente di iscriversi  
uni.iscrizione_esame(  
    matricola char(6),   
    IDEsame integer  
)

- uni.insert_manifesto: permette di inserire un insegnamento per un corso di laurea in un determinato anno   
uni.insert_manifesto(  
    IDInsegnamento integer,   
    IDCorso varchar(20),   
    anno uni.annoCorso  
)

- uni.insert_sessione_laurea: permette di inserire in una determinata data, la sessione di laurea per un corso di laurea  
uni.insert_sessione_laurea(  
    data date,   
    IDCorso varchar(20),   
)

- uni.iscrizione_laurea: permette di far iscrivere uno studente ad una sessione di laurea disponibile per il suo corso di laurea in una determinata data  
uni.iscrizione_laurea(  
    matricola char(6),   
    data date,   
    IDCorso varchar(20)  
)

- uni.registrazione_esito_esame: data una matricola che si è iscritta in una sessione per una esame, permette di registrane l'esito, caratterizzato dal voto e dall'eventuale lode  
uni.registrazione_esito_esame(  
    the_matricola char(6),   
    the_IDEsame integer,   
    the_voto uni.voto,   
    the_lode boolean  
)

- uni.accetta_esito: permette ad uno studente di accettare o meno un esito di un esame conseguito  
uni.accetta_esito(  
    the_matricola char(6),   
    the_IDEsame integer,   
    accettato boolean  
)

- uni.delete_studente: permette di eliminare uno studente che segue un corso di laurea  
uni.delete_studente(  
    the_matricola char(6),   
    the_IDCorso varchar(20)  
)

- uni.calcola_voto_laurea: permette di calcolare il voto di laurea finale per uno studente dato l'incremento ottenuto nella prova finale  
uni.calcola_voto_laurea(  
    the_matricola char(6),   
    incremento integer  
)

- uni.registrazione_esito_laurea: dato uno studente di un corso di laurea, che ha conseguito la laurea in una determinata data, permette di registrarne l'incremento ottenuto e l'eventuale lode    
uni.registrazione_esito_laurea(  
    the_matricola char(6),   
    the_IDCorso integer,   
    the_data date,   
    incremento integer,   
    lode boolean  
)

## Functions:
- uni.new_matricola(): permette di calcolare la matricola da assegnare ad un nuovo studente   
uni.new_matricola()   
    RETURNS uni.matricola.matricola%type  

- uni.get_id_ruolo: date le credenziali di accesso di un utente, permette di restituirne l'identificativo e il ruolo dell'utente   
uni.get_id_ruolo(  
    the_email varchar(100),   
    the_password varchar(32)  
)   RETURNS SETOF uni.utente  

- uni.get_studente_bio: data la matricola e il corso di laurea in cui è iscritto lo studente, vengono restituite le sue informazioni   
uni.get_studente_bio(  
    corso varchar(20),   
    matricola varchar(6)  
)   RETURNS SETOF uni.studente_bio  
