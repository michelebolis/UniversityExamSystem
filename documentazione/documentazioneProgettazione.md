Michele Bolis
## I) Analisi dei requisiti
La nostra base di dati ha l'obiettivo di gestire l'organizzazione degli esami universitari gestendoli sia dal punto di vista del docente che dello studente.

Nella struttura organizzativa universitaria vengono individuati 3 ruoli: Segreteria, Docente e Studente, ognuno dei quali con funzionalità previse diverse. Per tutti i ruoli sono previste delle credenziali di accesso, date dalla email e dalla password, e dei dati anagrafici, Nome e Cognome

- Segreteria
Gli utenti della segreteria aggiungono e modificano gli altri due ruoli, Docente e Studente. 
La segreteria ha poi la possibilità di aggiungere/modificare i corsi la laurea previsti e i relativi insegnamenti, prevedendo eventuali propedeuticità tra gli insegnamenti.
Infine è demandato a tale ruolo il compito di creare le sessioni di laurea per i vari corsi di laurea ed in seguito quello di registare il voto della prova finale della laurea

- Docente
I docenti sono responsabili di almeno 1 insegnamento fino ad un massimo di 3. Dati tali insegnamenti di cui è responsabile, il docente ha la possibilità di prevedere degli esami in una determinata data (SE non sono presenti gia dei corsi dello stesso corso di laurea previsti nello stesso anno).
Successivamente alla data dell'esame, il docente potra inserire, per gli studenti che si sono iscritti, il relativo voto espresso in 30esimi
Prevediamo che il docente responsabile di un insegnamento possa essere cambiato nel tempo.

- Studente
L'utente Studente ha la possibilità di iscriversi agli esami degli insegnamenti previsti per il suo corso di laurea SE ha gia superato gli esami propedeutici a tale insegnamento (e SE non lo ha gia superato accettando l'esito).
Una volta ottenuto un esito sufficiente, lo Studente ha la possibilità di accettare o rifiutare l'esito.
Analogamento gli sarà possibile l'iscrizione ad una sessione di laurea per il suo corso di laurea, SE ha conseguito ed accettato tutti gli insegnamenti del corso, a cui seguira una valutazione finale che verrà accettata automaticamente una volta inserita dalla segreteria.
Prevediamo la possibilità del cambiamento del corso di laurea dello studente, eseguito dalla segreteria, MA senza il cambiamento della matricola, della email e della password relative allo Studente.
Nel caso di conseguimento di laurea, o di cambiamento del corso di laurea, o di rinuncia agli studi, le informazioni dello studente verranno spostate in uno storico degli studenti.


## II) Identificazione delle funzionalità da sviluppare




## III) Progettazione e realizzazione della base di dati
Utenti
Le informazioni di accesso per gli utenti della nostra base di dati vengono salvate nella tabella Utente con il relativo ruolo che permetterà l'identificazione delle funzionalità previste.
Prevediamo la possibilità di un'estensione dei ruoli con un semplice espansione del dominio Ruolo.

Docente/Segreteria
Utilizziamo la chiave dell'IDUtente come chiave esterna e principale della tabella Docente in cui salveremo l'inizio e fine del rapporto lavorativo in modo da poter identificare i docenti che insegnano da quelli che hanno insegnato. Non ci interessa invece salvare tale informazione per gli utenti della segreteria. 
Ipotizzando infatti che il numero dei docenti, presenti e passati, e degli utenti della segreteria sia molto inferiore rispetto a quello degli studenti, presenti e passati, non prevediamo una gestione di uno storico per questi due ruoli.
Dato un corso/insegnamento/utente non ci interesas sapere quale utente della segreteria lo abbia inserito/modificato quindi non prevediamo l'IDUtente nelle varie tabelle

Studente/Matricola/Storico_Studente
Per identificare gli studenti utilizzeremo la tabella Matricola che contiene la matricola come chiave primaria e il relativo codice fiscale ed IDUtente associato allo studente.
Il codice fiscale ci permettera di recuperare la matricola in caso di una seconda iscrizione ad un corso di laurea ("seconda" inteso successivamente ad un corso li laurea concluso/abbandonato) mentre l'IDUtente ci permetterà di recuperare la matricola date le credenziali di accesso fornite.
La scelta di creare una tabella Matricola è giustificata dal voler mantenere uniche le matricole sia per gli studenti iscritti (presenti nella tabella Studente con la propria matricola, l'id del corso di laurea e la data di immatricolazione) che per quelli passati che saranno salvati in Storico_Studente. Ricordiamo infatti che uno studente puo aver seguito piu corsi di laurea nel tempo infatti nello storico, lo studente è identificato dalla matricola e dall'id del corso.
Nello Storico_Studente non duplichiamo le credenziali di accesso (email e password) che saranno sempre presenti in Utente, informazioni tramite la matricola che non cambia nel tempo per la persona

Corso_Laurea/Insegnamento/Propedeuticità/Storico_Insegnamento
I corsi di laurea sono identificati da un identificativo incrementale, dal nome, dagli anni totali del corso (3 per la triennale, 2 per la magistrale), il valore della lode (per es la lode nella media puo essere considerata come un 30 o come un 32) e un campo che identifica se il corso di laurea sia attivo o meno.
Ad ogni corso di laurea sono associati degli Insegnamenti identificati da un IDInsegnamento. 
Ad ogni insegnamento viene assegnato un docente responsabile MA inizialmente IDDocente sara NULL, in quanto per la creazione del docente è necessario associarlo ad un insegnamento (per questo viene previsto un IDInsegnamento). Nella tabella Insegnamento registriamo altre informazioni quali nome, descrizione, crediti e anno di attivazione dell'insegnamento. 
Prevediamo la possibilità che il docente responsabile dell'insegnamento possa cambiare, in questo caso l'insegnamento viene duplicato, prima del cambiamento, nello Storico_Insegnamento come anche gli esami sostenuti per quell'insegnamento con quel docente.
Inoltre prevediamo il requisito di propedeuticità tra gli esami e la possibilità che un insegnamento sia previsto per piu corsi di laurea (tabella Insegnamenti_Corso).

Esame/Esito
Gli esami sono registarti dal Docente responsabile dell'Insegnamento che ne specifica una data.
Una volta registato lo studente di quel corso di laurea, potra uscriversi all'esame, in particolare verra creato un record in Esito con IDEsame, Matricola, voto e lode NULL e lo stato inizializzato a "In attesa". 
Potra iscriversi a quell'esame SOLO SE ha passato gli esami di cui è richiesta la propedeuticità e non si puo iscrivere una seconda volta allo stesso esame

Storico_Esame/Composizione_Passata_Corso
Oltre allo Storico_Insegnamento e allo Storico_Studente, prevediamo lo storico degli esami in Storico_Esame che contiene le informazioni degli esami passati (non per forza superati) o di studenti passati o di insegnamenti il cui docente responsabile è cambiato. 
Lo Storico_Esame lo associamo infatti a Matricola in quanto non sarebbe corretto associarlo con Storico_Studente nel caso in cui cambiasse solo l'insegnamento ma lo studente fosse ancora iscritto.
Teniamo inoltre traccia in Composizione_Passata_Corso dato un insegnamento passato, i corsi di laurea a cui era associato 

Laurea/Sessione_Laurea/Iscrizione_Sessione
Prevediamo infine la gestione delle lauree, in cui data una Sessione_Laurea di un corso in una determinata data (per un corso di laurea prevediamo che ci sia solo una sessione per data), lo studente dello stesso corso si puo iscrivere alla suddetta sessione (previa verifica degli esami conseguiti)
A cio poi seguira la registrazione della laurea nella tabella Laurea, caratterizzata dalla matricola e dall'id del corso di laurea in cui si è laureato, e ovviamente dal Voto, dal voto della prova finale e dall'eventuale lode assegnata. 

//foto ER
//foto ER ristrutturato
//foto progettazione logica

Commenti extra:
SE UNO STUDENTE SI E' RITIRATO DA  UN ESAME NON PUO AVERE UN VOTO O LA LODE ON CHANGE DEL DOCENTE RESPONSABILE DELL'INSEGNAMENTO, L'INSEGNAMENTO VIENE SPOSTATO NELLO STORICO DEGLI INSEGNAMENTO SE NON PRESENTE, ALTRIMENTI SE GIA PRESENTE VIENE FATTO UN UPDATE SULLA DATA DI FINE (POTREBBE ESSERE PRESENTE SE UNO STUDENTE DEVE ESSERE RIMOSSO MA GLI ESAMI SOSTENUTI NON SONO PRESENTI NELLO STORICO PERCHE ANCORA IN CORSO E QUINDI PER MANTENERE IL VINCOLO DI FOREIGN KEY E' NECESSARIO AGGIUNGERLI)

## IV) Progettazione e realizzazione della struttura e della presentazione delle pagine Web per interfacciarsi con la base di dati
Fase 1: LOGIN
Interfaccia omogenea per i tre ruoli per effettuare il login, in base al ruolo dell'IDUtente risultate da email e password, carico una delle 3 pagine possibili

Fase 2: Funzionalità dei ruoli
Studente:
- Iscrizione ad un esame del suo corso di laurea
- Iscrizione ad una sessione di laurea
- Visualizzazione esiti di esami dati 
- Visualizzazione carriera completa 
- Visualizzazione esami passati/recap media+crediti 
- Visualizzazione elenco insegnamenti dato un corso di laurea 
- Modifica email e password

Segreteria: 
- Inserimento/Modifica/Rimozione corso di laurea
- Inserimento/Modifica/Rimozione insegnamento
- Inserimento/Modifica/Rimozione Docente
- Inserimento/Modifica/Rimozione Studente
- Inserimento/Modifica Sessione_Laurea
- Inserimento/Modifica Laurea
- Visualizzare la carriera completa di un dato studente, presente o rimosso

Docente: 
- Inserimento/Modifica esame
- Inserimento/Modifica esito
