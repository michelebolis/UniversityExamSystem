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
    votoProva uni.voto DEFAULT NULL, 
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
    annoFine integer NOT NULL CHECK(annoFine>0),
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


-- Creazione delle funzioni
-- insert_corso_laurea: 
-- il corso di laurea è di default attivo
CREATE OR REPLACE FUNCTION uni.insert_corso_laurea(
    IDCorso varchar(20), nome varchar(100), anniTotali tipoLaurea, valoreLode integer
)
RETURNS VOID AS $$
BEGIN 
    INSERT INTO uni.corso_laurea(IDCorso, nome, anniTotali, valoreLode, attivo)
        VALUES (IDCorso, nome, anniTotali, valoreLode, True);
END;
$$ LANGUAGE plpgsql;

-- insert_insegnamento
CREATE OR REPLACE FUNCTION uni.insert_insegnamento(
    IDDocente integer, nome varchar(200), descrizione text, crediti integer, annoAttivazione integer
)
RETURNS VOID AS $$
BEGIN 
    INSERT INTO uni.insegnamento(IDDocente, nome, descrizione, crediti, annoAttivazione)
        VALUES (IDDocente, nome, descrizione, crediti, annoAttivazione);
END;
$$ LANGUAGE plpgsql;

-- cambia_responsabile
CREATE OR REPLACE FUNCTION uni.cambia_responsabile(
    insegnamentoToUpdate integer, newDocente integer
)
RETURNS VOID AS $$
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
CREATE OR REPLACE FUNCTION uni.insert_utente(ruolo ruolo, nome varchar(50), cognome varchar(50), new_email varchar(100), password varchar(32), cellulare varchar(20)) 
RETURNS VOID AS $$
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
CREATE OR REPLACE FUNCTION uni.insert_docente(
    nome varchar(50), cognome varchar(50), new_email varchar(100), password varchar(32), cellulare varchar(20), 
    inizioRapporto date, fineRapporto date,
    insegnamentoToUpdate integer
) 
RETURNS VOID AS $$
DECLARE 
    newId uni.utente.IDUtente%TYPE;
BEGIN 
    PERFORM * FROM uni.insegnamento as c WHERE c.IDInsegnamento= insegnamentoToUpdate;
    IF NOT FOUND THEN 
        RAISE EXCEPTION 'Insegnamento non trovato';
    END IF;
    PERFORM * FROM uni.insert_utente('Docente', nome, cognome, new_email, password, cellulare);
    SELECT u.IDUtente INTO newId FROM uni.utente as u WHERE u.email=new_email;
    INSERT INTO uni.docente(IDDocente, inizioRapporto, fineRapporto) 
        VALUES (newId, inizioRapporto, fineRapporto);
    PERFORM * FROM uni.cambia_responsabile(insegnamentoToUpdate, newId);
END;
$$ LANGUAGE plpgsql;

-- new_matricola: restituisce la nuova matricola da assegnare a uno studente
CREATE OR REPLACE FUNCTION uni.new_matricola() RETURNS uni.matricola.matricola%type AS $$
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


-- insert_studente: 
CREATE OR REPLACE FUNCTION uni.insert_studente(
    nome varchar(50), cognome varchar(50), new_email varchar(100), password varchar(32), cellulare varchar(20), codiceFiscale varchar(16), 
    IDCorso integer, dataImmatricolazione date
) 
RETURNS VOID AS $$
DECLARE 
    newId uni.utente.IDUtente%TYPE;
    newMatricola uni.matricola.matricola%TYPE;
BEGIN 
    PERFORM * FROM uni.insert_utente('Docente', nome, cognome, new_email, password, cellulare);
    SELECT * INTO newMatricola FROM uni.new_matricola();
    SELECT u.IDUtente INTO newId FROM uni.utente as u WHERE u.email=new_email;
    INSERT INTO uni.matricola(IDUtente, matricola, codiceFiscale) 
        VALUES (newId, newMatricola, codiceFiscale);

	INSERT INTO uni.studente(matricola, dataImmatricolazione, IDCorso) 
        VALUES (newMatricola, dataImmatricolazione, IDCorso);
END;
$$ LANGUAGE plpgsql;


-- insert_segreteria: 
CREATE OR REPLACE FUNCTION uni.get_insert_segreteria(nome varchar(50), cognome varchar(50), new_email varchar(100), password varchar(32), cellulare varchar(20)) 
RETURNS VOID AS $$
BEGIN
    PERFORM * FROM uni.insert_utente('Segreteria', nome, cognome, new_email, password, cellulare);
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


-- Inserimento base di un utente segreteria
INSERT INTO uni.utente(
    ruolo, nome, cognome, email, password, cellulare
) VALUES (
    'Segreteria', 'admin', '_', 'admin', 'admin', '0'
);


