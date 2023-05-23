FASE 1: LOGIN --> uni.get_id_ruolo
FASE 2: 
## Segreteria
Cambiamento delle credenziali di accesso: uni.modifica_utente

Corso di laurea
- Inserimento: uni.insert_corso_laurea
- Inserimento manifesto: uni.insert_manifesto
- Modifica: 
- Rimozione: 

Insegnamento
- Inserimento: uni.insert_insegnamento
- Inserimento propedeuticita: uni.insert_propedeuticita
- Modifica responsabile: uni.cambia_responsabile
- Rimozione: 

Docente
- Inserimento: uni.insert_docente
- Modifica: 
- Rimozione: uni.licenzia_docente -- TO DO

Studente
- Inserimento: uni.insert_studente
- Modifica: 
- Rimozione: uni.delete_studente

Sessione_laurea
- Inserimento: uni.insert_sessione_laurea
- Rimozione

Laurea
- Inserimento: uni.registrazione_esito_laurea

Carriera completa di uno studente: 
Carriera esami passati di uno studente: 
Carriera esami passato degli studenti di un corso di laurea


## Docente
Cambiamento delle credenziali di accesso: uni.modifica_utente

Esame
- Inserimento: uni.insert_esame
- Inserimento esito: uni.registrazione_esito_esame
- Modifica: uni.cambia_data_esame -- TO DO


## Studente
Cambiamento delle credenziali di accesso: uni.modifica_utente
Info:
- Informazioni generali: uni.get_studente_bio
- Carriera completa dell'attuale corso di laurea: uni.get_carriera_completa_studente 
- Carriera esami passati dell'attuale corso di laurea: uni.get_carriera_studente 
- Carriera passata: uni.get_carriera_passata_studente --TO DO
- Media e crediti attuali: uni.get_studente_stats

Esame
- Iscrizione ad un esame: uni.iscrizione_esame
- Accettazione esito esame: uni.accetta_esito


Sessione_laurea:
- Iscrizione: uni.iscrizione_laurea


- Visualizzazione elenco insegnamenti dato un corso di laurea: uni.get_corso_laurea_insegnamenti
