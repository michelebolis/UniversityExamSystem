<?php
    include_once('lib/insert/iscrizione_esame.php');
    function printform($err){
?>
        <form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'];?>>
    <?php if (!is_null($err)){echo $err;}
        if(!isset($_POST['insegnamento'])) {
            include_once('lib/get/get_studente_bio.php');
            include_once('lib/get/get_all_insegnamento_mancante.php');
            $info = get_studente_biobyid($_SESSION['utente']);
            $res = get_all_insegnamento_mancante($info['matricola']);
            if (pg_num_rows($res)==0){
                echo 'Non hai piu esami da sostenere';
            }else{
    ?>
            <div class="mb-3">  
                <label for="insegnamento" class="form-label">Esami</label>
                <select id="insegnamento" class="form-select" name="insegnamento">
                <?php
                    include_once('lib/get/get_insegnamento.php');
                    while($insegnamento = pg_fetch_assoc($res)){
                        $info_insegnamento = get_insegnamento($insegnamento['idinsegnamento']);
                        echo '<option value='. $insegnamento['idinsegnamento'] ;
                        if (isset($_POST['insegnamento']) && $_POST['insegnamento']==$insegnamento['idinsegnamento']){
                            echo ' selected';
                        }
                        echo '>';
                        echo $info_insegnamento['nome'].' | Crediti: '.$info_insegnamento['crediti'] ;
                        echo '</option>';
                    }
                ?>
                </select>
                <button type="submit" class="btn btn-primary" style="margin-top:20px;">Cerca sessione d'esame</button>
            </div>
    <?php 
            }
        }else{
            include_once('lib/get/get_sessione_esame.php');
            $res = get_sessione_esame($_POST['insegnamento']);
            if (pg_num_rows($res)==0){
                echo 'Non ci sono sessioni di esame per questo insegnamento';
            }else{
                $toprint='';
                include_once('lib/is_iscritto.php');
                include_once('lib/get/get_studente_bio.php');
                $info = get_studente_biobyid($_SESSION['utente']);
                while($esame = pg_fetch_assoc($res)){
                    $iscritto = is_iscritto($info['matricola'], $esame['idesame'])['is_iscritto'];
                    if($iscritto=='t'){continue;}
                    $toprint.= '<option value="'. $esame['idesame'].'"' ;
                    if (isset($_POST['esame']) && $_POST['esame']==$esame['idesame']){
                        $toprint.= ' selected';
                    }
                    $toprint.= '>';
                    $toprint.=  date_format(new DateTime($esame['data']), 'd/m/Y') .' ' . $esame['orario'];
                    $toprint.= '</option>';
                }
                if ($toprint==''){
                    echo 'Non ci sono sessioni di esame a cui non ti sei gia iscritto per questo insegnamento';
                }else{
    ?>
            <label for="insegnamento" class="form-label">Insegnamento</label>
            <input id="insegnamento" class="form-control" name="insegnamento" type="text" readonly value=<?php echo $_POST['insegnamento'];?>>
            <div class="mb-3">
                <label for="esame" class="form-label">Data</label>
                <select id="esame" class="form-select" name="esame">
                <?php
                    echo $toprint;   
                ?>
                </select>
                <button type="submit" class="btn btn-primary">Iscriviti</button>
            </div>
    <?php
                }
            }
        }
    ?>
        </form>
<?php
    }
    
    if (isset($_POST['insegnamento']) && isset($_POST['esame'])){
        $err=iscrizione_esame($info['matricola'],$_POST['esame']);
        if (!is_null($err)){
            printform($err);
        }else{
            echo '<div class="home_element col-6 offset-1">Iscrizione all esame eseguita correttamente</div>';
        }
    }else{
        printform(null);
    }
?>
