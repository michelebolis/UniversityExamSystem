<?php
    include_once('lib/delete/delete_studente.php');
    function printform($err){
?>
        <form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'].'?delete=True';?>>
        <?php if (!is_null($err)){echo $err;}
            include_once('lib/get/get_all_studente.php');
            $res = get_all_studente();
            if (pg_num_rows($res)==0){
                echo 'Non sono ancora stati inseriti degli studenti';
            }else{
        ?>
           <div class="mb-3">
                <label for="info" class="form-label">Studente</label>
                <select name="info" id="info" class="form-select">
                <?php
                    while($studente = pg_fetch_assoc($res)){
                        echo '<option value="'. $studente['matricola'].';'. $studente['idcorso'].'"';
                        if (isset($_POST['info']) && $_POST['info']==($studente['matricola'].';'. $studente['idcorso'].'"')){
                            echo ' selected';
                        }
                        echo '>';
                        include_once('lib/get/get_id_studente.php');
                        $id = get_id_studente($studente['matricola'])[0];
                        include_once('lib/get/get_utente_bio.php');
                        $info = get_utente_bio($id);
                        echo $studente['matricola'] . ' | ' . $info['nome'] . ' ' . $info['cognome'];
                        echo '</option>';
                    }
                ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Elimina studente</button>
        <?php 
            }
        ?>
        </form>
<?php
    }/* Fine funzione di print */

    if (isset($_POST['info'])){
        $info = explode(";", $_POST['info']);
        $err=delete_studente($info[0],$info[1]);
        if (!is_null($err)){
            printform($err);
        }else{
            echo '<div class="home_element col-6 offset-1">Studente eliminato correttamente</div>';
        }
    }else{
        printform(null);
    }
?>
