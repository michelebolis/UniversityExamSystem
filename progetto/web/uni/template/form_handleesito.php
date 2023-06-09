<?php
    include_once('lib/update/accetta_esito.php');
    function printform($err){
?>
        <form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'];?>>
        <?php if (!is_null($err)){echo $err;}
            include_once('lib/get/get_all_esito_attesa_acc.php');
            include_once('lib/get/get_studente_bio.php');
            $info = get_studente_biobyid($_SESSION['utente']);
            $res = get_all_esito_attesa_acc($info['matricola']);
            if (pg_num_rows($res)==0){
                echo 'Non ci sono esiti in attesa di accettazione';
            }else{
        ?>
            <div class="mb-3">
                <label for="esito" class="form-label">Esiti</label>
                <select id="esito" class="form-select" name="esito">
                <?php
                    include_once('lib/get/get_insegnamento.php');
                    include_once('lib/get/get_esame.php');
                    while($esito = pg_fetch_assoc($res)){
                        echo '<option value='. $esito['idesame'] ;
                        $esame= get_esame($esito['idesame'] );
                        $insegnamento = get_insegnamento($esame['idinsegnamento']);
                        if (isset($_POST['esito']) && $_POST['esito']==$esito['idesame']){
                            echo ' selected';
                        }
                        echo '>';
                        echo $insegnamento['nome'].' | Crediti: '.$insegnamento['crediti'] . ' | Voto: ' . $esito['voto'];
                        if ($esito['lode']=='t'){echo 'L';}
                            echo '</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="mb-3" style="margin-top:15px;">
                <label for="rifiutato" class="form-label">Accetta</label>
                <div class="btn-group-vertical" role="group" aria-label="Vertical radio toggle button group">
                    <input type="radio" class="btn-check" name="accettato" id="accettato" autocomplete="off" value="SI"
                    <?php
                        if ((isset($_POST['accettato']) && $_POST['accettato']=="SI") || (!isset($_POST['accettato']))){
                            echo ' checked';
                        }
                    ?>
                    >
                    <label class="btn btn-outline" for="accettato">SI</label>
                    <input type="radio" class="btn-check" name="accettato" id="rifiutato" autocomplete="off" value="NO"
                    <?php
                        if (isset($_POST['accettato']) && $_POST['accettato']=="NO"){
                            echo ' checked';
                        }
                    ?>
                    >
                    <label class="btn btn-outline" for="rifiutato">NO</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top:20px;">Salva esito</button>
        <?php
            }
        ?>
        </form>
<?php
    } /* Fine funzione di print */
    
    if (isset($_POST['esito']) && isset($_POST['accettato'])){
        $err=accetta_esito($info['matricola'], $info['esito'],$_POST['accettato']=='SI');
        if (!is_null($err)){
            printform($err);
        }else{
            echo 'Iscrizione all esame eseguita correttamente';
        }
        
    }else{
        printform(null);
    }
?>
