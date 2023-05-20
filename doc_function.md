## Procedures:
uni.insert_corso_laurea(
    IDCorso varchar(20), 
    nome varchar(100), 
    anniTotali uni.tipoLaurea, 
    valoreLode integer
)

uni.insert_insegnamento(
    IDDocente integer, 
    nome varchar(200), 
    descrizione text, 
    crediti integer, 
    annoAttivazione integer
)

uni.cambia_responsabile(
    insegnamentoToUpdate integer, 
    newDocente integer
)

uni.insert_utente(
    ruolo uni.ruolo, 
    nome varchar(50), 
    cognome varchar(50), 
    new_email varchar(100), 
    password varchar(32), 
    cellulare varchar(20)
)

uni.insert_docente(
    nome varchar(50), 
    cognome varchar(50), 
    new_email varchar(100), 
    password varchar(32), 
    cellulare varchar(20), 
    inizioRapporto date, 
    fineRapporto date,
    insegnamentoToUpdate integer
) 

uni.modifica_utente(
    the_IDUtente integer, 
    new_email varchar(100), 
    new_password varchar(32), 
    new_cellulare varchar(20)
)

uni.cambio_corso_laurea(
    the_matricola char(6), 
    IDCorso varchar(20), 
    dataImmatricolazione date, 
    new_email varchar(100), 
    password varchar(32), 
    cellulare varchar(20)
)

uni.insert_studente(
    nome varchar(50), 
    cognome varchar(50), 
    new_email varchar(100), 
    password varchar(32), 
    cellulare varchar(20), 
    the_codiceFiscale varchar(16), 
    IDCorso varchar(20), 
    dataImmatricolazione date
) 

uni.insert_segreteria(
    nome varchar(50), 
    cognome varchar(50), 
    new_email varchar(100), 
    password varchar(32), 
    cellulare varchar(20)
) 

uni.insert_esame(
    IDDocente integer, 
    IDInsegnamento integer, 
    data date
)

uni.insert_propedeuticita(
    IDInsegnamento1 integer, 
    IDInsegnamento2 integer
)

uni.iscrizione_esame(
    matricola char(6), 
    IDEsame integer
)

uni.insert_manifesto(
    IDInsegnamento integer, 
    IDCorso varchar(20), 
    anno uni.annoCorso
)

uni.insert_sessione_laurea(
    data date, 
    IDCorso varchar(20), 
    creditiLaurea integer
)

uni.iscrizione_esame(
    matricola char(6), 
    data date, 
    IDCorso varchar(20)
)

uni.registrazione_esito_esame(
    the_matricola char(6), 
    the_IDEsame integer, 
    the_voto uni.voto, 
    the_lode boolean
)

uni.accetta_esito(
    the_matricola char(6), 
    the_IDEsame integer, 
    accettato boolean
)

uni.delete_studente(
    the_matricola char(6), 
    the_IDCorso varchar(20)
)

uni.calcola_voto_laurea(
    the_matricola char(6), 
    incremento integer
)

uni.registrazione_esito_laurea(
    the_matricola char(6), 
    the_IDCorso integer, 
    the_data date, 
    incremento integer, lode boolean
)

## Functions:
uni.new_matricola() 
    RETURNS uni.matricola.matricola%type

uni.get_id_ruolo(
    the_email varchar(100), 
    the_password varchar(32)
)   RETURNS SETOF uni.utente

uni.get_studente_bio(
    corso varchar(20), 
    matricola varchar(6)
)   RETURNS SETOF uni.studente_bio