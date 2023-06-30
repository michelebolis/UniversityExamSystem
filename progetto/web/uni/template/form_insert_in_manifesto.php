<?php
    include_once('lib/insert/insert_manifesto.php');
    function printform($err){
?>
        <form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'].'?manifesto=True';?>>
            <?php if (!is_null($err)){echo $err;}
            include_once('lib/get/get_all_insegnamento.php');
            $res = get_all_insegnamento();
            include_once('lib/get/get_all_corso.php');
            $res2 = get_all_corso();
            if (pg_num_rows($res)==0 || pg_num_rows($res2)==0){
                echo 'Non sono presenti abbastanza corsi di laurea o insegnamenti per modificare un manifesto';
            }else{
            ?>
            <div class="mb-3">
                <label for="insegnamento" class="form-label">Insegnamento</label>
                <select name="insegnamento" id="insegnamento" class="form-select">
            <?php 
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
                <label for="IDCorso" class="form-label">Corso</label>
                <select name="IDCorso" id="IDCorso" class="form-select">
                    <?php
                    while($corso = pg_fetch_assoc($res2)){
                        echo '<option value="'. $corso['idcorso'] . '"';
                        if (isset($_POST['IDCorso']) && $_POST['IDCorso']==$corso['idcorso']){
                            echo ' selected';
                        }
                        echo '>';
                        echo $corso['idcorso'] . ' | ' . $corso['nome'];
                        echo '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="anni2" class="form-label p-1">Anno</label>
                <div class="btn-group-vertical" role="group" aria-label="Vertical radio toggle button group">
                    <input type="radio" class="btn-check" name="anno" id="anni1" autocomplete="off" value=1
                    <?php
                        if ((isset($_POST['anno']) && $_POST['anno']==1)|| (!isset($_POST['anno']))){
                            echo ' checked';
                        }
                    ?>
                    >

                    <label class="btn btn-outline" for="anni1">1</label>
                    <input type="radio" class="btn-check" name="anno" id="anni2" autocomplete="off" value=2
                    <?php
                        if (isset($_POST['anno']) && $_POST['anno']==2){
                            echo ' checked';
                        }
                    ?>
                    >

                    <label class="btn btn-outline" for="anni2">2</label>
                    <input type="radio" class="btn-check" name="anno" id="anni3" autocomplete="off" value=3
                    <?php
                        if (isset($_POST['anno']) && $_POST['anno']==3){
                            echo ' checked';
                        }
                    ?>
                    >
                    <label class="btn btn-outline" for="anni3">3</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Aggiungi</button>
            <?php } ?>
        </form>
<?php
    }/*Fine funzione di print*/ 

    if (isset($_POST['insegnamento']) && isset($_POST['IDCorso']) && isset($_POST['anno'])){
        $err=insert_manifesto($_POST['insegnamento'],$_POST['IDCorso'],$_POST['anno']);
        if (!is_null($err)){
            printform($err);
        }else{
            echo '<div class="home_element col-6 offset-1">Insegnamento inserito correttamente nel manifesto del corso di laurea</div>';
        }
    }else{
        printform(null);
    }
?>
