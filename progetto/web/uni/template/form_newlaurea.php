<?php
    include_once('lib/insert/registrazione_esito_laurea.php');
    function printform($err){
?>
        <form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'] . '?esito=True';?>>
        <?php if (!is_null($err)){echo $err . '<br/>';}
            if(!isset($_POST['info'])) {
                include_once('lib/get/get_all_sessione.php');
                $res = get_all_sessione();
                if (pg_num_rows($res)==0){
                    echo 'Non sono ancora state inserite sessioni di laurea';
                }else{
        ?>
            <div class="mb-3">
                <label for="info" class="form-label">Sessione di laurea</label>
                <select name="info" id="info" class="form-select">
                <?php
                    while($sessione = pg_fetch_assoc($res)){
                        echo '<option value="'. $sessione['idcorso'] . ';' . $sessione['data'] .'"';
                        if (isset($_POST['info']) && $_POST['info']==$sessione['idcorso'] . ';'. $sessione['data']){
                            echo ' selected';
                        }
                        echo '>';
                        echo $sessione['idcorso'] . ' | ' . date_format(new DateTime($sessione['data']), 'd/m/Y');
                        echo '</option>';
                    }
                ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cerca iscritti</button>
        <?php   }
            }else{
        ?>
            <label for="esame" class="form-label">Sessione di laurea</label>
            <input id="esame" class="form-control" name="info" type="hidden" value=<?php echo $_POST['info'];?>>
        <?php
            include_once('lib/get/get_iscritti_laurea.php');
            $info=explode(";", $_POST['info']);
            $res = get_iscritti_laurea($info[0], $info[1]);
            if (pg_num_rows($res)==0){
                echo 'Non ci sono iscritti in attesa di voto per la sessione di laurea selezionato';
            }else{
        ?>  
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
            <div class="mb-3">
                <label for="incremento" class="form-label">Incremento</label>
                <input id="incremento" class="form-control" name="incremento" type="text" <?php if(isset($_POST['incremento'])){echo 'value='.$_POST['incremento'];}?> > 
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
    }/* Fine funzione di print */

    if (isset($_POST['info']) && isset($_POST['matricola']) && isset($_POST['incremento'])){
        $lode="false";
        if (isset($_POST['lode'])){
            $lode="true";
        }
        $info = explode(";", $_POST['info']);
        $err=registrazione_esito_laurea($_POST['matricola'], $info[0], $info[1], $_POST['incremento'], $lode);
        if (!is_null($err)){
            printform($err);
        }else{
            echo '<div class="home_element col-6 offset-1">Esito inserito correttamente</div>';
        }
        
    }else{
        printform(null);
    }
?>
