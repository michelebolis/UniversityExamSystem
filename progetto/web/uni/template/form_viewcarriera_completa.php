<form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'];?>>
<?php
    if (isset($_SESSION['ruolo']) && $_SESSION['ruolo']=='Studente'){
        include_once('lib/print_carriera_completa.php');
        print_carriera_completa($info['matricola'], $info['idcorso']);
    }else if (!isset($_POST['info'])){
        include_once('lib/get/get_all_studente.php');
        $res = get_all_studente();
        if (pg_num_rows($res)==0){
            echo 'Non ci sono studenti iscritti';
        }else{
?>
        <div class="mb-2">
            <label for="studente" class="form-label">Studente</label>
            <select id="studente" class="form-select" name="info">
            <?php
                while($studente = pg_fetch_assoc($res)){
                    echo '<option value="'. $studente['matricola']. ';' . $studente['idcorso'] .'"' ;
                    echo '>';
                    echo $studente['matricola'] . ' | ' . $studente['idcorso'];
                    echo '</option>';
                }
            ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Seleziona</button>
<?php 
        }
    }else{
        $matricola= explode(";", $_POST['info'])[0];
        $corso = explode(";", $_POST['info'])[1];
        include_once('lib/print_carriera_completa.php');
        print_carriera_completa($matricola, $corso);
    }
        
?>
</form>