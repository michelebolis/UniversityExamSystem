<?php
    include_once('lib/insert/iscrizione_laurea.php');
    function printform($err){
?>
        <form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'];?>>
        <?php if (!is_null($err)){echo $err;}
            include_once('lib/get/get_studente_bio.php');
            $info = get_studente_biobyid($_SESSION['utente']);
        ?>
            <div class="mb-3">
                <label for="corso" class="form-label">Corso di laurea</label>
                <input id="corso" class="form-control" name="corso" type="text" readonly value=<?php echo $info['idcorso'];?>>
                <?php
                    include_once('lib/get/get_all_sessione_bycorso.php');
                    $res = get_all_sessione_bycorso($info['idcorso']);
                    if (pg_num_rows($res)==0){
                        echo 'Non sono previste sessione di laurea per il corso di laurea';
                    }else{
                ?>
                <label for="data" class="form-label">Data</label>
                <select id="data" class="form-select" name="data">
                <?php
                    while($sessione = pg_fetch_assoc($res)){
                        echo '<option value="'. $sessione['data'] . '"' ;
                        if (isset($_POST['data']) && $_POST['data']==$sessione['data']){
                            echo ' selected';
                        }
                        echo '>';
                        echo  date_format(new DateTime($sessione['data']), 'd/m/Y');
                        echo '</option>';
                    }
                ?>
                </select>
                <button type="submit" class="btn btn-primary" style="margin-top:20px;">Iscriviti alla sessione di laurea</button>
                <?php
                    }
                ?>
            </div>
        </form>
<?php
    } /* Fine function di print */

    include_once('lib/get/get_all_insegnamento_mancante.php');
    if (pg_num_rows(get_all_insegnamento_mancante($info['matricola']))!=0){
        echo '<form class="col-6 offset-1">';
        echo 'Ci sono degli esami mancanti prima di poter sostenere la laurea';
        echo '</form>';
    }else if (isset($_POST['corso']) && isset($_POST['data'])){
        $err=iscrizione_esame($info['matricola'],$_POST['data'], $_POST['corso']);
        if (!is_null($err)){
            printform($err);
        }else{
            echo 'Iscrizione all esame eseguita correttamente';
        }
    }else{
        printform(null);
    }
?>
