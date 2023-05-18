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
    matricola varchar(6) PRIMARY KEY,
    IDUtente integer REFERENCES uni.utente(IDUtente) NOT NULL UNIQUE,
    codiceFiscale varchar(16) NOT NULL UNIQUE
);

CREATE TABLE uni.studente(
    IDCorso varchar(20) REFERENCES uni.corso_laurea(IDCorso),
    matricola varchar(6) REFERENCES uni.matricola(matricola) UNIQUE,
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
    matricola varchar(6) REFERENCES uni.matricola(matricola),
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
    matricola varchar(6) REFERENCES uni.matricola(matricola),
    IDCorso varchar(20) REFERENCES uni.corso_laurea(IDCorso),
    dataImmatricolazione date NOT NULL,
    dataRimozione date NOT NULL DEFAULT CURRENT_DATE,
    PRIMARY KEY(matricola, IDCorso)
);

CREATE TABLE uni.storico_esame(
    IDStorico SERIAL PRIMARY KEY,
    matricola varchar(6) REFERENCES uni.matricola(matricola) NOT NULL,
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
    matricola varchar(6) REFERENCES uni.matricola(matricola),
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

-- Inserimento base di un utente segreteria
INSERT INTO uni.utente(
    ruolo, nome, cognome, email, password, cellulare
) VALUES (
    'Segreteria', 'admin', '_', 'admin', 'admin', '0'
);
