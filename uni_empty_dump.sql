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
    VALUE = 'In attesa' OR
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
    nome varchar(50) NOT NULL,
    cognome varchar(50) NOT NULL,
    email varchar(100) NOT NULL UNIQUE,
    password varchar(32) NOT NULL,
    cellulare varchar(20) NOT NULL
);

CREATE TABLE uni.corso_laurea(
    IDCorso varchar(20) PRIMARY KEY,
    nome varchar(100) NOT NULL,
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
    IDDocente integer REFERENCES uni.docente(IDDocente) DEFAULT NULL,
    nome varchar(200) NOT NULL,
    descrizione text,
    crediti integer NOT NULL CHECK(crediti>0),
    annoAttivazione integer NOT NULL CHECK(annoAttivazione>0)
);

CREATE TABLE uni.matricola(
    matricola char(6) PRIMARY KEY,
    IDUtente integer REFERENCES uni.utente(IDUtente) NOT NULL UNIQUE,
    codiceFiscale varchar(16) NOT NULL UNIQUE
);

CREATE TABLE uni.studente(
    IDCorso varchar(20) REFERENCES uni.corso_laurea(IDCorso),
    matricola char(6) REFERENCES uni.matricola(matricola) UNIQUE,
    dataImmatricolazione date NOT NULL DEFAULT CURRENT_DATE,
    PRIMARY KEY (IDCorso, matricola)
);

CREATE TABLE uni.sessione_laurea(
    data date,
    IDCorso varchar(20) REFERENCES uni.corso_laurea(IDCorso),
    creditiLaurea integer NOT NULL CHECK(creditiLaurea>0),
    PRIMARY KEY(data, IDCorso)
);

CREATE TABLE uni.laurea(
    matricola char(6) REFERENCES uni.matricola(matricola),
    data date,
    IDCorso varchar(20),
    voto uni.votoLaurea DEFAULT NULL,
    incrementoVoto integer DEFAULT NULL CHECK (incrementoVoto>0) , 
    lode boolean DEFAULT NULL,
	FOREIGN KEY (data, IDCorso) REFERENCES uni.sessione_laurea(data, IDCorso),
	PRIMARY KEY (matricola, data, IDCorso)
);

CREATE TABLE uni.esame(
    IDEsame SERIAL PRIMARY KEY,
    IDDocente integer REFERENCES uni.docente(IDDocente) NOT NULL,
    IDInsegnamento integer REFERENCES uni.insegnamento(IDInsegnamento) NOT NULL,
    data date NOT NULL
);

CREATE TABLE uni.storico_insegnamento(
    IDDocente integer REFERENCES uni.docente(IDDocente),
    IDInsegnamento integer,
    nome varchar(200) NOT NULL,
    crediti integer NOT NULL CHECK(crediti>0), 
    annoInizio integer NOT NULL CHECK(annoInizio>0),
    annoFine integer NOT NULL DEFAULT CAST(date_part('year', CURRENT_DATE) as integer) CHECK(annoFine>0),
    PRIMARY KEY(IDDocente, IDInsegnamento)
);

CREATE TABLE uni.storico_studente(
    matricola char(6) REFERENCES uni.matricola(matricola),
    IDCorso varchar(20) REFERENCES uni.corso_laurea(IDCorso),
    dataImmatricolazione date NOT NULL,
    dataRimozione date NOT NULL DEFAULT CURRENT_DATE,
    PRIMARY KEY(matricola, IDCorso)
);

CREATE TABLE uni.storico_esame(
    IDStorico SERIAL PRIMARY KEY,
    matricola char(6) REFERENCES uni.matricola(matricola) NOT NULL,
    IDCorso varchar(20) REFERENCES uni.corso_laurea(IDCorso) NOT NULL,
    IDInsegnamento integer NOT NULL,
    IDDocente integer NOT NULL,
    voto uni.voto,
    stato uni.statoesito NOT NULL,
    lode boolean,
    data date NOT NULL,
	FOREIGN KEY (IDInsegnamento, IDDocente) REFERENCES uni.storico_insegnamento(IDInsegnamento, IDDocente)
);

CREATE TABLE uni.manifesto_insegnamenti(
    IDInsegnamento integer REFERENCES uni.insegnamento(IDInsegnamento),
    IDCorso varchar(20) REFERENCES uni.corso_laurea(IDCorso),
    anno uni.annoCorso NOT NULL CHECK(anno>0),
    PRIMARY KEY (IDInsegnamento, IDCorso) 
);

CREATE TABLE uni.manifesto_passato(
    IDInsegnamento integer,
    IDDocente integer,
    IDCorso varchar(20) REFERENCES uni.corso_laurea(IDCorso),
    FOREIGN KEY (IDInsegnamento, IDDocente) REFERENCES uni.storico_insegnamento(IDInsegnamento, IDDocente),
    PRIMARY KEY (IDInsegnamento, IDCorso, IDDocente) 
);

CREATE TABLE uni.esito(
    matricola char(6) REFERENCES uni.matricola(matricola),
    IDEsame integer REFERENCES uni.esame(IDEsame),
    voto uni.voto DEFAULT NULL,
    stato uni.statoEsito DEFAULT 'In attesa',
    lode boolean DEFAULT NULL,
    PRIMARY KEY (matricola, IDEsame)
);

CREATE TABLE uni.propedeuticita(
    insegnamento integer REFERENCES uni.insegnamento(IDInsegnamento),
    insegnamentoRichiesto integer REFERENCES uni.insegnamento(IDInsegnamento),
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
    PRIMARY KEY (matricola, IDCorso, IDInsegnamento)
);

CREATE OR REPLACE VIEW uni.view_carriera AS 
    SELECT matricola, IDCorso, c.IDInsegnamento, i.nome, c.IDDocente, data, voto, lode 
    FROM uni.carriera_studente as c INNER JOIN uni.insegnamento as i ON c.IDInsegnamento=i.IDInsegnamento
;

-- Creazione delle funzioni
-- insert_corso_laurea: 
-- il corso di laurea è di default attivo
CREATE OR REPLACE PROCEDURE uni.insert_corso_laurea(
    IDCorso varchar(20), nome varchar(100), anniTotali uni.tipoLaurea, valoreLode integer
)
AS $$
BEGIN 
    INSERT INTO uni.corso_laurea(IDCorso, nome, anniTotali, valoreLode, attivo)
        VALUES (IDCorso, nome, anniTotali, valoreLode, True);
END;
$$ LANGUAGE plpgsql ;

-- insert_insegnamento
CREATE OR REPLACE PROCEDURE uni.insert_insegnamento(
    IDDocente integer, nome varchar(200), descrizione text, crediti integer, annoAttivazione integer
)
AS $$
BEGIN 
    INSERT INTO uni.insegnamento(IDDocente, nome, descrizione, crediti, annoAttivazione)
        VALUES (IDDocente, nome, descrizione, crediti, annoAttivazione);
END;
$$ LANGUAGE plpgsql;

-- cambia_responsabile
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


-- insert_utente
CREATE OR REPLACE PROCEDURE uni.insert_utente(ruolo uni.ruolo, nome varchar(50), cognome varchar(50), new_email varchar(100), password varchar(32), cellulare varchar(20)) 
AS $$
BEGIN 
    INSERT INTO uni.utente(ruolo, nome, cognome, email, password, cellulare) 
        VALUES (ruolo, nome, cognome, new_email, md5(password), cellulare);
END;
$$ LANGUAGE plpgsql;

-- insert_docente: Inserisce un nuovo docente 
-- INPUT: 
-- - nome NOT NULL
-- - cognome NOT NULL
-- - email NOT NULL e UNIQUE 
-- - password NOT NULL
-- - cellulare NOT NULL
-- - inizioRapporto NOT NULL
-- - fineRapporto eventualmente NULL
-- - IDCorso NOT NULL
-- OUTPUT: ---
CREATE OR REPLACE PROCEDURE uni.insert_docente(
    nome varchar(50), cognome varchar(50), new_email varchar(100), password varchar(32), cellulare varchar(20), 
    inizioRapporto date, fineRapporto date,
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
        VALUES (newId, inizioRapporto, fineRapporto);
    CALL uni.cambia_responsabile(insegnamentoToUpdate, newId);
END;
$$ LANGUAGE plpgsql;

-- new_matricola: restituisce la nuova matricola da assegnare a uno studente
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

-- modifica_utente: 
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
        UPDATE uni.utente SET email=new_email, password=md5(new_password), cellulare=new_cellulare
        WHERE IDUtente=the_IDUtente;
    END IF; 
    
END;
$$ LANGUAGE plpgsql;

-- cambio_corso_laurea:
CREATE OR REPLACE PROCEDURE uni.cambio_corso_laurea(
    the_matricola char(6), the_IDCorso varchar(20), dataImmatricolazione date, new_email varchar(100), password varchar(32), cellulare varchar(20)
)
AS $$
DECLARE 
    the_IDUtente uni.utente.IDUtente%TYPE;
    insegnamento uni.manifesto_insegnamenti%ROWTYPE;
    esame uni.view_carriera%ROWTYPE;
BEGIN
    INSERT INTO uni.studente(matricola, IDCorso, dataImmatricolazione)
    VALUES (the_matricola, the_IDCorso, dataImmatricolazione);
    
    SELECT u.IDUtente INTO the_IDUtente FROM uni.utente as u INNER JOIN uni.matricola as m 
    ON m.IDUtente=u.IDUtente AND m.matricola=the_matricola;
    CALL uni.modifica_utente (the_IDUtente, new_email, password, cellulare);

    FOR insegnamento IN 
        SELECT * FROM uni.manifesto_insegnamenti
        WHERE IDCorso=the_IDCorso
    LOOP
        FOR esame IN 
            SELECT * FROM uni.view_carriera WHERE matricola=the_matricola AND IDInsegnamento=insegnamento.IDInsegnamento 
        LOOP
            INSERT INTO uni.carriera_studente(matricola, IDCorso, IDInsegnamento, IDDocente, voto, lode, data)
            VALUES (the_matricola, the_IDCorso, esame.IDInsegnamento, esame.IDDocente, esame.voto, esame.lode, esame.data);
        END LOOP;
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- insert_studente: 
CREATE OR REPLACE PROCEDURE uni.insert_studente(
    nome varchar(50), cognome varchar(50), new_email varchar(100), password varchar(32), cellulare varchar(20), 
    the_codiceFiscale varchar(16), IDCorso varchar(20), dataImmatricolazione date
) 
AS $$
DECLARE 
    old_matricola uni.matricola.matricola%TYPE;
    newId uni.utente.IDUtente%TYPE;
    newMatricola uni.matricola.matricola%TYPE;
BEGIN 
    SELECT matricola INTO old_matricola FROM uni.matricola WHERE codiceFiscale=the_codiceFiscale;
    IF old_matricola IS NULL THEN
        CALL uni.insert_utente('Studente', nome, cognome, new_email, password, cellulare);
        SELECT * INTO newMatricola FROM uni.new_matricola();
        SELECT u.IDUtente INTO newId FROM uni.utente as u WHERE u.email=new_email;
        INSERT INTO uni.matricola(IDUtente, matricola, codiceFiscale) 
            VALUES (newId, newMatricola, the_codiceFiscale);

	    INSERT INTO uni.studente(matricola, dataImmatricolazione, IDCorso) 
            VALUES (newMatricola, dataImmatricolazione, IDCorso);
    ELSE
        CALL uni.cambio_corso_laurea(old_matricola, IDCorso, dataImmatricolazione, new_email, password, cellulare);
        RETURN;
    END IF;
    
END;
$$ LANGUAGE plpgsql;



-- insert_segreteria: 
CREATE OR REPLACE PROCEDURE uni.insert_segreteria(nome varchar(50), cognome varchar(50), new_email varchar(100), password varchar(32), cellulare varchar(20)) 
AS $$
BEGIN
    CALL uni.insert_utente('Segreteria', nome, cognome, new_email, password, cellulare);
END; 
$$ LANGUAGE plpgsql;


-- insert_esame: 
CREATE OR REPLACE PROCEDURE uni.insert_esame(
    IDDocente integer, IDInsegnamento integer, data date
)
AS $$
BEGIN 
    -- IF data<CURRENT_DATE THEN
       -- RAISE EXCEPTION 'La data non puo essere precedente a quella di oggi';
    -- END IF;
    INSERT INTO uni.esame(IDDocente, IDInsegnamento, data)
        VALUES (IDDocente, IDInsegnamento, data);
END;
$$ LANGUAGE plpgsql;

-- insert_propedeuticita
CREATE OR REPLACE PROCEDURE uni.insert_propedeuticita(
    IDInsegnamento1 integer, IDInsegnamento2 integer
)
AS $$
BEGIN 
    IF IDInsegnamento1=IDInsegnamento2 THEN
        RAISE EXCEPTION 'I due insegnamenti non possono essere lo stesso';
    END IF;
    INSERT INTO uni.propedeuticita(insegnamento, insegnamentoRichiesto)
        VALUES (IDInsegnamento1, IDInsegnamento2);
END;
$$ LANGUAGE plpgsql;

-- iscrizione_esame:
CREATE OR REPLACE PROCEDURE uni.iscrizione_esame(
    matricola char(6), IDEsame integer
)
AS $$
BEGIN 
    INSERT INTO uni.esito(matricola, IDEsame, stato)
        VALUES (matricola, IDEsame, 'In attesa');
END;
$$ LANGUAGE plpgsql;

-- insert_manifesto
CREATE OR REPLACE PROCEDURE uni.insert_manifesto(
    IDInsegnamento integer, IDCorso varchar(20), anno uni.annoCorso
)
AS $$
BEGIN 
    INSERT INTO uni.manifesto_insegnamenti(IDInsegnamento, IDCorso, anno)
        VALUES (IDInsegnamento, IDCorso, anno);
END;
$$ LANGUAGE plpgsql;

-- insert_sessione_laurea
CREATE OR REPLACE PROCEDURE uni.insert_sessione_laurea(
    data date, IDCorso varchar(20)
)
AS $$
BEGIN 
    INSERT INTO uni.sessione_laurea(data, IDCorso)
        VALUES (data, IDCorso);
END;
$$ LANGUAGE plpgsql;

-- iscrizione_laurea
CREATE OR REPLACE PROCEDURE uni.iscrizione_esame(
    matricola char(6), data date, IDCorso varchar(20)
)
AS $$
BEGIN 
    INSERT INTO uni.laurea(matricola, data, IDCorso)
        VALUES (matricola, data, IDCorso);
END;
$$ LANGUAGE plpgsql;

-- registrazione_esito_esame
CREATE OR REPLACE PROCEDURE uni.registrazione_esito_esame(
    the_matricola char(6), the_IDEsame integer, the_voto uni.voto, the_lode boolean
)
AS $$
DECLARE newStato uni.esito.stato%TYPE;
BEGIN 
    PERFORM * FROM uni.esito WHERE matricola=the_matricola AND IDEsame=the_IDEsame AND voto>=0;
    IF FOUND THEN
        RAISE EXCEPTION 'Esito gia registrato';
    END IF;
    IF the_voto<18 THEN 
        newStato='Bocciato';
    ELSE
        newStato='In attesa di accettazione';
    END IF;
    IF the_voto!=30 AND the_lode THEN
        RAISE EXCEPTION 'La lode puo essere assegnata solo ad un voto 30';
    END IF;
    UPDATE uni.esito SET voto=the_voto, lode=the_lode, stato=newStato
    WHERE matricola=the_matricola AND IDEsame=the_IDEsame;
END;
$$ LANGUAGE plpgsql;

-- accetta_esito
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

-- delete_studente
CREATE OR REPLACE PROCEDURE uni.delete_studente(
    the_matricola char(6), the_IDCorso varchar(20)
)
AS $$
BEGIN 
    DELETE FROM uni.studente WHERE matricola=the_matricola AND IDCorso=the_IDCorso;
END;
$$ LANGUAGE plpgsql;

-- calcola_voto_laurea
CREATE OR REPLACE FUNCTION uni.calcola_voto_laurea(
    the_matricola char(6), incremento integer
)
RETURNS decimal AS $$
DECLARE the_media decimal; voto decimal;
BEGIN 
    SELECT media INTO the_media FROM uni.media_studente WHERE matricola=matricola;
    voto:=(the_media*110/30)+incremento;
    IF VOTO>=110 THEN
        RETURN 110;
    ELSE 
        RETURN ROUND(voto);
    END IF;
END;
$$ LANGUAGE plpgsql;

-- registrazion_esito_laurea
CREATE OR REPLACE PROCEDURE uni.registrazione_esito_laurea(
    the_matricola char(6), the_IDCorso integer, the_data date, incremento integer, lode boolean
)
AS $$
DECLARE votoFinale decimal;
BEGIN 
    SELECT * INTO votoFinale FROM calcola_voto_laurea(the_matricola, incremento);
    UPDATE uni.laurea SET incrementoVoto=incremento, lode=lode
    WHERE matricola=the_matricola AND IDCorso=the_IDCorso AND data=the_data;
END;
$$ LANGUAGE plpgsql;

-- get_id_ruolo: 
CREATE OR REPLACE FUNCTION uni.get_id_ruolo(the_email varchar(100), the_password varchar(32))
RETURNS SETOF uni.utente AS $$
DECLARE ut uni.utente%ROWTYPE;
BEGIN
    SELECT u.IDUtente, u.ruolo INTO ut
    FROM uni.utente as u 
    WHERE u.email=the_email AND u.password=the_password;
	RETURN NEXT ut;
END;
$$ LANGUAGE plpgsql;

-- get_docente: 


-- get_studente: restituisce le informazioni biografiche di uno studente
CREATE OR REPLACE FUNCTION uni.get_studente_bio(corso varchar(20), matricola varchar(6))
RETURNS SETOF uni.studente_bio AS $$
DECLARE stud uni.studente_bio%ROWTYPE;
BEGIN
    SELECT u.* INTO stud
    FROM uni.studente_bio as u 
    WHERE corso=u.corso AND matricola=u.matricola;
	RETURN NEXT stud;
END;
$$ LANGUAGE plpgsql;



-- creazione dei trigger
-- insert_media: 
CREATE OR REPLACE FUNCTION uni.insert_media()
RETURNS TRIGGER AS $$
BEGIN 
    INSERT INTO uni.media_studente(matricola, IDCorso)
    VALUES (NEW.matricola, NEW.IDCorso);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- insert_media_trigger: 
CREATE OR REPLACE TRIGGER insert_media_trigger AFTER INSERT ON uni.studente 
FOR EACH ROW EXECUTE FUNCTION uni.insert_media();

-- update_media
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
        SELECT * INTO old_stats FROM uni.media_studente WHERE matricola=NEW.matricola AND IDCorso=NEW.IDCorso;
        SELECT crediti INTO num_crediti FROM uni.insegnamento WHERE IDInsegnamento=NEW.IDInsegnamento;

        IF NEW.lode THEN
            SELECT valoreLode INTO valLode FROM uni.corso_laurea WHERE IDCorso=NEW.IDCorso;
            new_media:=(old_stats.media*old_stats.crediti+num_crediti*valLode)/(old_stats.crediti+num_crediti);
        ELSE 
            new_media:=(old_stats.media*old_stats.crediti+num_crediti*NEW.voto)/(old_stats.crediti+num_crediti);
        END IF;

        UPDATE uni.media_studente SET media=new_media, crediti=old_stats.crediti+num_crediti
        WHERE matricola=NEW.matricola AND IDCorso=NEW.IDCorso;
        RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER update_media_trigger AFTER INSERT ON uni.carriera_studente 
FOR EACH ROW EXECUTE FUNCTION uni.update_media();

-- check_esito:
CREATE OR REPLACE FUNCTION uni.check_esito()
RETURNS TRIGGER AS $$
DECLARE 
    corso uni.media_studente.IDCorso%TYPE;
    info_esame uni.esame%ROWTYPE;
BEGIN 
    IF NEW.stato='Accettato' THEN
        SELECT * INTO info_esame FROM uni.esame WHERE IDEsame=NEW.IDEsame;
        SELECT IDCorso INTO corso FROM uni.studente WHERE matricola=NEW.matricola;
        INSERT INTO uni.carriera_studente(matricola, IDCorso, IDInsegnamento, IDDocente, voto, lode, data)
        VALUES (NEW.matricola, corso, info_esame.IDInsegnamento, info_esame.IDDocente, NEW.voto, new.lode, info_esame.data);
    ELSE   
        IF NEW.stato='In attesa' THEN
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
                -- controlla che lo studente non abbia gia dato accettato un esito positivo per quell esame
                PERFORM * FROM uni.view_carriera WHERE matricola=NEW.matricola AND IDInsegnamento=(
                    SELECT IDInsegnamento FROM uni.esame WHERE IDEsame = NEW.IDEsame
                );
                IF FOUND THEN 
                    RAISE EXCEPTION 'Lo studente ha gia accettato un esito positivo per l insegnamento';
                END IF;
            ELSE 
                RAISE EXCEPTION 'L insegnamento dell esame non è previsto per il corso di laurea dello studente';
            END IF;
        END IF;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- check_esito_trigger: 
CREATE OR REPLACE TRIGGER check_esito_trigger BEFORE UPDATE ON uni.esito 
FOR EACH ROW EXECUTE FUNCTION uni.check_esito();

-- num_responsabile
CREATE OR REPLACE FUNCTION uni.num_responsabile()
RETURNS TRIGGER AS $$
DECLARE 
    count_old_responsabile integer;
    count_new_responsabile integer;
BEGIN 
    IF NEW.IDDocente=OLD.IDDocente THEN
        RETURN NEW;
    ELSE
        SELECT count(*) INTO count_old_responsabile FROM uni.insegnamento
        WHERE IDDocente=OLD.IDDocente;
        IF count_old_responsabile=1  THEN 
            RAISE EXCEPTION 'Il docente non puo essere sostituito come responsabile, è l unico insegnamento di cui è responsabile';
        END IF;
        SELECT count(*) INTO count_old_responsabile FROM uni.insegnamento
        WHERE IDDocente=OLD.IDDocente;
        IF count_old_responsabile=1  THEN 
            RAISE EXCEPTION 'Il docente non puo essere responsabile del corso, è gia responsabile di altri 3 corsi';
        END IF;
        RETURN NEW;
    END IF;
END;
$$ LANGUAGE plpgsql;

-- num_responsabile_trigger: verifica che il docente sia responsabile di almeno 1 insegnamento 
CREATE OR REPLACE TRIGGER update_media_trigger BEFORE UPDATE ON uni.insegnamento 
FOR EACH ROW EXECUTE FUNCTION uni.num_responsabile();

-- move_to_storico_insegnamento: al cambiamento del docente responsabile, sposta i record associati in storico_insegnamento e storico_esame 
CREATE OR REPLACE FUNCTION uni.move_to_storico_insegnamento()
RETURNS TRIGGER AS $$
DECLARE 
    sessione uni.esame%ROWTYPE;
    esame uni.esito%ROWTYPE;
    corso uni.corso_laurea.IDCorso%TYPE;
    the_data uni.esame.data%TYPE;
BEGIN 
    IF OLD.IDDocente IS NULL THEN
        RETURN NEW;
    END IF;
    INSERT INTO uni.storico_insegnamento(IDDocente, IDInsegnamento, nome, crediti, annoInizio)
        VALUES (OLD.IDDocente, OLD.IDInsegnamento, OLD.nome, OLD.crediti, OLD.annoAttivazione);
    FOR sessione IN 
        SELECT * FROM uni.esame 
        WHERE IDInsegnamento=OLD.IDInsegnamento AND IDDocente=OLD.IDDocente
    LOOP 
        FOR esame IN
            SELECT * FROM uni.esito
            WHERE IDEsame=sessione.IDEsame
        LOOP
            SELECT * INTO corso FROM uni.studente WHERE matricola=esame.matricola;
            INSERT INTO uni.storico_esame(matricola, IDCorso, IDInsegnamento, IDDocente, voto, stato, lode, data)
            VALUES (esame.matricola, corso, sessione.IDInsegnamento, sessione.IDDocente, esame.voto, esame.stato, esame.lode, sessione.data);
        END LOOP;
    END LOOP;
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER move_to_storico_insegnamento_trigger BEFORE UPDATE ON uni.insegnamento 
FOR EACH ROW EXECUTE FUNCTION uni.move_to_storico_insegnamento();

-- move_to_storico_studente
CREATE OR REPLACE FUNCTION uni.move_to_storico_studente()
RETURNS TRIGGER AS $$
DECLARE 
    exam uni.esito%ROWTYPE;
    doc uni.docente.IDDocente%TYPE;
    row_esame uni.esame%ROWTYPE;
BEGIN 
    INSERT INTO uni.storico_studente(matricola, IDCorso, dataImmatricolazione)
        VALUES (OLD.matricola, OLD.IDCorso, OLD.dataImmatricolazione);
    FOR exam IN 
        SELECT * FROM uni.esito 
        WHERE matricola=OLD.matricola
    LOOP
        SELECT * INTO row_esame FROM uni.esame WHERE IDEsame=exam.IDEsame;
        SELECT IDDocente INTO doc FROM uni.insegnamento WHERE IDInsegnamento=row_esame.IDInsegnamento;
        IF doc=row_esame.IDInsegnamento THEN
            INSERT INTO uni.storico_esame(matricola, IDCorso, IDInsegnamento, IDDocente, voto, stato, lode, data)
            VALUES (OLD.matricola, OLD.IDCorso, row_esame.IDInsegnamento, doc, exam.voto, exam.stato, exam.lode, row_esame.data);
        END IF;
    END LOOP;
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

-- move_to_storico_studente_trigger all'eliminazione di un record in studente, sposta i record associati in storico_studente e storico_esame
CREATE OR REPLACE TRIGGER move_to_storico_studente_trigger AFTER DELETE ON uni.studente 
FOR EACH ROW EXECUTE FUNCTION uni.move_to_storico_studente();


-- Inserimento base di un utente segreteria
CALL uni.insert_segreteria('admin', '_', 'admin', 'admin', '0');


