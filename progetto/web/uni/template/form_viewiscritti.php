<form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'].'?next=True';?>>
<?php
    if (!isset($_POST['esame'])){
?>
    <div class="mb-2">
    <?php
        include_once('lib/get/get_next_exam_bydoc.php');
        $res = get_next_exam_bydoc($_SESSION['utente']);
        if (pg_num_rows($res)==0){
            echo 'Non ci sono esami in programma';
        }else{
            include_once('lib/get/get_insegnamento.php');
    ?>
        <label for="esame" class="form-label">Sessioni d'esame future</label>
        <select id="studente" class="form-select" name="esame">
        <?php         
            while($esame = pg_fetch_assoc($res)){
                $insegnamento = get_insegnamento($esame['idinsegnamento']);
                echo '<option value="'. $esame['idesame'] .'"' ;
                echo '>';
                echo $insegnamento['nome'] ;
                echo ' ' . date_format(new DateTime($esame['data']), 'd/m/Y') ;
                echo ' ' . date_format(new DateTime($esame['orario']), 'H:i');
                echo '</option>';
            }
        ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Seleziona</button>
<?php 
        }
    }else{
        include_once('lib/get/get_iscritti_esame.php');
        include_once('lib/get/get_esame.php');
        include_once('lib/get/get_insegnamento.php');
        $info_esame = get_esame($_POST['esame']);
        $info_insegnamento_esame = get_insegnamento($info_esame['idinsegnamento']);
        $res = get_iscritti_esame($_POST['esame']);
        if (pg_num_rows($res)==0){
            echo 'Non ci sono iscritti per l esame di ' . $info_insegnamento_esame['nome'] . ' 
            in data ' . date_format(new DateTime($info_esame['data']), 'd/m/Y') . ' : ' . date_format(new DateTime($info_esame['orario']), 'H:i');
        }else{
            include_once('lib/get/get_studente_bio.php');
            echo '<h4>Iscritti dell appello di ' . $info_insegnamento_esame['nome']. ' in data ' . date_format(new DateTime($info_esame['data']), 'd/m/Y') 
            . ' : ' . date_format(new DateTime($info_esame['orario']), 'H:i') . '</h4> ';
            ?>
            <table class="table table-striped">
                <thead>
                    <th>Matricola</th>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Corso di laurea</th>
                </thead>
            <?php
            while($studente = pg_fetch_assoc($res)){
                $info_stud = get_studente_bio($studente['matricola'], $studente['idcorso']);
                echo '<tr>';
                    echo '<td>' . $studente['matricola'] . '</td>';
                    echo '<td>' . $info_stud['nome'] . '</td>';
                    echo '<td>' . $info_stud['cognome'] . '</td>';
                    echo '<td>' . $studente['idcorso'] . '</td>';
                echo '</tr>';
            }
            echo '<tbody>
            </table>';
        }
    }
?>
</form>