<?php
    include_once('lib/update/cambia_stato_corso.php');
    include_once('lib/get/get_all_corso.php');
    
    function printform($err){
?>
    <form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'].'?attiva=True';?>>
    <?php if (!is_null($err)){echo $err;}
        $res = get_all_corso();
        if (pg_num_rows($res)==0){
            echo 'Non sono ancora stati inseriti corsi di laurea';
        }else{
        ?>
        <div class="mb-3">
        <label for="corso" class="form-label">Corso</label>
            <select name="corso" id="corso" class="form-select">
            <?php
                while($corso = pg_fetch_assoc($res)){
                    echo '<option value="'. $corso['idcorso'] . '"';
                    echo '>';
                    echo $corso['idcorso'] . ' | ' . $corso['nome'] . ' | ' ; if($corso['attivo']=="t"){echo "Attivo";}else{echo "disattivato";};
                    echo '</option>';
                }
            ?>
            </select>
        </div>
        <div class="mb-2">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="attiva" value="t">
                <label class="form-check-label" for="flexRadioDefault1">
                    Attiva
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="attiva" value="f" checked>
                <label class="form-check-label" for="flexRadioDefault2">
                    Disattiva
                </label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Cambia</button>
        <?php } ?>
    </form>
<?php
    }

    if (isset($_POST['corso']) && isset($_POST['attiva'])){
        $attiva = "False";
        if ($_POST['attiva']=="t"){
            $attiva = "True";
        }
        $err = cambia_stato_corso($_POST['corso'], $attiva);
        if (!is_null($err)){
            printform($err);
        }else{
            echo '<div class="home_element col-6 offset-1">Corso di laurea correttamente attivato/disattivato </div>';
        }
    }else{
        printform(null);
    }
?>