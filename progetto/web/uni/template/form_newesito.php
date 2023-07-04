<?php
    include_once('lib/insert/registrazione_esito_esame.php');
    function printform($err){
?>
    <form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'];?>>
<?php if (!is_null($err)){echo $err . '<br/>';}
        if(!isset($_POST['esame'])) {
            include_once('lib/get/get_past_exam.php');
            $res = get_past_exam($_SESSION['utente']);
            if (pg_num_rows($res)==0){
                echo 'Non ci sono sessioni di esame';
            }else{
?>
        <div class="mb-3">     
            <label for="esame" class="form-label">Esami</label>
            <select id="esame" class="form-select" name="esame">
            <?php
                include_once('lib/get/get_insegnamento.php');
                while($esame = pg_fetch_assoc($res)){
                    echo '<option value='. $esame['idesame'] ;
                    if (isset($_POST['esame']) && $_POST['esame']==$esame['idesame']){
                        echo ' selected';
                    }
                    echo '>';
                    $insegnamento=get_insegnamento($esame['idinsegnamento']);
                    echo $insegnamento['nome'].' | '. date_format(new DateTime($esame['data']), 'd/m/Y');
                    echo '</option>';
                }
            ?>
            </select>
            <button type="submit" class="btn btn-primary" style="margin-top:20px;">Cerca iscritti</button>
        </div>
<?php 
            }
        }else{
            include_once('lib/get/get_iscritti_esame.php');
            $res = get_iscritti_esame($_POST['esame']);
            if (pg_num_rows($res)==0){
                echo 'Non ci sono iscritti per l esame selezionato';
            }else{
?>
        <label for="esame" class="form-label">IDEsame</label>
        <input id="esame" class="form-control" name="esame" type="text" readonly value=<?php echo $_POST['esame'];?>>
        <div class="mb-3">
            <label for="studenti" class="form-label">Studenti</label>
            <select id="studenti" class="form-select" name="matricola">
            <?php
                while($studente = pg_fetch_assoc($res)){
                    echo '<option value="'. $studente['matricola'].'"' ;
                    if (isset($_POST['matricola']) && $_POST['matricola']==$studente['matricola']){
                        echo ' selected';
                    }
                    echo '>';
                    echo $studente['matricola'];
                    echo '</option>';
                }
            ?>
            </select>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="ritirato" name="ritirato" <?php if(isset($_POST['ritirato'])){echo 'checked';}?>> 
            <label class="form-check-label" for="ritirato">Ritirato</label>
        </div>
        <div class="mb-3">
            <label for="voto" class="form-label">Voto</label>
            <input id="voto" class="form-control" name="voto" type="text" <?php if(isset($_POST['voto'])){echo 'value='.$_POST['voto'];}?> > 
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="lode" name="lode" <?php if(isset($_POST['lode'])){echo 'checked';}?>> 
            <label class="form-check-label" for="lode">Lode</label>
        </div>
        <button type="submit" class="btn btn-primary">Aggiungi</button>
    <?php
            }
        }
    ?>
    </form>
<?php
    } /* Fine function di print */
    
    if (isset($_POST['esame']) && isset($_POST['matricola'])){
        if (isset($_POST['ritirato']) && $_POST['ritirato']=true){
            $err=registrazione_esito_esame($_POST['esame'],$_POST['matricola'], NULL, NULL);
            if (!is_null($err)){
                printform($err);
            }else{
                echo '<div class="home_element col-6 offset-1">Esito inserito correttamente</div>';
            }
        
        }else if (isset($_POST['voto']) ){
            $lode="false";
            if (isset($_POST['lode'])){
                $lode="true";
            }
            $err=registrazione_esito_esame($_POST['esame'],$_POST['matricola'],$_POST['voto'],$lode);
            if (!is_null($err)){
                printform($err);
            }else{
                echo '<div class="home_element col-6 offset-1">Esito inserito correttamente</div>';
            }
        }else{
            printform(null);
        }
    }else{
        printform(null);
    }
?>
