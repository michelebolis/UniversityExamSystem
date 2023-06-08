-- ATTENZIONE: Assicurarsi di avere un database esistente 'uni'
-- creazione dello schema uni
DROP SCHEMA IF EXISTS uni CASCADE;
CREATE SCHEMA uni;

-- creazione dei domini 
CREATE DOMAIN uni.ruolo as varchar(100)
CHECK (
    VALUE = 'Segreteria' OR
    VALUE = 'Docente' OR 
    VALUE = 'Studente'
);

CREATE DOMAIN uni.tipoLaurea as int
CHECK (
    VALUE=3 OR VALUE=2
);

CREATE DOMAIN uni.annoCorso as int
CHECK (
    VALUE=3 OR VALUE=2 OR VALUE=1
);

CREATE DOMAIN uni.voto as int
CHECK (
    VALUE>=0 AND VALUE<=30
);

CREATE DOMAIN uni.statoEsito as varchar(100)
CHECK (
    VALUE = 'Ritirato' OR
    VALUE = 'Accettato' OR 
    VALUE = 'Rifiutato' OR 
    VALUE = 'Iscritto' OR
    VALUE = 'In attesa di accettazione' OR
    VALUE = 'Bocciato'   
);

CREATE DOMAIN uni.votoLaurea as int
CHECK (
    VALUE>=60 AND VALUE<=110
);

-- creazione delle tabelle del db uni nello schema uni
CREATE TABLE uni.utente(
    IDUtente SERIAL PRIMARY KEY,
    ruolo uni.ruolo NOT NULL,
    nome varchar(50) NOT NULL CHECK(nome!=''),
    cognome varchar(50) NOT NULL CHECK(cognome!=''),
    email varchar(100) NOT NULL UNIQUE CHECK(email!='' AND email LIKE '%@%'),
    password varchar(32) NOT NULL CHECK(password!=''),
    cellulare varchar(20) NOT NULL CHECK(cellulare!='')
);

CREATE TABLE uni.corso_laurea(
    IDCorso varchar(20) PRIMARY KEY CHECK(IDCorso!=''),
    nome varchar(100) NOT NULL CHECK(nome!=''),
    anniTotali uni.tipoLaurea NOT NULL,
    valoreLode integer NOT NULL CHECK(valoreLode>=30),
    attivo boolean NOT NULL
);

CREATE TABLE uni.docente(
    IDDocente integer PRIMARY KEY REFERENCES uni.utente(IDUtente),
    inizioRapporto date DEFAULT CURRENT_DATE,
    fineRapporto date DEFAULT NULL
);

CREATE TABLE uni.insegnamento(
    IDInsegnamento SERIAL PRIMARY KEY,
    IDDocente integer REFERENCES uni.docente(IDDocente) ON DELETE CASCADE DEFAULT NULL,
    nome varchar(200) NOT NULL CHECK(nome!=''),
    descrizione text,
    crediti integer NOT NULL CHECK(crediti>0),
    annoAttivazione integer NOT NULL CHECK(annoAttivazione>0)
);

CREATE TABLE uni.matricola(
    matricola char(6) PRIMARY KEY,
    IDUtente integer REFERENCES uni.utente(IDUtente) ON DELETE CASCADE NOT NULL UNIQUE,
    codiceFiscale varchar(16) NOT NULL UNIQUE CHECK(codiceFiscale!='')
);

CREATE TABLE uni.studente(
    IDCorso varchar(20) REFERENCES uni.corso_laurea(IDCorso) ON DELETE CASCADE,
    matricola char(6) REFERENCES uni.matricola(matricola) ON DELETE CASCADE UNIQUE,
    dataImmatricolazione date NOT NULL DEFAULT CURRENT_DATE,
    PRIMARY KEY (IDCorso, matricola)
);

CREATE TABLE uni.sessione_laurea(
    data date,
    IDCorso varchar(20) REFERENCES uni.corso_laurea(IDCorso) ON DELETE CASCADE,
    PRIMARY KEY(data, IDCorso)
);

CREATE TABLE uni.laurea(
    matricola char(6) REFERENCES uni.matricola(matricola) ON DELETE CASCADE,
    data date,
    IDCorso varchar(20),
    voto uni.votoLaurea DEFAULT NULL,
    incrementoVoto integer DEFAULT NULL CHECK (incrementoVoto>0) , 
    lode boolean DEFAULT NULL,
	FOREIGN KEY (data, IDCorso) REFERENCES uni.sessione_laurea(data, IDCorso) ON DELETE CASCADE,
	PRIMARY KEY (matricola, data, IDCorso)
);

CREATE TABLE uni.esame(
    IDEsame SERIAL PRIMARY KEY,
    IDDocente integer REFERENCES uni.docente(IDDocente) ON DELETE CASCADE NOT NULL,
    IDInsegnamento integer REFERENCES uni.insegnamento(IDInsegnamento) ON DELETE CASCADE NOT NULL,
    data date NOT NULL,
    orario time NOT NULL DEFAULT '12:00'
);

CREATE TABLE uni.storico_insegnamento(
    IDDocente integer REFERENCES uni.docente(IDDocente) ON DELETE CASCADE,
    IDInsegnamento integer,
    nome varchar(200) NOT NULL CHECK(nome!=''),
    crediti integer NOT NULL CHECK(crediti>0), 
    annoInizio integer NOT NULL CHECK(annoInizio>0),
    annoFine integer NOT NULL DEFAULT CAST(date_part('year', CURRENT_DATE) as integer) CHECK(annoFine>0),
    PRIMARY KEY(IDDocente, IDInsegnamento)
);

CREATE TABLE uni.storico_studente(
    matricola char(6) REFERENCES uni.matricola(matricola) ON DELETE CASCADE,
    IDCorso varchar(20) REFERENCES uni.corso_laurea(IDCorso) ON DELETE CASCADE,
    dataImmatricolazione date NOT NULL,
    dataRimozione date NOT NULL DEFAULT CURRENT_DATE,
    PRIMARY KEY(matricola, IDCorso)
);

CREATE TABLE uni.storico_esame(
    IDStorico SERIAL PRIMARY KEY,
    matricola char(6) NOT NULL,
    IDCorso varchar(20) NOT NULL,
    IDInsegnamento integer NOT NULL,
    IDDocente integer NOT NULL,
    voto uni.voto,
    stato uni.statoesito NOT NULL,
    lode boolean,
    data date NOT NULL,
	FOREIGN KEY (matricola, IDCorso) REFERENCES uni.storico_studente(matricola, IDCorso) ON DELETE CASCADE
);

CREATE TABLE uni.manifesto_insegnamenti(
    IDInsegnamento integer REFERENCES uni.insegnamento(IDInsegnamento) ON DELETE CASCADE,
    IDCorso varchar(20) REFERENCES uni.corso_laurea(IDCorso) ON DELETE CASCADE,
    anno uni.annoCorso NOT NULL CHECK(anno>0),
    PRIMARY KEY (IDInsegnamento, IDCorso) 
);

CREATE TABLE uni.esito(
    matricola char(6) REFERENCES uni.matricola(matricola) ON DELETE CASCADE,
    IDEsame integer REFERENCES uni.esame(IDEsame) ON DELETE CASCADE,
    voto uni.voto DEFAULT NULL,
    stato uni.statoEsito DEFAULT 'Iscritto',
    lode boolean DEFAULT NULL,
    PRIMARY KEY (matricola, IDEsame)
);

CREATE TABLE uni.propedeuticita(
    insegnamento integer REFERENCES uni.insegnamento(IDInsegnamento) ON DELETE CASCADE,
    insegnamentoRichiesto integer REFERENCES uni.insegnamento(IDInsegnamento) ON DELETE CASCADE,
    PRIMARY KEY (insegnamento, insegnamentoRichiesto)
);

-- Inserimento delle viste
-- studente_bio: contiene tutte le informazioni biografiche degli studenti iscritti
CREATE OR REPLACE VIEW uni.studente_bio AS 
	SELECT m.matricola, u.nome, u.cognome, u.email, u.cellulare, s.IDCorso, s.dataImmatricolazione
    FROM uni.utente as u INNER JOIN uni.matricola as m ON u.IDUtente = m.IDUtente
	INNER JOIN uni.studente as s ON m.matricola = s.matricola
;

-- Inserimento viste materializzate
CREATE TABLE uni.media_studente (
    matricola char(6) REFERENCES uni.matricola(matricola),
    IDCorso varchar(20) REFERENCES uni.corso_laurea(IDCorso),
    media decimal CHECK (media>=0) DEFAULT 0,
    crediti integer CHECK (crediti>=0) DEFAULT 0,
    PRIMARY KEY (matricola, IDCorso)
);

CREATE TABLE uni.carriera_studente (
    matricola char(6) REFERENCES uni.matricola(matricola),
    IDCorso varchar(20) REFERENCES uni.corso_laurea(IDCorso),
    IDInsegnamento integer NOT NULL,
    IDDocente integer NOT NULL,
    data date NOT NULL,
    voto uni.voto NOT NULL,
    lode boolean NOT NULL,
    stato uni.statoEsito DEFAULT 'Accettato',
    PRIMARY KEY (matricola, IDCorso, IDInsegnamento)
);

-- carriera_completa_studente: contiene tutti gli esami passati degli studenti presenti e passati
CREATE OR REPLACE VIEW uni.carriera_studente_view AS 
    SELECT matricola, IDCorso, c.IDInsegnamento, i.nome, c.IDDocente, data, voto, lode 
    FROM uni.carriera_studente as c INNER JOIN uni.insegnamento as i ON c.IDInsegnamento=i.IDInsegnamento
;

-- carriera_completa_studente: contiene tutti gli esami degli attuali studenti
CREATE OR REPLACE VIEW uni.carriera_completa_studente AS
    SELECT s.matricola, s.IDCorso, esa.IDInsegnamento, esa.IDDocente, esa.data, e.voto, e.lode, e.stato 
    FROM 
        uni.studente as s INNER JOIN uni.esito as e ON e.matricola = s.matricola
        INNER JOIN uni.esame as esa ON esa.IDEsame = e.IDEsame 
    UNION 
    SELECT c.matricola, c.IDCorso, c.IDInsegnamento, c.IDDocente, c.data, c.voto, c.lode, c.stato 
    FROM 
        uni.carriera_studente as c INNER JOIN uni.studente as s ON c.IDCorso= s.IDCorso AND s.matricola=c.matricola
;

-- Creazione delle funzioni
-- insert_corso_laurea: permette di inserire un nuovo corso di laurea
-- EXCEPTION SE il valore della lode < 30
-- il corso di laurea è di default attivo
CREATE OR REPLACE PROCEDURE uni.insert_corso_laurea(
    IDCorso varchar(20), nome varchar(100), anniTotali uni.tipoLaurea, valoreLode integer
)
AS $$
BEGIN 
    IF valoreLode < 30 THEN RAISE EXCEPTION 'Il valore della lode non puo essere minore di 30';
    END IF;
    INSERT INTO uni.corso_laurea(IDCorso, nome, anniTotali, valoreLode, attivo)
        VALUES (IDCorso, nome, anniTotali, valoreLode, True);
END;
$$ LANGUAGE plpgsql ;

-- insert_insegnamento: permette di inserire un nuovo insegnamento
-- IDDocente puo essere eventualmente NULL
CREATE OR REPLACE PROCEDURE uni.insert_insegnamento(
    IDDocente integer, nome varchar(200), descrizione text, crediti integer, annoAttivazione integer
)
AS $$
BEGIN 
    INSERT INTO uni.insegnamento(IDDocente, nome, descrizione, crediti, annoAttivazione)
        VALUES (IDDocente, nome, descrizione, crediti, annoAttivazione);
END;
$$ LANGUAGE plpgsql;

-- cambia_responsabile: permette di cambiare il docente responsabile di un corso
-- EXCEPTION SE l'insegnamento non esiste
CREATE OR REPLACE PROCEDURE uni.cambia_responsabile(
    insegnamentoToUpdate integer, newDocente integer
)
AS $$
DECLARE rec uni.insegnamento%ROWTYPE;
BEGIN
    PERFORM * FROM uni.insegnamento as c WHERE c.IDInsegnamento= insegnamentoToUpdate;
    IF NOT FOUND THEN 
        RAISE EXCEPTION 'Insegnamento non trovato';
    END IF;
    UPDATE uni.insegnamento SET IDDocente = newDocente WHERE IDInsegnamento=insegnamentoToUpdate;
END;
$$ LANGUAGE plpgsql;


-- insert_utente: permette di inserire un nuovo utente
CREATE OR REPLACE PROCEDURE uni.insert_utente(ruolo uni.ruolo, nome varchar(50), cognome varchar(50), new_email varchar(100), password varchar(32), cellulare varchar(20)) 
AS $$
BEGIN 
    INSERT INTO uni.utente(ruolo, nome, cognome, email, password, cellulare) 
        VALUES (ruolo, nome, cognome, new_email, (password), cellulare);
END;
$$ LANGUAGE plpgsql;

-- insert_docente: permette di inserire un nuovo docente
CREATE OR REPLACE PROCEDURE uni.insert_docente(
    nome varchar(50), cognome varchar(50), new_email varchar(100), password varchar(32), cellulare varchar(20), 
    inizioRapporto date, 
    insegnamentoToUpdate integer
) 
AS $$
DECLARE 
    newId uni.utente.IDUtente%TYPE;
BEGIN 
    PERFORM * FROM uni.insegnamento as c WHERE c.IDInsegnamento= insegnamentoToUpdate;
    IF NOT FOUND THEN 
        RAISE EXCEPTION 'Insegnamento non trovato';
    END IF;
    CALL uni.insert_utente('Docente', nome, cognome, new_email, password, cellulare);
    SELECT u.IDUtente INTO newId FROM uni.utente as u WHERE u.email=new_email;
    INSERT INTO uni.docente(IDDocente, inizioRapporto, fineRapporto) 
        VALUES (newId, inizioRapporto, NULL);
    CALL uni.cambia_responsabile(insegnamentoToUpdate, newId);
END;
$$ LANGUAGE plpgsql;

-- new_matricola: restituisce la matricola da assegnare a uno nuovo studente
-- LIMITE 999999 studenti
CREATE OR REPLACE FUNCTION uni.new_matricola() 
RETURNS uni.matricola.matricola%type AS $$
DECLARE 
	lastMatricola uni.matricola.matricola%TYPE;
	newMatricola uni.matricola.matricola%TYPE;
BEGIN
	SELECT m.matricola INTO lastMatricola FROM uni.matricola as m ORDER BY m.matricola desc;
	IF lastMatricola IS NULL THEN
		RETURN '000001';
	END IF;
	newMatricola := CAST((CAST(lastMatricola as integer)+1) as varchar(6));
	WHILE length(newMatricola)<6 LOOP
		newMatricola := ('0' || newMatricola );
	END LOOP;
    RETURN newMatricola;
END;
$$ LANGUAGE plpgsql;

-- modifica_utente: data un utente, è possibile modificarne la mail, la password e il cellulare
-- SE non si vuole modficare uno o piu di questi campi, gli argomenti devono essere NULL
CREATE OR REPLACE PROCEDURE uni.modifica_utente(
    the_IDUtente integer, new_email varchar(100), new_password varchar(32), new_cellulare varchar(20)
)
AS $$
BEGIN
    IF new_email IS NULL THEN
        SELECT email INTO new_email FROM uni.utente WHERE IDUtente=the_IDUtente;
    END IF;
    IF new_cellulare IS NULL THEN
        SELECT cellulare INTO new_cellulare FROM uni.utente WHERE IDUtente=the_IDUtente;
    END IF; 
    IF new_password IS NULL THEN
        SELECT password INTO new_password FROM uni.utente WHERE IDUtente=the_IDUtente;
        UPDATE uni.utente SET email=new_email, password=new_password, cellulare=new_cellulare
        WHERE IDUtente=the_IDUtente;
    ELSE 
        UPDATE uni.utente SET email=new_email, password=(new_password), cellulare=new_cellulare
        WHERE IDUtente=the_IDUtente;
    END IF; 
END;
$$ LANGUAGE plpgsql;

-- cambio_corso_laurea: procedura usata internamente per iscrivere uno studente ad un nuovo corso di laurea, 
-- recuperandone le informazioni in quanto già iscritto in precedenza
CREATE OR REPLACE PROCEDURE uni.cambio_corso_laurea(
    the_matricola char(6), the_IDCorso varchar(20), dataImmatricolazione date, new_email varchar(100), password varchar(32), cellulare varchar(20)
)
AS $$
DECLARE 
    the_IDUtente uni.utente.IDUtente%TYPE;
    insegnamento uni.manifesto_insegnamenti%ROWTYPE;
    esame uni.carriera_studente_view%ROWTYPE;
BEGIN
    -- inserisce il nuovo studente
    INSERT INTO uni.studente(matricola, IDCorso, dataImmatricolazione)
    VALUES (the_matricola, the_IDCorso, dataImmatricolazione);
    
    -- recupero il suo IDUtente passato
    SELECT u.IDUtente INTO the_IDUtente FROM uni.utente as u INNER JOIN uni.matricola as m 
    ON m.IDUtente=u.IDUtente AND m.matricola=the_matricola;
    CALL uni.modifica_utente (the_IDUtente, new_email, password, cellulare); -- Ne modifico i dati di accesso se richiesto

    FOR insegnamento IN -- per ogni insegnamento nel suo nuovo corso di laurea
        SELECT * FROM uni.get_manifesto(the_IDCorso)
    LOOP
        FOR esame IN -- recupeo ogni essame passato precedentemente che vale per il suo nuovo corso di laurea
            SELECT * FROM uni.carriera_studente_view WHERE matricola=the_matricola AND IDInsegnamento=insegnamento.IDInsegnamento 
        LOOP
            -- inserisco tale esame passato nella sua carriera
            INSERT INTO uni.carriera_studente(matricola, IDCorso, IDInsegnamento, IDDocente, voto, lode, data)
            VALUES (the_matricola, the_IDCorso, esame.IDInsegnamento, esame.IDDocente, esame.voto, esame.lode, esame.data);
        END LOOP;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- insert_studente: permette di inserire un nuovo studente date le informazioni biografiche, quelle di accesso e il corso di laurea a cui si sta iscrivendo
-- EXCEPTION SE il corso di laurea a cui si sta iscrivendo non ha ancora nessun insegnamento associato
CREATE OR REPLACE PROCEDURE uni.insert_studente(
    nome varchar(50), cognome varchar(50), new_email varchar(100), password varchar(32), cellulare varchar(20), 
    the_codiceFiscale varchar(16), the_IDCorso varchar(20), dataImmatricolazione date
) 
AS $$
DECLARE 
    old_matricola uni.matricola.matricola%TYPE;
    newId uni.utente.IDUtente%TYPE;
    newMatricola uni.matricola.matricola%TYPE;
BEGIN 
    PERFORM * FROM uni.manifesto_insegnamenti WHERE IDCorso=the_IDCorso;
    IF NOT FOUND THEN
        RAISE EXCEPTION 'Il corso di laurea è ancora in fase di preparazione, aggiungere almeno un insegnamento al manifesto';
    END IF;
    SELECT matricola INTO old_matricola FROM uni.matricola WHERE codiceFiscale=the_codiceFiscale;
    IF old_matricola IS NULL THEN --verifico che il codice fiscale non appartenga ad uno studente gia presente nel sistema
        -- SE non è presente nel sistema, lo creo
        CALL uni.insert_utente('Studente', nome, cognome, new_email, password, cellulare);
        SELECT * INTO newMatricola FROM uni.new_matricola();
        SELECT u.IDUtente INTO newId FROM uni.utente as u WHERE u.email=new_email;
        INSERT INTO uni.matricola(IDUtente, matricola, codiceFiscale) 
            VALUES (newId, newMatricola, the_codiceFiscale);

	    INSERT INTO uni.studente(matricola, dataImmatricolazione, IDCorso) 
            VALUES (newMatricola, dataImmatricolazione, the_IDCorso);
    ELSE
        -- SE è gia presente nel sistema, la sua iscrizione equivale ad un cambio di corso di laurea
        CALL uni.cambio_corso_laurea(old_matricola, the_IDCorso, dataImmatricolazione, new_email, password, cellulare);
        RETURN;
    END IF;
    
END;
$$ LANGUAGE plpgsql;

-- insert_segreteria: permette di inserire un nuovo utente della segreteria date le informazioni biografiche e quelle di accesso
CREATE OR REPLACE PROCEDURE uni.insert_segreteria(nome varchar(50), cognome varchar(50), new_email varchar(100), password varchar(32), cellulare varchar(20)) 
AS $$
BEGIN
    CALL uni.insert_utente('Segreteria', nome, cognome, new_email, password, cellulare);
END; 
$$ LANGUAGE plpgsql;

-- insert_esame: permette di inserire per un docente che è responsabile di un insegnamento un esame di quest'ultimo in una data
-- EXCEPTION SE il docente non è responsabile dell'insegnamento
-- EXCEPTION SE nella data scelta è già presente un esame previsto per lo stesso anno dell'insegnamento nel manifesto degli studi di un corso di laurea 
CREATE OR REPLACE PROCEDURE uni.insert_esame(
    the_IDDocente integer, the_IDInsegnamento integer, the_data date, orario time
)
AS $$
BEGIN 
    INSERT INTO uni.esame(IDDocente, IDInsegnamento, data, orario)
            VALUES (the_IDDocente, the_IDInsegnamento, the_data, orario);
END;
$$ LANGUAGE plpgsql;

-- insert_propedeuticita: permette di inserire, per un insegnamento, un altro insegnamento di cui è richiesto il superamento
-- EXCEPTION SE insegnamento e insegnamentoRichiesto sono uguali
CREATE OR REPLACE PROCEDURE uni.insert_propedeuticita(
    insegnamento integer, insegnamentoRichiesto integer
)
AS $$
BEGIN 
    IF insegnamento=insegnamentoRichiesto THEN
        RAISE EXCEPTION 'I due insegnamenti non possono essere lo stesso';
    END IF;
    INSERT INTO uni.propedeuticita(insegnamento, insegnamentoRichiesto)
        VALUES (insegnamento, insegnamentoRichiesto);
END;
$$ LANGUAGE plpgsql;

-- iscrizione_esame: data una matricola e un identificativo di un esame, permette di iscriversi all'esame
CREATE OR REPLACE PROCEDURE uni.iscrizione_esame(
    matricola char(6), IDEsame integer
)
AS $$
BEGIN 
    INSERT INTO uni.esito(matricola, IDEsame, stato)
        VALUES (matricola, IDEsame, 'Iscritto');
END;
$$ LANGUAGE plpgsql;

-- annulla_iscrizione_esame: data una matricola e un identificativo di un esame, permette di annullare l'iscrizione a tale esame
-- l'annullamento dell'iscrizione è possibile solo se l esame non si è gia tenuto
CREATE OR REPLACE PROCEDURE uni.annulla_iscrizione_esame(
    the_matricola char(6), the_IDEsame integer
)
AS $$
BEGIN 
    DELETE FROM uni.esito WHERE matricola=the_matricola AND IDEsame=the_IDEsame;
END;
$$ LANGUAGE plpgsql;

-- insert_manifesto: permette di inserire un insegnamento nel manifesto di studi di un corso di laurea in un anno
-- EXECEPTION: SE l'anno in cui si prevede l'insegnamento è il 3° ma il corso di laurea è di 2 anni
CREATE OR REPLACE PROCEDURE uni.insert_manifesto(
    IDInsegnamento integer, the_IDCorso varchar(20), anno uni.annoCorso
)
AS $$
DECLARE annitot uni.corso_laurea.anniTotali%TYPE;
BEGIN 
    IF anno=3 THEN
        SELECT anniTotali INTO annitot FROM uni.corso_laurea WHERE IDCorso=the_IDCorso;
        IF annitot=2 THEN 
            RAISE EXCEPTION 'Il corso di laurea a cui si vuole assegnare l insegnamento è di 2 anni';
        END IF;
    END IF;
    INSERT INTO uni.manifesto_insegnamenti(IDInsegnamento, IDCorso, anno)
        VALUES (IDInsegnamento, the_IDCorso, anno);
END;
$$ LANGUAGE plpgsql;

-- insert_sessione_laurea: permette di inserire una sessione di laurea di un corso di laurea in una data
CREATE OR REPLACE PROCEDURE uni.insert_sessione_laurea(
    data date, IDCorso varchar(20)
)
AS $$
BEGIN 
    INSERT INTO uni.sessione_laurea(data, IDCorso)
        VALUES (data, IDCorso);
END;
$$ LANGUAGE plpgsql;

-- iscrizione_laurea: permette ad una matricola di iscriversi ad una sessione di laurea in una data 
CREATE OR REPLACE PROCEDURE uni.iscrizione_laurea(
    matricola char(6), data date, IDCorso varchar(20)
)
AS $$
BEGIN 
    INSERT INTO uni.laurea(matricola, data, IDCorso)
        VALUES (matricola, data, IDCorso);
END;
$$ LANGUAGE plpgsql;

-- registrazione_esito_esame: data una matricola che si è iscritta ad un esame, permette di registrarne il voto e l'eventuale lode
-- EXCEPTION SE l'esito era già stato registrato
-- EXCEPTION SE la lode è assegnata ad un voto inferiore di 30
-- NOTA: se la coppia matricola, esame non è presente: non viene registrato nessun esito
CREATE OR REPLACE PROCEDURE uni.registrazione_esito_esame(
    the_matricola char(6), the_IDEsame integer, the_voto uni.voto, the_lode boolean
)
AS $$
DECLARE newStato uni.esito.stato%TYPE;
BEGIN 
    PERFORM * FROM uni.esito WHERE matricola=the_matricola AND IDEsame=the_IDEsame AND stato!='Iscritto';
    IF FOUND THEN
        RAISE EXCEPTION 'Esito gia registrato';
    END IF;
    IF the_voto IS NULL THEN
        newStato='Ritirato';
        ELSE IF the_voto<18 THEN 
            newStato='Bocciato';
        ELSE
            newStato='In attesa di accettazione';
        END IF;
    END IF;
    IF the_voto!=30 AND the_lode THEN
        RAISE EXCEPTION 'La lode puo essere assegnata solo ad un voto 30';
    END IF;
    UPDATE uni.esito SET voto=the_voto, lode=the_lode, stato=newStato
    WHERE matricola=the_matricola AND IDEsame=the_IDEsame;
END;
$$ LANGUAGE plpgsql;

-- accetta_esito: data una matricola che si è iscritta ad un esame, permette di accetare o rifiutare l'esito
-- NOTA: se la coppia matricola, esame non è presente: non viene accettato nessun esito
CREATE OR REPLACE PROCEDURE uni.accetta_esito(
    the_matricola char(6), the_IDEsame integer, accettato boolean
)
AS $$
BEGIN 
    IF accettato=True THEN
        UPDATE uni.esito SET stato='Accettato'
        WHERE matricola=the_matricola AND IDEsame=the_IDEsame; 
    ELSE
        UPDATE uni.esito SET stato='Rifiutato'
        WHERE matricola=the_matricola AND IDEsame=the_IDEsame;
    END IF;
END;
$$ LANGUAGE plpgsql;

-- delete_studente: permette di cancellare uno studente data la sua matricola iscritta ed il corso di laurea a cui è iscritto
CREATE OR REPLACE PROCEDURE uni.delete_studente(
    the_matricola char(6), the_IDCorso varchar(20)
)
AS $$
BEGIN 
    DELETE FROM uni.studente WHERE matricola=the_matricola AND IDCorso=the_IDCorso;
END;
$$ LANGUAGE plpgsql;

-- calcola_voto_laurea: permette di calcolare il voto finale della laurea data la matricola dello studente iscritta ad un corso di laurea e l'eventuale incrememnto 
CREATE OR REPLACE FUNCTION uni.calcola_voto_laurea(
    the_matricola char(6), the_IDCorso varchar(20), incremento integer
)
RETURNS decimal AS $$
DECLARE the_media decimal; voto decimal;
BEGIN 
    SELECT media INTO the_media FROM uni.media_studente WHERE matricola=matricola AND IDCorso=the_IDCorso;
    voto:=(the_media*110/30)+incremento;
    IF VOTO>=110 THEN
        RETURN 110;
    ELSE 
        RETURN CAST(ROUND(voto) as int);
    END IF;
END;
$$ LANGUAGE plpgsql;

-- registrazion_esito_laurea: permette di registrarre l'esito della sessione di laurea, in una specifica data, di uno studente di un corso di laurea
-- a cui è stato assegnato un eventuale incremento e l'eventuale lode
CREATE OR REPLACE PROCEDURE uni.registrazione_esito_laurea(
    the_matricola char(6), the_IDCorso varchar(20), the_data date, incremento integer, the_lode boolean
)
AS $$
DECLARE votoFinale int;
BEGIN 
    SELECT * INTO votoFinale FROM uni.calcola_voto_laurea(the_matricola, the_IDCorso, incremento);
    UPDATE uni.laurea SET incrementoVoto=incremento, lode=the_lode, voto=votoFinale
    WHERE matricola=the_matricola AND IDCorso=the_IDCorso AND data=the_data;
END;
$$ LANGUAGE plpgsql;

-- get_id_ruolo: data la mail e password di un utente, restituisce l'id dell'utente e il suo ruolo
-- SE email e password non corrispondono a nessun utente, restituisce una riga vuota
CREATE OR REPLACE FUNCTION uni.get_id_ruolo(the_email varchar(100), the_password varchar(32))
RETURNS SETOF uni.utente AS $$
DECLARE ut uni.utente%ROWTYPE;
BEGIN
    SELECT u.IDUtente, u.ruolo INTO ut
    FROM uni.utente as u 
    WHERE u.email=the_email AND u.password=md5(the_password);
	RETURN NEXT ut;
END;
$$ LANGUAGE plpgsql;

-- get_utente_bio: permette di ottenere tutti i dati dell'utente dato il suo identificativo
-- NOTA: la password è cifrata quindi non comprensibile
CREATE OR REPLACE FUNCTION uni.get_utente_bio(the_idutente integer)
RETURNS SETOF uni.utente AS $$
DECLARE ut uni.utente%ROWTYPE;
BEGIN
    SELECT * INTO ut
    FROM uni.utente as u 
    WHERE idutente=the_idutente;
	RETURN NEXT ut;
END;
$$ LANGUAGE plpgsql;

-- get_id_studente: permette di ottenere l'idutente data la matricola dello studente
CREATE OR REPLACE FUNCTION uni.get_id_studente(the_matricola char(6))
RETURNS integer AS $$
DECLARE id uni.matricola.idutente%TYPE;
BEGIN
    SELECT idutente INTO id
    FROM uni.matricola  
    WHERE matricola=the_matricola;
	RETURN id;
END;
$$ LANGUAGE plpgsql;

-- get_next_exam_bydoc: permette di ottenere le informazioni dei prossimi esami di un docente
-- ROW: idesame, idinsegnamento, data, ora
CREATE OR REPLACE FUNCTION uni.get_next_exam_bydoc(the_IDDocente integer)
RETURNS SETOF uni.esame AS $$
DECLARE exam uni.esame%ROWTYPE;
BEGIN
    FOR exam IN
        SELECT * FROM uni.esame 
        WHERE IDDocente=the_IDDocente AND data>=CURRENT_DATE
        ORDER BY data
    LOOP
        RETURN NEXT exam;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_past_esame: restituisce gli esami passati dato un docente
-- ROW: idesame, idinsegnamento, iddocente, data
CREATE OR REPLACE FUNCTION uni.get_past_esame(the_IDDocente integer)
RETURNS SETOF uni.esame AS $$
DECLARE esame uni.esame%ROWTYPE;
BEGIN
    FOR esame IN
        SELECT * FROM uni.esame
        WHERE IDDocente=the_IDDocente AND data<=CURRENT_DATE
    LOOP
        RETURN NEXT esame;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_studente_bio: restituisce le informazioni biografiche di uno studente data la sua matricola e il corso di laurea a cui è iscritto
-- ROW: matricola, nome, cognome, email, cellulare, idcorso, dataImmatricolazione
CREATE OR REPLACE FUNCTION uni.get_studente_bio(the_matricola varchar(6), the_corso varchar(20))
RETURNS SETOF uni.studente_bio AS $$
DECLARE stud uni.studente_bio%ROWTYPE;
BEGIN
    SELECT u.* INTO stud
    FROM uni.studente_bio as u 
    WHERE the_corso=u.idcorso AND the_matricola=u.matricola;
	RETURN NEXT stud;
END;
$$ LANGUAGE plpgsql;

-- get_studente_bio: restituisce le informazioni biografiche di uno studente dato il suo identificativo come utente
-- ROW: matricola, nome, cognome, email, cellulare, idcorso, dataImmatricolazione
CREATE OR REPLACE FUNCTION uni.get_studente_bio(the_idutente integer)
RETURNS SETOF uni.studente_bio AS $$
DECLARE stud uni.studente%ROWTYPE;
BEGIN
    SELECT * INTO stud
    FROM uni.studente as s INNER JOIN uni.matricola as m 
    ON m.matricola=s.matricola
    WHERE the_idutente=idutente;
	RETURN NEXT uni.get_studente_bio(stud.matricola, stud.idcorso);
END;
$$ LANGUAGE plpgsql;

-- get_exstudente_bio: permette di recuperare la matricola e i corsi di laurea a cui è stato iscritto un ex studente dato il suo identificativo come utente
-- ROW: matricola, idcorso
CREATE OR REPLACE FUNCTION uni.get_exstudente_bio(the_idutente integer)
RETURNS SETOF uni.storico_studente AS $$
DECLARE stud uni.storico_studente%ROWTYPE;
BEGIN
    FOR stud IN 
        SELECT m.matricola, idcorso 
        FROM uni.storico_studente as s INNER JOIN uni.matricola as m 
        ON m.matricola=s.matricola
        WHERE the_idutente=idutente
    LOOP
	    RETURN NEXT stud;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_laurea: restituisce eventuali lauree data una matricola
-- ROW: matricola, data, idcorso, voto, incremento, lode
CREATE OR REPLACE FUNCTION uni.get_laurea(the_matricola varchar(6))
RETURNS SETOF uni.laurea AS $$
DECLARE laurea uni.laurea%ROWTYPE;
BEGIN
    FOR laurea IN 
        SELECT * FROM uni.laurea WHERE matricola=the_matricola
    LOOP 
	    RETURN NEXT laurea;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_insegnamento: restituisce le informazioni di un insegnamento
-- ROW: idinsegnamento, iddocente, nome, descrizione, crediti, annoattivazione
CREATE OR REPLACE FUNCTION uni.get_insegnamento(the_IDInsegnamento integer)
RETURNS SETOF uni.insegnamento AS $$
DECLARE insegnamento uni.insegnamento%ROWTYPE;
BEGIN
    SELECT * INTO insegnamento
    FROM uni.insegnamento 
    WHERE IDInsegnamento=the_IDInsegnamento;
	RETURN NEXT insegnamento;
END;
$$ LANGUAGE plpgsql;

-- get_manifesto: restituisce gli insegnamenti presenti nel manifesto degli studi di un corso di laurea
-- ROW: idcorso, idinsegnamento, anno
CREATE OR REPLACE FUNCTION uni.get_manifesto(the_IDCorso varchar(20))
RETURNS SETOF uni.manifesto_insegnamenti AS $$
DECLARE manifesto uni.manifesto_insegnamenti%ROWTYPE;
BEGIN
    FOR manifesto IN
        SELECT * FROM uni.manifesto_insegnamenti 
        WHERE IDCorso=the_IDCorso
        ORDER BY anno
    LOOP
	    RETURN NEXT manifesto;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_esame: restituisce le informazioni di un esame
-- ROW: idesame, idinsegnamento, iddocente, data, ora
CREATE OR REPLACE FUNCTION uni.get_esame(the_IDEsame integer)
RETURNS SETOF uni.esame AS $$
DECLARE esame uni.esame%ROWTYPE;
BEGIN
    SELECT * INTO esame
    FROM uni.esame 
    WHERE IDEsame=the_IDEsame;
	RETURN NEXT esame;
END;
$$ LANGUAGE plpgsql;

-- get_corso: restituisce le informazioni di un corso di laurea
-- ROW: idcorso, nome, annitotali, valorelode, attivo
CREATE OR REPLACE FUNCTION uni.get_corso(the_IDCorso varchar(20))
RETURNS SETOF uni.corso_laurea AS $$
DECLARE corso uni.corso_laurea%ROWTYPE;
BEGIN
    SELECT * INTO corso
    FROM uni.corso_laurea 
    WHERE IDCorso=the_IDCorso;
	RETURN NEXT corso;
END;
$$ LANGUAGE plpgsql;

-- get_all_insegnamento: restituisce le informazioni di tutti gli insegnamenti
-- ROW: idinsegnamento, iddocente, nome, descrizione, crediti, annoattivazione
CREATE OR REPLACE FUNCTION uni.get_all_insegnamento()
RETURNS SETOF uni.insegnamento AS $$
DECLARE insegnamento uni.insegnamento%ROWTYPE;
BEGIN
    FOR insegnamento IN
        SELECT * FROM uni.insegnamento 
    LOOP
    	RETURN NEXT insegnamento;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_all_insegnamento_mancante: restituisce gli identificativi degli insegnamenti mancanti ad una matricola
-- ROW: idinsegnamento
CREATE OR REPLACE FUNCTION uni.get_all_insegnamento_mancante(the_matricola char(6))
RETURNS SETOF uni.insegnamento AS $$
DECLARE insegnamento uni.insegnamento%ROWTYPE;
BEGIN
    FOR insegnamento IN
        (SELECT IDInsegnamento FROM uni.manifesto_insegnamenti as m
        WHERE m.idcorso=(SELECT idcorso FROM uni.studente WHERE matricola=the_matricola)
        EXCEPT
        SELECT IDInsegnamento FROM uni.carriera_studente
        WHERE matricola=the_matricola)
    LOOP
    	RETURN NEXT insegnamento;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_all_corso: restituisce le informazioni di tutti i corsi di laurea
-- ROW: idcorso, nome, annitotali, valorelode, attivo
CREATE OR REPLACE FUNCTION uni.get_all_corso()
RETURNS SETOF uni.corso_laurea AS $$
DECLARE corso uni.corso_laurea%ROWTYPE;
BEGIN
    FOR corso IN
        SELECT * FROM uni.corso_laurea 
    LOOP
    	RETURN NEXT corso;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_all_docente: restituisce le informazioni di tutti i docenti
-- ROW: iddocente, iniziorapporto, finerapporto
CREATE OR REPLACE FUNCTION uni.get_all_docente()
RETURNS SETOF uni.docente AS $$
DECLARE docente uni.docente%ROWTYPE;
BEGIN
    FOR docente IN
        SELECT * FROM uni.docente 
    LOOP
    	RETURN NEXT docente;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_all_studente: restituisce le informazioni di tutti gli studenti iscritti
-- ROW: matricola, idcorso, dataimmatricolazione
CREATE OR REPLACE FUNCTION uni.get_all_studente()
RETURNS SETOF uni.studente AS $$
DECLARE studente uni.studente%ROWTYPE;
BEGIN
    FOR studente IN
        SELECT * FROM uni.studente 
    LOOP
    	RETURN NEXT studente;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_all_studente_bycorso: restituisce le informazioni di tutti gli studenti iscritti ad un corso di laurea
-- ROW: matricola, idcorso, dataimmatricolazione
CREATE OR REPLACE FUNCTION uni.get_all_studente_bycorso(corso varchar(20))
RETURNS SETOF uni.studente AS $$
DECLARE studente uni.studente%ROWTYPE;
BEGIN
    FOR studente IN
        SELECT * FROM uni.studente WHERE IDCorso=corso
    LOOP
    	RETURN NEXT studente;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_all_sessione: restituisce le informazioni su tutte le sessioni di laurea
-- ROW: idcorso, data
CREATE OR REPLACE FUNCTION uni.get_all_sessione()
RETURNS SETOF uni.sessione_laurea AS $$
DECLARE sessione uni.sessione_laurea%ROWTYPE;
BEGIN
    FOR sessione IN
        SELECT * FROM uni.sessione_laurea
    LOOP
    	RETURN NEXT sessione;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_all_sessione_bycorso: restituisce le informazioni su tutte le sessioni di laurea di un corso di laurea specificato
-- ROW: idcorso, data
CREATE OR REPLACE FUNCTION uni.get_all_sessione_bycorso(the_corso varchar(20))
RETURNS SETOF uni.sessione_laurea AS $$
DECLARE sessione uni.sessione_laurea%ROWTYPE;
BEGIN
    FOR sessione IN
        SELECT * FROM uni.sessione_laurea WHERE IDCorso=the_corso
    LOOP
    	RETURN NEXT sessione;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_all_iscrizione: restituisce le informazioni degli esami a cui una matricola è iscritta e per cui non ha ancora ricevuto un'esito
-- ROW: idesame, idinsegnamento, iddocente, data, ora
CREATE OR REPLACE FUNCTION uni.get_all_iscrizione(the_matricola char(6))
RETURNS SETOF uni.esame AS $$
DECLARE 
    info_esame uni.esame%ROWTYPE;
    a_esame uni.esame.idesame%TYPE;
BEGIN
    FOR a_esame IN
        SELECT IDEsame FROM uni.esito WHERE matricola=the_matricola AND stato='Iscritto'
    LOOP
        FOR info_esame IN 
            SELECT * FROM uni.esame WHERE IDEsame=a_esame
        LOOP
            RETURN NEXT info_esame;
        END LOOP;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_all_nextiscrizione: restituisce le informazioni degli esami futuri a cui una matricola è iscritta 
-- ROW: idesame, idinsegnamento, iddocente, data
CREATE OR REPLACE FUNCTION uni.get_all_nextiscrizione(the_matricola char(6))
RETURNS SETOF uni.esame AS $$
DECLARE 
    info_esame uni.esame%ROWTYPE;
BEGIN
    FOR info_esame IN
        SELECT * FROM uni.get_all_iscrizione(the_matricola) WHERE data>CURRENT_DATE
    LOOP
        RETURN NEXT info_esame;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_all_esito_attesa_acc: restituisce gli esiti in attesa di accettazione per una matricola
-- ROW: matricola, idesame, voto, stato, lode
CREATE OR REPLACE FUNCTION uni.get_all_esito_attesa_acc(the_matricola char(6))
RETURNS SETOF uni.esito AS $$
DECLARE esito uni.esito%ROWTYPE;
BEGIN
    FOR esito IN
        SELECT * FROM uni.esito WHERE matricola=the_matricola AND stato='In attesa di accettazione'
    LOOP
    	RETURN NEXT esito;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_sessione_esame: restituisce gli esami futuri previsti per un insegnamento
-- ROW: idesame, idinsegnamento, iddocente, data, ora
CREATE OR REPLACE FUNCTION uni.get_sessione_esame(the_IDInsegnamento integer) 
RETURNS SETOF uni.esame AS $$
DECLARE sessione uni.esame%ROWTYPE;
BEGIN
    FOR sessione IN
        SELECT * FROM uni.esame WHERE IDInsegnamento=the_IDInsegnamento AND data>=CURRENT_DATE
    LOOP
    	RETURN NEXT sessione;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_insegnamenti: restituisce gli insegnamenti di cui è responsabile un docente
-- ROW: idinsegnamento, iddocente, nome, descrizione, crediti, annoattivazione
CREATE OR REPLACE FUNCTION uni.get_insegnamenti(the_IDDocente integer)
RETURNS SETOF uni.insegnamento AS $$
DECLARE insegnamento uni.insegnamento%ROWTYPE;
BEGIN
    SELECT * INTO insegnamento
    FROM uni.insegnamento 
    WHERE IDDocente=the_IDDocente;
	RETURN NEXT insegnamento;
END;
$$ LANGUAGE plpgsql;

-- get_iscritti_esame: restituisce le matricole iscritte ad un esame senza ancora un esito
-- ROW: matricola
CREATE OR REPLACE FUNCTION uni.get_iscritti_esame(the_IDEsame integer)
RETURNS SETOF uni.esito AS $$
DECLARE stud uni.esito%ROWTYPE;
BEGIN
    FOR stud IN
        SELECT matricola FROM uni.esito
        WHERE IDEsame=the_IDEsame AND stato='Iscritto'
    LOOP
        RETURN NEXT stud;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_iscritti_laurea: restituisce le matricola iscritte ad una sessione di laurea senza ancora il voto finale registrato
-- ROW: matricola
CREATE OR REPLACE FUNCTION uni.get_iscritti_laurea(the_IDCorso varchar(20), the_data date)
RETURNS SETOF uni.laurea AS $$
DECLARE stud uni.laurea%ROWTYPE;
BEGIN
    FOR stud IN
        SELECT matricola FROM uni.laurea
        WHERE IDCorso=the_IDCorso AND data=the_data AND voto=NULL
    LOOP
        RETURN NEXT stud;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_past_corso: restiuisce i corsi di laurea a cui è stato iscritto uno studente, data la sua matricola
-- matricola, idcorso, dataimmatricolazione, datarimozione
CREATE OR REPLACE FUNCTION uni.get_past_corso(the_matricola varchar(20))
RETURNS SETOF uni.storico_studente AS $$
DECLARE stud uni.storico_studente%ROWTYPE;
BEGIN
    FOR stud IN
        SELECT * FROM uni.storico_studente
        WHERE matricola=the_matricola
    LOOP
        RETURN NEXT stud;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_studente_stats: restituisce le statistiche di uno studente, data la sua matricola e un corso di laurea
-- ROW: matricola, idcorso, media, crediti
CREATE OR REPLACE FUNCTION uni.get_studente_stats(the_matricola char(6), corso varchar(20))
RETURNS SETOF uni.media_studente AS $$
DECLARE stats uni.media_studente%ROWTYPE;
BEGIN
    SELECT * INTO stats FROM uni.media_studente WHERE matricola=the_matricola AND IDCorso=corso;
    RETURN NEXT stats;
END;
$$ LANGUAGE plpgsql;

-- get_num_esami_passati: restituisce il numero di esami superati da uno studente mentre è/era iscritto ad un corso di laurea
-- ROW: integer
CREATE OR REPLACE FUNCTION uni.get_num_esami_passati(the_matricola char(6), corso varchar(20))
RETURNS integer AS $$
DECLARE num integer;
BEGIN
    SELECT COUNT(*) INTO num FROM uni.carriera_studente WHERE matricola=the_matricola AND IDCorso=corso;
    RETURN num;
END;
$$ LANGUAGE plpgsql;

-- get_carriera_studente: restituisce gli esami superati da uno studente iscritto, data la sua matricola
-- ROW: matricola, idcorso, idinsegnamento, nome, iddocente, data, voto, lode
CREATE OR REPLACE FUNCTION uni.get_carriera_studente(the_matricola char(6))
RETURNS SETOF uni.carriera_studente_view AS $$
DECLARE exam uni.carriera_studente_view%ROWTYPE;
BEGIN 
    FOR exam IN 
        SELECT * FROM uni.carriera_studente_view WHERE matricola=the_matricola AND IDCorso=(
            SELECT IDCorso FROM uni.studente WHERE matricola=the_matricola
        )
        ORDER BY data
    LOOP
        RETURN NEXT exam;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_carriera_studente: restituisce gli esami superati da uno studente, data la sua matricola e un corso id laurea
-- ROW: matricola, idcorso, idinsegnamento, nome, iddocente, data, voto, lode
CREATE OR REPLACE FUNCTION uni.get_carriera_studente(the_matricola char(6), the_IDCorso varchar(20))
RETURNS SETOF uni.carriera_studente_view AS $$
DECLARE exam uni.carriera_studente_view%ROWTYPE;
BEGIN 
    FOR exam IN 
        SELECT * FROM uni.carriera_studente_view WHERE matricola=the_matricola AND IDCorso=the_IDCorso 
        ORDER BY data
    LOOP
        RETURN NEXT exam;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_carriera_completa_studente: restituisce tutti gli esami a cui si è iscritto uno studente iscritto, data la sua matricola
-- ROW: matricola, idcorso, idinsegnamento, nome, iddocente, data, voto, lode
CREATE OR REPLACE FUNCTION uni.get_carriera_completa_studente(the_matricola char(6))
RETURNS SETOF uni.carriera_completa_studente AS $$
DECLARE exam uni.carriera_studente%ROWTYPE;
BEGIN 
    FOR exam IN 
        SELECT * FROM uni.carriera_completa_studente WHERE matricola=the_matricola
        ORDER BY data
    LOOP
        RETURN NEXT exam;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- get_carriera_passata_studente: restituisce tutti gli esami a cui era iscritto un ex studente data la sua matricola
-- ROW: idstorico, matricola, idcorso, idinsegnamento, iddocente, voto, stato, lode, data
CREATE OR REPLACE FUNCTION uni.get_carriera_passata_studente(the_matricola char(6))
RETURNS SETOF uni.storico_esame AS $$
DECLARE 
    corso_passato uni.storico_studente.IDCorso%TYPE;
    esame_passato uni.storico_esame%ROWTYPE;
BEGIN
    FOR corso_passato IN 
        SELECT IDCorso FROM uni.storico_studente WHERE matricola=the_matricola
    LOOP
        FOR esame_passato IN 
            SELECT * FROM uni.storico_esame WHERE IDCorso=corso_passato AND matricola=the_matricola
            ORDER BY data
        LOOP
            RETURN NEXT esame_passato;
        END LOOP;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- is_iscritto: restituisce SE una matricola è iscritta o meno ad un esame
CREATE OR REPLACE FUNCTION uni.is_iscritto(the_matricola char(6), the_IDEsame integer)
RETURNS boolean AS $$
BEGIN
    PERFORM * FROM uni.esito WHERE matricola=the_matricola AND IDEsame=the_IDEsame;
    RETURN FOUND;
END
$$ LANGUAGE plpgsql;

-- creazione dei trigger
-- insert_media: all'inserimento di un nuovo studente, inizializza la media di un nuovo studente di un corso di laurea a 0 nella vista materializzata
CREATE OR REPLACE FUNCTION uni.insert_media()
RETURNS TRIGGER AS $$
BEGIN 
    INSERT INTO uni.media_studente(matricola, IDCorso)
    VALUES (NEW.matricola, NEW.IDCorso);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER insert_media_trigger AFTER INSERT ON uni.studente 
FOR EACH ROW EXECUTE FUNCTION uni.insert_media();

-- update_media: all'inserimento nella vista materializzata carriera_studente di un esame, che quindi è stato superato, 
-- aggiorna la vista materializzata media_studente dato il nuovo esito
CREATE OR REPLACE FUNCTION uni.update_media()
RETURNS TRIGGER AS $$
DECLARE
    corso uni.media_studente.IDCorso%TYPE;
    old_stats uni.media_studente%ROWTYPE;
    new_media uni.media_studente.media%TYPE;
    valLode uni.corso_laurea.valoreLode%TYPE;
    num_crediti uni.insegnamento.crediti%TYPE;
    info_esame uni.esame%ROWTYPE;
BEGIN
        -- recupero la media ed il numero di crediti prima del nuovo esito accettato e i crediti del nuovo esito accettato
        SELECT * INTO old_stats FROM uni.media_studente WHERE matricola=NEW.matricola AND IDCorso=NEW.IDCorso;
        SELECT crediti INTO num_crediti FROM uni.insegnamento WHERE IDInsegnamento=NEW.IDInsegnamento;
        
        -- SE il nuovo esito ha la lode, recupero il valore della lode del corso di laurea
        IF NEW.lode THEN -- reminder: lode<->30 viene controllato in accetta esito
            SELECT valoreLode INTO valLode FROM uni.corso_laurea WHERE IDCorso=NEW.IDCorso;
            new_media:=(old_stats.media*old_stats.crediti+num_crediti*valLode)/(old_stats.crediti+num_crediti);
        ELSE 
            new_media:=(old_stats.media*old_stats.crediti+num_crediti*NEW.voto)/(old_stats.crediti+num_crediti);
        END IF;

        -- aggiorno la vista materializzata
        UPDATE uni.media_studente SET media=new_media, crediti=old_stats.crediti+num_crediti
        WHERE matricola=NEW.matricola AND IDCorso=NEW.IDCorso;
        RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER update_media_trigger AFTER INSERT ON uni.carriera_studente 
FOR EACH ROW EXECUTE FUNCTION uni.update_media();


-- check_esame_trigger: all'inserimento di una nuova sessione di esame controlla che non siano gia previsti esami
-- nello stesso giorno per insegnamenti previsti nello stesso anno nel manifesto degli studi
CREATE OR REPLACE FUNCTION uni.check_esame_trigger()
RETURNS TRIGGER AS $$ 
DECLARE
    corso_laurea uni.manifesto_insegnamenti%ROWTYPE;
    altro_insegnamento uni.insegnamento.IDInsegnamento%TYPE;
BEGIN
    PERFORM * FROM uni.insegnamento as i WHERE i.IDInsegnamento=NEW.IDInsegnamento AND i.IDDocente=NEW.IDDocente;
    IF FOUND THEN 
        -- IF NEW.data<CURRENT_DATE THEN
            -- RAISE EXCEPTION 'La data non puo essere precedente a quella di oggi';
        -- END IF;

        -- controllo che non ci sia nella stessa data previsto nello stesso anno, un altro esame di un altro insegnamento 
        FOR corso_laurea IN 
            SELECT * FROM uni.manifesto_insegnamenti WHERE IDInsegnamento=NEW.IDInsegnamento
        LOOP
            FOR altro_insegnamento IN 
                SELECT m.IDInsegnamento FROM uni.insegnamento as i INNER JOIN uni.manifesto_insegnamenti as m 
                ON i.IDInsegnamento=m.IDInsegnamento 
                WHERE m.IDCorso=corso_laurea.IDCorso AND m.anno=corso_laurea.anno
            LOOP
                PERFORM * FROM uni.esame WHERE altro_insegnamento=IDInsegnamento AND data=NEW.data;
                IF FOUND THEN
                    RAISE EXCEPTION 'Nella data selezionata è gia presente un esame dello stesso anno per un corso di laurea dell insegnamento';
                END IF;
            END LOOP;
        END LOOP;
    ELSE
        RAISE EXCEPTION 'Il docente non è attualmente responsabile dell insegnamento, non puo inserire un esame per tale insegnamento';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER check_esame_trigger BEFORE INSERT ON uni.esame 
FOR EACH ROW EXECUTE FUNCTION uni.check_esame_trigger();

-- check_insertesito: all'inserimento di un nuovo esito, verifico che il nuovo stato sia Iscritto e che lo studente 
-- NON abbia già accetato un esito positivo per lo stesso insegnamento, sia del corso di laurea dell'insegnamento 
-- e che abbia superato gli esami propedeutici a tale insegnamento
CREATE OR REPLACE FUNCTION uni.check_insertesito()
RETURNS TRIGGER AS $$
DECLARE 
    corso uni.media_studente.IDCorso%TYPE;
    insegnamento_richiesto uni.propedeuticita.insegnamentoRichiesto%TYPE;
BEGIN 
    CASE NEW.stato
        WHEN 'Ritirato', 'Accettato', 'Rifiutato', 'Bocciato', 'In attesa di accettazione' THEN
            RAISE EXCEPTION 'All inserimento il primo stato deve essere Iscritto';
        
        WHEN 'Iscritto' THEN
            -- INSERT con uni.iscrizione_esame(...)
            -- controlla che lo studente non si sia gia iscritto alla stessa sessione di esame
            PERFORM * FROM uni.esito WHERE matricola=NEW.matricola AND IDEsame=NEW.IDEsame;
            IF FOUND THEN
                RAISE EXCEPTION 'Lo studente si è gia iscritto a questa sessione dell esame';
            END IF;
            
            -- controlla che lo studente sia dello stesso corso di laurea dell insegnamento per cui sostiene l'esame
            SELECT IDCorso INTO corso FROM uni.studente WHERE matricola=NEW.matricola;
            PERFORM * FROM uni.manifesto_insegnamenti WHERE IDCorso=corso AND IDInsegnamento=(
                SELECT IDInsegnamento FROM uni.esame WHERE IDEsame = NEW.IDEsame
            );
            IF FOUND THEN
                -- controlla che lo studente non abbia gia dato accettato un esito positivo per quell insegnamento
                PERFORM * FROM uni.carriera_studente_view WHERE matricola=NEW.matricola AND IDInsegnamento=(
                    SELECT IDInsegnamento FROM uni.esame WHERE IDEsame = NEW.IDEsame
                );
                IF FOUND THEN 
                    RAISE EXCEPTION 'Lo studente ha gia accettato un esito positivo per l insegnamento';
                ELSE 
                    -- controlla che abbia la propedeuticita richiesta per iscriversi all esame
                    FOR insegnamento_richiesto IN
                        SELECT insegnamentoRichiesto FROM uni.propedeuticita
                        WHERE insegnamento=(SELECT IDInsegnamento FROM uni.esame WHERE IDEsame = NEW.IDEsame
                        )
                    LOOP
                        PERFORM * FROM uni.carriera_studente WHERE IDInsegnamento=insegnamento_richiesto;
                        IF FOUND THEN 
                        ELSE
                            RAISE EXCEPTION 'Lo studente non ha superato gli esami propedeutici richiesti';
                        END IF;
                    END LOOP;
                END IF;
            ELSE 
                RAISE EXCEPTION 'L insegnamento dell esame non è previsto per il corso di laurea dello studente';
            END IF;
    END CASE;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER check_insertesito_trigger BEFORE INSERT ON uni.esito 
FOR EACH ROW EXECUTE FUNCTION uni.check_insertesito();

-- check_updateesito: alla modifica dell'esito, verifico che lo stato non sia ancora Iscritto e verifico la correttezza delle informazioni
-- associate al nuovo stato tenendo in considerazione anche lo stato precedente
CREATE OR REPLACE FUNCTION uni.check_updateesito()
RETURNS TRIGGER AS $$
DECLARE 
    corso uni.media_studente.IDCorso%TYPE;
    info_esame uni.esame%ROWTYPE;
BEGIN 
    CASE NEW.stato
        WHEN 'Iscritto' THEN
            RAISE EXCEPTION 'Lo stato non puo essere ancora Iscritto';
        
        WHEN 'Ritirato' THEN 
            IF OLD.stato!='Iscritto' THEN
                RAISE EXCEPTION 'Si puo registrare il ritiro di uno studente solo se questo era iscritto';
            END IF;
            IF NOT (NEW.voto=NULL AND NEW.lode=NULL) THEN
                RAISE EXCEPTION 'SE lo studente si è ritirato, il voto e la lode devono essere NULL';
            END IF;

        WHEN 'Bocciato' THEN
            IF OLD.stato!='Iscritto' THEN
                RAISE EXCEPTION 'Si puo bocciare uno studente solo se questo era iscritto';
            END IF;
            IF NEW.voto>=18 THEN
                RAISE EXCEPTION 'SE lo studente è stato bocciato, il voto deve essere inferiore di 18';
            END IF;
            IF NEW.lode=True THEN
                RAISE EXCEPTION 'SE lo studente è stato bocciato, la lode deve essere false';
            END IF;
        
        WHEN 'In attesa di accettazione' THEN
            IF OLD.stato!='Iscritto' THEN
                RAISE EXCEPTION 'Si puo registrare un esito positivo di uno studente solo se questo era iscritto';
            END IF;
            IF NEW.voto<18 THEN
                RAISE EXCEPTION 'SE lo studente è stato prmosso, il voto deve essere superiore di 18';
            END IF;
            IF NEW.lode=True AND NEW.voto!=30 THEN
                RAISE EXCEPTION 'SE lo studente promosso ha ottenuto la lode, il voto deve essere 30';
            END IF;
        
        WHEN 'Accettato' THEN
            -- UPDATE con uni.accetta_esito(...)
            IF OLD.stato='In attesa di accettazione' THEN
                -- si sta accettando un esito positivo: bisogna aggiornare la vista che contiene la carriera dello studente
                -- recupero la data, l'insegnamento, il docente responsabile e il corso di laurea dello studente
                SELECT * INTO info_esame FROM uni.esame WHERE IDEsame=NEW.IDEsame;
                SELECT IDCorso INTO corso FROM uni.studente WHERE matricola=NEW.matricola;
                INSERT INTO uni.carriera_studente(matricola, IDCorso, IDInsegnamento, IDDocente, voto, lode, data)
                VALUES (NEW.matricola, corso, info_esame.IDInsegnamento, info_esame.IDDocente, NEW.voto, new.lode, info_esame.data);
            ELSE
                RAISE EXCEPTION 'L esito non è in attesa di accettazione';
            END IF;
        WHEN 'Rifiutato' THEN
            IF OLD.stato!='In attesa di accettazione' THEN
                RAISE EXCEPTION 'L esito non è in attesa di accettazione';
            END IF;
    END CASE;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER check_updateesito_trigger BEFORE UPDATE ON uni.esito 
FOR EACH ROW EXECUTE FUNCTION uni.check_updateesito();


-- check_deleteesito_trigger: all'eliminazione di un esito, verifico che questo abbia uno stato di iscritto e una data successiva a quella odierna
CREATE OR REPLACE FUNCTION uni.check_deleteesito()
RETURNS TRIGGER AS $$
DECLARE 
    info_esame uni.esame%ROWTYPE;
BEGIN 
    PERFORM * FROM uni.studente WHERE matricola=OLD.matricola;
    IF NOT FOUND THEN
        RETURN OLD;
    END IF;
    CASE OLD.stato
        WHEN 'Ritirato', 'Accettato', 'Rifiutato', 'Bocciato', 'In attesa di accettazione' THEN
                RAISE EXCEPTION 'Si puo cancellare un esito SOLO se questo ha uno stato iscritto';

        WHEN 'Iscritto' THEN
            SELECT * INTO info_esame FROM uni.get_esame(OLD.IDEsame);
            IF info_esame.data<CURRENT_DATE THEN
                RAISE EXCEPTION 'L esame si è già tenuto, non è possibile cancellare l iscrizione';
            END IF;
    END CASE;
    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER check_deleteesito_trigger BEFORE DELETE ON uni.esito 
FOR EACH ROW EXECUTE FUNCTION uni.check_deleteesito();


-- num_responsabile: all'inserimento o modifica di un insegnamento controlla che il nuovo docente sia responsabile di al massimo 3 insegnamenti
-- e che il precedente responsabile dopo la modifica sia ancora responsabile di almeno 1 insegnamento
CREATE OR REPLACE FUNCTION uni.num_responsabile()
RETURNS TRIGGER AS $$
DECLARE 
    count_old_responsabile integer;
    count_new_responsabile integer;
BEGIN 
    IF NEW.IDDocente=OLD.IDDocente THEN
        -- non si sta modificando il docente responsabile
        RETURN NEW;
    ELSE
        -- recupero il numero di insegnamenti di cui è responsabile il docente che verra sostituito
        SELECT count(*) INTO count_old_responsabile FROM uni.insegnamento
        WHERE IDDocente=OLD.IDDocente;
        IF count_old_responsabile=1  THEN 
            RAISE EXCEPTION 'Il docente non puo essere sostituito come responsabile, è l unico insegnamento di cui è responsabile';
        END IF;

        -- SE si sta assegnado l'insegnamento a qualcuno, recupero il numero di insegnamenti di cui è responsabile il docente che sara il nuovo responsabile
        IF NEW.IDDocente IS NULL THEN
            RETURN NEW;
        END IF;
        SELECT count(*) INTO count_new_responsabile FROM uni.insegnamento
        WHERE IDDocente=NEW.IDDocente;
        IF count_new_responsabile=3  THEN 
            RAISE EXCEPTION 'Il docente non puo essere responsabile del corso, è gia responsabile di altri 3 corsi';
        END IF;
        RETURN NEW;
    END IF;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER num_responsabile_trigger BEFORE UPDATE OR INSERT ON uni.insegnamento 
FOR EACH ROW EXECUTE FUNCTION uni.num_responsabile();


-- move_to_storico_insegnamento: al cambiamento del docente responsabile, sposta i record associati in storico_insegnamento
CREATE OR REPLACE FUNCTION uni.move_to_storico_insegnamento()
RETURNS TRIGGER AS $$
DECLARE 
    sessione uni.esame%ROWTYPE;
    esame uni.esito%ROWTYPE;
    corso uni.corso_laurea.IDCorso%TYPE;
    the_data uni.esame.data%TYPE;
BEGIN 
    IF OLD.IDDocente IS NULL THEN
        -- inizializzazione del docente responsabile --> non ci sono entry con IDDocente = NULL nelle altre tabelle
        RETURN NEW;
    END IF;
    IF OLD.IDDocente=NEW.IDDocente THEN
        RAISE EXCEPTION 'Il nuovo docente responsabile non puo essere quello precedente';
    END IF;
    -- registro l insegnamento nello storico
    INSERT INTO uni.storico_insegnamento(IDDocente, IDInsegnamento, nome, crediti, annoInizio)
        VALUES (OLD.IDDocente, OLD.IDInsegnamento, OLD.nome, OLD.crediti, OLD.annoAttivazione);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER move_to_storico_insegnamento_trigger BEFORE UPDATE ON uni.insegnamento 
FOR EACH ROW EXECUTE FUNCTION uni.move_to_storico_insegnamento();


-- move_to_storico_studente: all'eliminazione di un record in studente, sposta i record associati in storico_studente e storico_esame
CREATE OR REPLACE FUNCTION uni.move_to_storico_studente()
RETURNS TRIGGER AS $$
DECLARE 
    exam uni.esito%ROWTYPE;
    doc uni.docente.IDDocente%TYPE;
    row_esame uni.esame%ROWTYPE;
BEGIN 
    -- registro lo studente nello storico
    INSERT INTO uni.storico_studente(matricola, IDCorso, dataImmatricolazione)
        VALUES (OLD.matricola, OLD.IDCorso, OLD.dataImmatricolazione);
    FOR exam IN -- per ogni esame che ha sostenuto
        SELECT * FROM uni.esito 
        WHERE matricola=OLD.matricola
    LOOP
        -- recupero l insegnamento, il docente e la data dell'esame fissato
        SELECT * INTO row_esame FROM uni.esame WHERE IDEsame=exam.IDEsame;
        SELECT IDDocente INTO doc FROM uni.insegnamento WHERE IDInsegnamento=row_esame.IDInsegnamento;

        INSERT INTO uni.storico_esame(matricola, IDCorso, IDInsegnamento, IDDocente, voto, stato, lode, data)
        VALUES (OLD.matricola, OLD.IDCorso, row_esame.IDInsegnamento, doc, exam.voto, exam.stato, exam.lode, row_esame.data);
        
        -- elimino dagli esiti, l'esito appena spostato nello storico
        DELETE FROM uni.esito 
        WHERE IDEsame=exam.IDEsame AND matricola=exam.matricola;
    END LOOP;
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER move_to_storico_studente_trigger AFTER DELETE ON uni.studente 
FOR EACH ROW EXECUTE FUNCTION uni.move_to_storico_studente();


-- check_registrazione_laurea: all'inserimento di un iscrizione ad una sessione di laurea in laurea, controlla 
-- SE lo studente ha superato tutti gli insegnamenti del corso e SE li ha passati, il voto di laurea, l'incremento e la lode devono essere NULL
CREATE OR REPLACE FUNCTION uni.check_registrazione_laurea()
RETURNS TRIGGER AS $$
BEGIN 
    PERFORM * FROM (
        SELECT IDInsegnamento FROM uni.manifesto_insegnamenti WHERE IDCorso=NEW.IDCorso
        EXCEPT
        SELECT IDInsegnamento FROM uni.carriera_studente WHERE IDCorso=NEW.IDCorso AND matricola=NEW.matricola
    ) as iddifference;
    IF FOUND THEN
        IF NOT (voto=NULL AND lode=NULL AND incrememnto=NULL) THEN
            RAISE 'All inserimento, voto, lode e incremento devono essere null';
        END IF;
        RAISE EXCEPTION 'Lo studente non ha passato tutti gli insegnamenti del corso';
    ELSE 
        RETURN NEW;
    END IF;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER check_registrazione_laurea_trigger BEFORE INSERT ON uni.laurea 
FOR EACH ROW EXECUTE FUNCTION uni.check_registrazione_laurea();


-- move_to_storico: alla modifica del voto di laurea di uno studente, elimino tale studente in quanto è diventato un ex-studente
CREATE OR REPLACE FUNCTION uni.move_to_storico()
RETURNS TRIGGER AS $$
BEGIN 
    IF NEW.voto IS NOT NULL THEN  
        CALL uni.delete_studente(OLD.matricola, OLD.IDCorso);
        RETURN NEW;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER move_to_storico_trigger BEFORE UPDATE ON uni.laurea 
FOR EACH ROW EXECUTE FUNCTION uni.move_to_storico();


-- hash: all'inserimento o alla modifica di un utente, applico la funzione hash md5 alla password.
CREATE OR REPLACE FUNCTION uni.hash()
RETURNS TRIGGER AS $$
BEGIN 
    NEW.password = md5(NEW.password);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER hash_trigger BEFORE INSERT OR UPDATE ON uni.utente 
FOR EACH ROW EXECUTE FUNCTION uni.hash();

-- Inserimento base di un utente segreteria
CALL uni.insert_segreteria('admin', '_', 'admin@uni.it', 'Admin', '0123456789');