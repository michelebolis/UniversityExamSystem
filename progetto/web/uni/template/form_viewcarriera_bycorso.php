<form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'].'?allCarriera=True';?>>
<?php
    if (!isset($_POST['corso'])){
        include_once('lib/get/get_all_corso.php');
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
                echo $corso['idcorso'] . ' | ' . $corso['nome'];
                echo '</option>';
            }
        ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Seleziona</button>
<?php 
        }
    }else{ 
        include_once('lib/print_carriera.php');
        include_once('lib/get/get_all_studente_bycorso.php');
        $res = get_all_studente_bycorso($_POST['corso']);
        if (pg_num_rows($res)==0){
            echo 'Non vi è nessun studente iscritto per il corso di laurea ' . $_POST['corso'];
        }else{
            while($studente = pg_fetch_assoc($res)){
                print_carriera($studente['matricola'], $studente['idcorso']);
            }
        }
    }
?>
</form>