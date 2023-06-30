<form class="col-6 offset-1" method="POST" action=<?php echo $_SERVER['PHP_SELF'].'?iscritti=True';?>>
<?php
    if (!isset($_POST['sessione'])){
?>
    <div class="mb-2">
    <?php
        include_once('lib/get/get_all_sessione.php');
        $res = get_all_sessione();
        if (pg_num_rows($res)==0){
            echo 'Non ci sono sessioni di laurea';
        }else{
            include_once('lib/get/get_insegnamento.php');
    ?>
        <label for="sessione" class="form-label">Sessioni di laurea</label>
        <select id="sessione" class="form-select" name="sessione">
        <?php         
            while($sessione = pg_fetch_assoc($res)){
                echo '<option value="'. $sessione['idcorso'] . ';' . $sessione['data'] .'"';
                if (isset($_POST['info']) && $_POST['info']==$sessione['idcorso'] . ';'. $sessione['data']){
                    echo ' selected';
                }
                echo '>';
                echo $sessione['idcorso'] . ' | ' . date_format(new DateTime($sessione['data']), 'd/m/Y');
                echo '</option>';
            }
        ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Cerca iscritti</button>
<?php 
        }
    }else{
        $info = explode(";", $_POST['sessione']);
        include_once('lib/get/get_all_iscritti_laurea.php');
        $res = get_all_iscritti_laurea($info[0], $info[1]);
        if (pg_num_rows($res)==0){
            echo 'Non ci sono iscritti per la sessione di laurea di ' . $info[0] . ' 
            in data ' . date_format(new DateTime($info[1]), 'd/m/Y');
        }else{
            echo '<h4>Iscritti alla sessione di laurea di ' . $info[0] . ' in data ' . date_format(new DateTime($info[1]), 'd/m/Y') . '</h4> ';
            ?>
            <table class="table table-striped">
                <thead>
                    <th>Matricola</th>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Voto</th>
                    <th>Incremento</th>
                    <th>Lode</th>
                </thead>
                <tbody>
                <?php
                include_once('lib/get/get_studente_bio.php');
                include_once('lib/get/get_exstudente_bio.php');
                while ($studente = pg_fetch_assoc($res)){
                    $info_studente = get_studente_bio($studente['matricola'], $studente['idcorso']);
                    if (is_null($info_studente['matricola'])){
                        $info_studente = get_exstudente_info($studente['matricola']);
                    }
                    echo '<tr>';
                        echo '<td>' . $studente['matricola'] . '</td>';
                        echo '<td>' . $info_studente['nome'] . '</td>';
                        echo '<td>' . $info_studente['cognome'] . '</td>';
                        echo '<td>' . $studente['voto'] . '</td>';
                        echo '<td>' . $studente['incrementovoto'] . '</td>';
                        echo '<td>'; if($studente['lode']=="t"){echo 'SI';}else{echo "NO";}; echo '</td>';
                    echo '</tr>';
                }
                ?>
                <tbody>
            </table>
            <?php
        }
    }
?>
</form>