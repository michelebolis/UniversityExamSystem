<?php
    include_once('lib/delete/annulla_iscrizione_esame.php');
    function printform($err){
?>
        <form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'].'?modify=True';?>>
        <?php if (!is_null($err)){echo $err;}
            include_once('lib/get/get_all_iscrizione.php');
            include_once('lib/get/get_studente_bio.php');
            $info = get_studente_biobyid($_SESSION['utente']);
            $res = get_all_iscrizione($info['matricola']);
            if (pg_num_rows($res)==0){
                echo 'Non hai iscrizioni ad esami futuri';
            }else{
        ?>
            <div class="mb-3">
                <label for="esame" class="form-label">Sessione d'esame</label>
                <select name="esame" id="esame" class="form-select">
                <?php
                    include_once('lib/get/get_insegnamento.php');
                    while($esame = pg_fetch_assoc($res)){
                        echo '<option value='. $esame['idesame'];
                        if (isset($_POST['esame']) && $_POST['esame']==$esame['idesame']){
                            echo ' selected';
                        }
                        echo '>';
                        $insegnamento = get_insegnamento($esame['idinsegnamento']);
                        echo date_format(new DateTime($esame['data']), 'd/m/Y') . ' | ' . $insegnamento['nome'];
                            echo '</option>';
                    }
                ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Annulla iscrizione</button>
        <?php
            }
        ?>
        </form>
<?php
    } /* Fine funzione di print */

    if (isset($_POST['esame'])){
        $err=annulla_iscrizione_esame($info['matricola'], $_POST['esame']);
        if (!is_null($err)){
            printform($err);
        }else{
            echo '<div class="home_element col-6 offset-1">Iscrizione annullata correttamente</div>';
        }
    }else{
        printform(null);
    }
?>