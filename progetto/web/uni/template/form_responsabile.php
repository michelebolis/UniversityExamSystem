<?php
    include_once('lib/update/cambia_responsabile.php');
    function printform($err){
?>
        <form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'].'?responsabile=True';?>>
            <?php if (!is_null($err)){echo $err;}
            include_once('lib/get/get_all_insegnamento.php');
            $res = get_all_insegnamento();
            if (pg_num_rows($res)==0){
                echo 'Non ci sono ancora insegnamenti';
            }else{
                include_once('lib/get/get_all_docente.php');
                $res2 = get_all_docente();
                if (pg_num_rows($res2)==0){
                    echo 'Non ci sono ancora docenti';
                }else{
            ?>
            <div class="mb-3">
                <label for="insegnamento" class="form-label">Insegnamento</label>
                <select name="insegnamento" id="insegnamento" class="form-select">
                    <?php
                    include_once('lib/get/get_utente_bio.php');
                    while($insegnamento = pg_fetch_assoc($res)){
                        echo '<option value='. $insegnamento['idinsegnamento'] ;
                        if (isset($_POST['insegnamento']) && $_POST['insegnamento']==$insegnamento['idinsegnamento']){
                            echo ' selected';
                        }
                        echo '>';
                        $info = get_utente_bio($insegnamento['iddocente']);
                        echo $insegnamento['nome'] . ' | ' . $info['nome'] . ' ' . $info['cognome'];
                        echo '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="docente" class="form-label">Docente</label>
                <select name="docente" id="docente" class="form-select">
                    <?php
                    echo '<option value='. null ;
                    if (isset($_POST['docente']) && $_POST['docente']=='null'){
                        echo ' selected';
                    }
                    echo '>';
                    echo 'Nessun responsabile';
                    echo '</option>';
                    while($docente = pg_fetch_assoc($res2)){
                        echo '<option value='. $docente['iddocente'];
                        if (isset($_POST['docente']) && $_POST['docente']==$docente['iddocente']){
                            echo ' selected';
                        }
                        echo '>';
                        include_once('lib/get/get_utente_bio.php');
                        $info = get_utente_bio($docente['iddocente']);
                        echo $info['nome'] . ' ' . $info['cognome'];
                        echo '</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Aggiungi</button>
            <?php }}?>
        </form>
<?php
    }/*Fine funzione di print */
    
    if (isset($_POST['insegnamento']) && isset($_POST['docente'])){
        $doc = $_POST['docente'];
        if ($doc==""){
            $doc = null;
        }
        $err=cambia_responsabile($_POST['insegnamento'], $doc);
        if (!is_null($err)){
            printform($err);
        }else{
            echo '<div class="home_element col-6 offset-1">Responsabile cambiato correttamente</div>';
        }
    }else{
        printform(null);
    }
?>
