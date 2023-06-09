<?php
    include_once('lib/insert/insert_sessione_laurea.php');
    function printform($err){
?>
        <form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'];?>>
        <?php if (!is_null($err)){echo $err;}
            include_once('lib/get/get_all_corso.php');
            $res = get_all_corso();
            if (pg_num_rows($res)==0){
                echo 'Non sono ancora stati inseriti corsi di laurea';
            }else{
        ?>
            <div class="mb-3">
                <label for="IDCorso" class="form-label">Corso</label>
                <select name="IDCorso" id="IDCorso" class="form-select">
                <?php
                    
                    while($corso = pg_fetch_assoc($res)){
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
                <label for="data" class="form-label">Data</label>
                <input id="data" class="form-control" name="data" type="date" 
                <?php if(isset($_POST['data']))
                {echo 'value='.$_POST['data'];}
                ?> 
                > 
            </div>
            <button type="submit" class="btn btn-primary">Aggiungi</button>
            
            <?php 
            } ?>
        </form>
<?php
    } /* Fine funzione di print */

    if (isset($_POST['IDCorso']) && isset($_POST['data'])){
        $err=insert_sessione_laurea($_POST['data'],$_POST['IDCorso']);
        if (!is_null($err)){
            printform($err);
        }else{
            echo '<div class="home_element col-6 offset-1">Esame inserito correttamente</div>';
        }
    }else{
        printform(null);
    }
?>
