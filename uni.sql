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
    ruolo ruolo NOT NULL,
    nome varchar(50) NOT NULL,
    cognome varchar(50) NOT NULL,
    email varchar(100) NOT NULL UNIQUE,
    password varchar(32) NOT NULL,
    cellulare varchar(20) NOT NULL
);

CREATE TABLE uni.corso_laurea(
    IDCorso varchar(20) PRIMARY KEY,
    nome varchar(100) NOT NULL,
    anniTotali tipoLaurea NOT NULL,
    valoreLode integer NOT NULL,
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
    crediti integer NOT NULL,
    annoAttivazione integer NOT NULL
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