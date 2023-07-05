<?php
    include_once('lib/insert/registrazione_esito_esame.php');
?>
    <form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'] . "?getAll=True";?>>
    <?php
        if(!isset($_POST['esame'])) {
            include_once('lib/get/get_past_exam.php');
            $res = get_past_exam($_SESSION['utente']);
            if (pg_num_rows($res)==0){
                echo 'Non ci sono sessioni di esame';
            }else{
?>
        <div class="mb-3">     
            <label for="esame" class="form-label">Esami</label>
            <select id="esame" class="form-select" name="esame">
            <?php
                include_once('lib/get/get_insegnamento.php');
                while($esame = pg_fetch_assoc($res)){
                    echo '<option value='. $esame['idesame'] ;
                    if (isset($_POST['esame']) && $_POST['esame']==$esame['idesame']){
                        echo ' selected';
                    }
                    echo '>';
                    $insegnamento=get_insegnamento($esame['idinsegnamento']);
                    echo $insegnamento['nome'].' | '. date_format(new DateTime($esame['data']), 'd/m/Y');
                    echo '</option>';
                }
            ?>
            </select>
            <button type="submit" class="btn btn-primary" style="margin-top:20px;">Cerca iscritti</button>
        </div>
<?php 
            }
        }else{
            include_once('lib/get/get_all_esito.php');
            $res = get_all_esito($_POST['esame']);
            if (pg_num_rows($res)==0){
                echo 'Non ci sono iscritti per l esame selezionato';
            }else{
                include_once('lib/get/get_esame.php');
                include_once('lib/get/get_insegnamento.php');
                $esame = get_Esame($_POST['esame']); 
                $insegnamento = get_insegnamento($esame['idinsegnamento']);
?>
        <h4>Esame di <?php echo $insegnamento['nome'] . " del " . date_format(new DateTime($esame['data']), 'd/m/Y'); ?></h4>
        <table class="table table-striped">
            <head>
                <th>Matricola</th>
                <th>Corso</th>
                <th>Stato</th>
                <th>Voto</th>
            </head>
            <tbody>
            <?php
                while($esito = pg_fetch_assoc($res)){
                echo "<tr>";
                    echo "<td>" . $esito['matricola'] . "</td>";
                    echo "<td>" . $esito['idcorso'] . "</td>";
                    echo "<td>" . $esito['stato'] . "</td>";
                    echo "<td>" . $esito['voto']; if($esito['lode']=="t"){echo 'L';} echo "</td>";
                echo "<tr>";
                }
            ?>
            </tbody>
        </table>
    <?php
            }
        }
    ?>
    </form>