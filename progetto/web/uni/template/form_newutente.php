<?php
    include_once('lib/insert/insert_utente.php');
    function printform($err){
?>
        <form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'];?>>
        <?php if (!is_null($err)){echo $err;}
            if (!isset($_POST['ruolo'])){
            /* Scelta del ruolo */
        ?>
            <div class="mb-2">
            <label for="ruolo" class="form-label">Ruolo</label>
                <select name="ruolo" id="ruolo" class="form-select">
                    <option value="Studente" selected>Studente</option>
                    <option value="Segreteria">Segreteria</option>
                    <option value="Docente">Docente</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Seleziona</button>
        <?php
            }else{
            /* Attributi in comune tra segreteria-studente-docente */
            ?>
            <div class="mb-2">
                <label for="ruolo" class="form-label">Ruolo</label>
                <input type="text" id="ruolo" class="form-control" name="ruolo" readonly
                <?php
                    echo 'value="' . $_POST['ruolo'] . '"';
                ?>
                >
            </div>
            <div class="mb-2">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" id="nome" class="form-control" name="nome"
                <?php
                    if ((isset($_POST['nome']))){echo 'value="' . $_POST['nome'] . '"';}
                ?>
                >
            </div>
            <div class="mb-2">
                <label for="cognome" class="form-label">Cognome</label>
                <input type="text" id="cognome" class="form-control" name="cognome"
                <?php
                    if ((isset($_POST['cognome']))){echo 'value="' . $_POST['cognome'] . '"';}
                ?>
                >
            </div>
            <div class="mb-2">
                <label for="email" class="form-label">Email</label>
                <input type="text" id="email" class="form-control" name="email"
                <?php
                    if ((isset($_POST['email']))){echo 'value="' . $_POST['email'] . '"';}
                ?>
                >
            </div>
            <div class="mb-2">
                <label for="password" class="form-label">Password</label>
                <input type="text" id="password" class="form-control" name="password"
                <?php
                    if ((isset($_POST['password']))){echo 'value="' . $_POST['password'] . '"';}
                ?>
                >
            </div>
            <div class="mb-2">
                <label for="cellulare" class="form-label">Cellulare</label>
                <input type="text" id="cellulare" class="form-control" name="cellulare"
                <?php
                    if ((isset($_POST['cellulare']))){echo 'value="' . $_POST['cellulare'] . '"';}
                ?>
                >
            </div>
        <?php 
            if ($_POST['ruolo']=="Studente"){
            /* Attributi aggiuntivi per il ruolo studente */
                include_once('lib/get/get_all_corso.php');
                $res = get_all_corso();
                if (pg_num_rows($res)==0){
                    echo 'Non è possibile aggiungere uno studente: non sono stati inseriti corsi di laurea';
                }else{
        ?>
            <div class="mb-2">
                <label for="codfiscale" class="form-label">Codice fiscale</label>
                <input type="text" id="codfiscale" class="form-control" name="codfiscale" maxlength="16"
                <?php
                    if ((isset($_POST['codfiscale']))){echo 'value="' . $_POST['codfiscale'] . '"';}
                ?>
                >
            </div>
            <div class="mb-2">
                <label for="corso" class="form-label">Corso di laurea</label>
                <select name="corso" id="corso" class="form-select">
                <?php
                    while($corso = pg_fetch_assoc($res)){
                        echo '<option value="'. $corso['idcorso'] . '"';
                        if (isset($_POST['corso']) && $_POST['corso']==$corso['idcorso']){
                            echo ' selected';
                        }
                        echo '>';
                        echo $corso['idcorso'] . ' | ' . $corso['nome'];
                        echo '</option>';
                    }
                ?>
                </select>
            </div>
            <div class="mb-2">
                <label for="data" class="form-label">Data iscrizione</label>
                <input type="date" id="data" class="form-control" name="data"
                <?php
                    if ((isset($_POST['data']))){echo 'value="' . $_POST['data'] . '"';}
                ?>
                >
            </div>
            <div class="mb-2">
                <label for="imm" class="form-label">Data immatricolazione</label>
                <input type="date" id="data" class="form-control" name="imm"
                <?php
                    if ((isset($_POST['imm']))){echo 'value="' . $_POST['imm'] . '"';}
                ?>
                >
            </div>
            
        <?php   }
            }else if ($_POST['ruolo']=="Docente"){
                /* Attributi aggiuntivi per il ruolo docente*/
                include_once('lib/get/get_all_insegnamento.php');
                $res = get_all_insegnamento();
                if (pg_num_rows($res)==0){
                    echo 'Non è possibile aggiungere un docente: non sono stati inseriti insegnamenti';
                }else{
        ?>
            <div class="mb-2">
                <label for="inizio" class="form-label">Inizio rapporto di lavoro</label>
                <input type="date" id="inizio" class="form-control" name="inizio"
                <?php
                    if ((isset($_POST['inizio']))){echo 'value="' . $_POST['inizio'] . '"';}
                ?>
                >
            </div>
            <div class="mb-2">
                <label for="insegnamento" class="form-label">Insegnamento</label>
                <select name="insegnamento" id="insegnamento" class="form-select">
                <?php
                    while($insegnamento = pg_fetch_assoc($res)){
                        echo '<option value='. $insegnamento['idinsegnamento'] ;
                        if (isset($_POST['insegnamento']) && $_POST['insegnamento']==$insegnamento['idinsegnamento']){
                            echo ' selected';
                        }
                        echo '>';
                        echo $insegnamento['nome'];
                        echo '</option>';
                    }
                ?>
                </select>
            </div>
        <?php   }
            }
        ?>
            <button type="submit" class="btn btn-primary">Aggiungi</button>
        <?php 
            } 
        ?>
                
        </form>
<?php
    }/*Fine funzione di print */

    if (isset($_POST['ruolo']) && isset($_POST['nome']) && isset($_POST['cognome']) && isset($_POST['email'])
        && isset($_POST['password'])&& isset($_POST['cellulare'])){
        if ($_POST['password']==''){ /*Controllo necessario perche l'md5 trasformerebbe il vuoto in una password non vuota */
            printform('La password non deve essere vuota');
        }else{
            switch($_POST['ruolo']){
                case 'Segreteria': 
                    $err=insert_segreteria($_POST['nome'],$_POST['cognome'],$_POST['email'], $_POST['password'], $_POST['cellulare']);
                    break;
                case 'Studente':
                    if (isset($_POST['codfiscale']) && isset($_POST['corso']) && isset($_POST['data']) && isset($_POST['imm'])){
                        $err= insert_studente($_POST['nome'],$_POST['cognome'],$_POST['email'], $_POST['password'], 
                                                $_POST['cellulare'], $_POST['codfiscale'], $_POST['corso'], $_POST['data'], $_POST['imm']);
                    }else{
                        printform(null);
                    }
                    break;
                case 'Docente':
                    if (isset($_POST['inizio']) && isset($_POST['insegnamento'])){
                        $err=insert_docente($_POST['nome'],$_POST['cognome'],$_POST['email'], $_POST['password'], 
                                            $_POST['cellulare'], $_POST['inizio'], $_POST['insegnamento']);
                    }else{
                        printform(null);
                    }
                    break;
                default: $err = 'Ruolo non previsto';
            }
            if (!is_null($err)){
                printform($err);
            }else{
                echo '<div class="home_element col-6 offset-1">Utente inserito correttamente</div>';
            }
        }
    }else{
        printform(null);
    }
?>
