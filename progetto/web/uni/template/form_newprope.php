<?php
    include_once('lib/insert/insert_propedeuticita.php');
    function printform($err){
?>
        <form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'].'?prope=True';?>>
            <?php if (!is_null($err)){echo $err;}
            include_once('lib/get/get_all_insegnamento.php');
            $res = get_all_insegnamento();
            if (pg_num_rows($res)==0){
                echo 'Non sono ancora presenti insegnamenti';
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
                <label for="insegnamentoRichiesto" class="form-label">Insegnamento di cui è richiesta la propedeuticità</label>
                <select name="insegnamentoRichiesto" id="insegnamentoRichiesto" class="form-select">
                    <?php            
                    $res = get_all_insegnamento();
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
            <button type="submit" class="btn btn-primary">Aggiungi</button>
        <?php } ?>
        </form>
<?php
    } /*Fine funzione di print */

    if (isset($_POST['insegnamento']) && isset($_POST['insegnamentoRichiesto'])){
        $err=insert_propedeuticita($_POST['insegnamento'],$_POST['insegnamentoRichiesto']);
        if (!is_null($err)){
            printform($err);
        }else{
            echo '<div class="home_element col-6 offset-1"> Propedeuticità richiesta inserita correttamente </div';
        }
    }else{
        printform(null);
    }
?>
