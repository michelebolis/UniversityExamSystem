<form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'].'?past=True';?>>
<?php
    if (!isset($_POST['corso'])){
        include_once('lib/get/get_past_corso.php');
        include_once('lib/get/get_corso.php');
        $res = get_past_corso($info['matricola']);
        if(pg_num_rows($res)==0){
            echo 'Non ci sono corsi di laurea passati frequentati';
        }else{
?>
    <div class="mb-3">    
        <label for="corso" class="form-label">Corso</label>
        <select name="corso" id="corso" class="form-select">
        <?php
            while($corso = pg_fetch_assoc($res)){
                $info_corso = get_corso($corso['idcorso']);
                echo '<option value="'. $corso['idcorso'] . '"';
                echo '>';
                echo $corso['idcorso'] . ' | ' . $info_corso['nome'];
                echo '</option>';
            }
        ?>
        </select>
        <button type="submit" class="btn btn-primary">Seleziona</button>
    </div>
<?php
        }
    }else{ 
?>
    <h5><?php  echo $info['nome'] . ' ' . $info['cognome'] . ' | Corso di laurea: ' . $_POST['corso'] ;?> </h5>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Insegnamento</th>
                <th scope="col">Docente</th>
                <th scope="col">Data</th>
                <th scope="col">Voto</th>
                <th scope="col">Lode</th>
            </tr>
        </thead>
        <tbody>
<?php
        include_once('lib/get/get_carriera_studente.php');
        include_once('lib/get/get_insegnamento.php');
        include_once('lib/get/get_utente_bio.php');
        $res= get_carriera_studente_past($info['matricola'], $_POST['corso']);
        while($esame = pg_fetch_assoc($res)){
            $insegnamento = get_insegnamento($esame['idinsegnamento']);
            $docente = get_utente_bio($esame['iddocente']);
            echo '<tr>';
                echo '<td>' . $insegnamento['nome'] . '</td>';
                echo '<td>' . $docente['nome'] . ' ' . $docente['cognome'] . '</td>';
                echo '<td>' . date_format(new DateTime($esame['data']), 'd/m/Y') . '</td>';
                echo '<td>' . $esame['voto'] . '</td>';
                echo '<td>'; if ($esame['lode']=='t'){echo 'Sì';}else{echo 'No';} ;echo '</td>';
            echo '<tr>';
        }
?>
        </tbody>
    </table>
<?php
    }
?>
</form>