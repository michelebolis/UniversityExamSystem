-- ATTENZIONE: Assicurarsi di avere un database esistente 'uni'
-- creazione dello schema uni
DROP SCHEMA IF EXISTS uni;
CREATE SCHEMA uni;

-- creazione dei domini 
DROP DOMAIN IF EXISTS ruolo;
CREATE DOMAIN test.ruolo as varchar(100)
CHECK (
    VALUE 'Segreteria' OR
    VALUE 'Docente' OR 
    VALUE 'Studente'
);

DROP DOMAIN IF EXISTS tipoLaurea;
CREATE DOMAIN tipoLaurea as int
CHECK (
    VALUE=3 OR VALUE=2
);

DROP DOMAIN IF EXISTS annoCorso;
CREATE DOMAIN annoCorso as int
CHECK (
    VALUE=3 OR VALUE=2 OR VALUE=1
);

DROP DOMAIN IF EXISTS voto;
CREATE DOMAIN voto as int
CHECK (
    VALUE>=0 AND VALUE<=30
);

DROP DOMAIN IF EXISTS statoEsito;
CREATE DOMAIN statoEsito as int
CHECK (
    VALUE 'Ritirato' OR
    VALUE 'Rifiutato' OR 
    VALUE 'In attesa' OR
    VALUE 'In attesa di accettazione' OR
    VALUE 'Bocciato'   
);

DROP DOMAIN IF EXISTS votoLaurea;
CREATE DOMAIN votoLaurea as int
CHECK (
    VALUE>=60 AND VALUE<=110
);

-- creazione delle tabelle del db uni nello schema uni
CREATE OR REPLACE uni.insegnamento(

);
CREATE OR REPLACE uni.utente(

);
CREATE OR REPLACE uni.corso_laurea(

);

