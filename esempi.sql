CALL uni.insert_segreteria('Giuseppe', 'Lavati', 'giuseppe.lavati@uni.it',  'Giuseppe', '370942736498');

CALL uni.insert_corso_laurea('F1X', 'Informatica', 3, 32);
CALL uni.insert_corso_laurea('F2X', 'Sicurezza', 3, 30);

CALL uni.insert_insegnamento(NULL, 'Programmazione', NULL, 12, 2020);
CALL uni.insert_manifesto(1, 'F1X', 1);
CALL uni.insert_manifesto(1, 'F2X', 1);
CALL uni.insert_docente('Paolo', 'Boldi', 'paolo.boldi@uni.it', 'Paolo', '48793624982', '07/11/2002', 1);

CALL uni.insert_insegnamento(NULL, 'Basi di dati', NULL, 12, 2020);
CALL uni.insert_manifesto(2, 'F1X', 2);

CALL uni.insert_insegnamento(NULL, 'Web API', NULL, 6, 2020);
CALL uni.insert_manifesto(3, 'F1X', 1);


CALL uni.insert_insegnamento(NULL, 'Programmazione Web', NULL, 6, 2020);
CALL uni.insert_manifesto(4, 'F1X', 1);
CALL uni.insert_manifesto(4, 'F2X', 2);


CALL uni.insert_insegnamento(NULL, 'Algoritmi', NULL, 12, 2020);
CALL uni.insert_manifesto(5, 'F1X', 2);


CALL uni.insert_docente('Stefano', 'Montanelli', 'stefano.montanelli@uni.it', 'Stefano', '03500182934', '11/12/2000', 2);
CALL uni.insert_docente('Valerio', 'Bellandi', 'valerio.bellandi@uni.it', 'Valerio', '03500182934', '11/12/2000', 3);

CALL uni.cambia_responsabile(4, 5);
CALL uni.cambia_responsabile(5, 3);
-- CALL uni.cambia_responsabile(3, 4); --EXCEPTION

CALL uni.insert_propedeuticita(2, 1);

CALL uni.insert_esame(3, 1, '21/01/2021', '12:00');
CALL uni.insert_esame(5, 3, '30/01/2021', '12:00');
CALL uni.insert_esame(3, 1, '27/01/2021', '12:00');

CALL uni.insert_studente('Michele', 'Bolis', 'michele.bolis@uni.it', 'Michele', '01923782319', 'LASJD18AJ19AJDKA', 'F1X', '15/05/2020');
CALL uni.insert_studente('Andrea', 'Galliano', 'andrea.galliano@uni.it', 'Andrea', '01923452319', 'KSU118AJ19AJDKA', 'F1X', '15/09/2020');
CALL uni.insert_studente('Giacomo', 'Comitani', 'giacomo.comitani@uni.it', 'Giacomo', '1273452319', 'ADU118AJ19AJDKA', 'F1X', '11/09/2020');

CALL uni.iscrizione_esame('000001', 1);
-- CALL uni.iscrizione_esame('000001', 3); --EXCEPTION
CALL uni.iscrizione_esame('000002', 1);
CALL uni.iscrizione_esame('000003', 1);
CALL uni.registrazione_esito_esame('000001', 1, 30, True);
CALL uni.registrazione_esito_esame('000002', 1, 22, False);
CALL uni.registrazione_esito_esame('000003', 1, 17, False);
CALL uni.accetta_esito('000001', 1, True);
CALL uni.accetta_esito('000002', 1, False);

--CALL uni.annulla_iscrizione_esame('000002', 3); --non cancella niente perche non c è niente da cancellare
CALL uni.iscrizione_esame('000002', 3);
--CALL uni.annulla_iscrizione_esame('000002', 3);

CALL uni.iscrizione_esame('000003', 3);
CALL uni.registrazione_esito_esame('000003', 3, 25, False);
CALL uni.accetta_esito('000003', 3, True);

CALL uni.iscrizione_esame('000001', 2);
-- CALL uni.registrazione_esito_esame('000001', 2, 27, True); --EXCEPTION 
CALL uni.registrazione_esito_esame('000001', 2, 27, False);
-- CALL uni.registrazione_esito_esame('000001', 2, 27, False); --EXCEPTION
CALL uni.accetta_esito('000001', 2, True);

CALL uni.cambia_responsabile(1, 4);

CALL uni.delete_studente('000003', 'F1X');
CALL uni.insert_studente('Giacomo', 'Comitani', 'giacomo.comitani@uni.it', 'Giacomo', '035127911', 'ADU118AJ19AJDKA', 'F2X', '11/09/2021');

CALL uni.insert_sessione_laurea('25/05/2023', 'F2X');
-- CALL uni.iscrizione_laurea('000003', '25/05/2023', 'F2X'); --EXCEPTION

CALL uni.insert_esame(5, 4, '20/04/2023', '12:00');
CALL uni.iscrizione_esame('000003', 4);
CALL uni.registrazione_esito_esame('000003', 4, 23, False);
CALL uni.accetta_esito('000003', 4, True);

CALL uni.iscrizione_laurea('000003', '25/05/2023', 'F2X');
CALL uni.registrazione_esito_laurea('000003', 'F2X', '25/05/2023', 5, False);

CALL uni.insert_corso_laurea('R1', 'Ricamo', 3, 31);
CALL uni.insert_insegnamento(NULL, 'Ricamo 1', NULL, 12, 2023);
CALL uni.insert_insegnamento(NULL, 'Ricamo 2', NULL, 6, 2023);
CALL uni.insert_manifesto(6, 'R1', 1);
CALL uni.insert_docente('Giulia', 'Strada', 'giulia.strada@uni.it', 'Giulia', '48793624982', '08/06/2023', 6);
CALL uni.insert_studente('Sara', 'Uncino', 'sara.uncino@uni.it', 'Sara', '035127911', 'DIBUJLICKUABI1', 'R1', '09/06/2023');

CALL uni.insert_sessione_laurea('12/06/2023', 'R1');