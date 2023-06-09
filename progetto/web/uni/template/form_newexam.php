<?php
    include_once('lib/insert/insert_esame.php');
    function printform($err){
        ?>
            <form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'];?>>
                <?php if (!is_null($err)){echo $err;}?>
                <div class="mb-3">
                <label for="IDDocente" class="form-label">IDDocente</label>
                <input type="text" id="IDDocente" class="form-control" value="<?php echo $_SESSION['utente'];?>" name="docente">
                </div>
                <div class="mb-3">
                <label for="menu" class="form-label">Insegnamento</label>
                <select id="menu" class="form-select" name="insegnamento">
                    <?php
                        include_once('lib/get/get_insegnamenti.php');
                        $res = get_insegnamenti($_SESSION['utente']);
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
                <div class="mb-3">
                <div>
                    <label for="data" class="form-label">Data</label>
                    <input id="data" class="form-control" name="data" type="date" <?php if(isset($_POST['data'])){echo 'value='.$_POST['data'];}?> > 
                </div>
                <div>
                    <label for="orario" class="form-label">Orario</label>
                    <input id="orario" class="form-control" name="orario" type="time" <?php if(isset($_POST['orario'])){echo 'value='.$_POST['orario'];}?> > 
                </div>
                </div>
                <button type="submit" class="btn btn-primary">Aggiungi</button>
            </form>
            <?php
    }
    if (isset($_SESSION['utente']) && isset($_POST['insegnamento']) && isset($_POST['data']) && isset($_POST['orario'])){
        $err=insert_esame($_SESSION['utente'],$_POST['insegnamento'],$_POST['data'], $_POST['orario']);
        if (!is_null($err)){
            printform($err);
        }else{
            echo '<div class="home_element col-6 offset-1">Esame inserito correttamente</div>';
        }
    }else{
        printform(null);
    }
?>
