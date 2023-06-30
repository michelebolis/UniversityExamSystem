<div class="col-6 offset-1 home_element">
    <?php
        include_once('lib/get/get_all_studente.php');
        $res = get_all_studente();
        if (pg_num_rows($res)==0){
            echo "<h4>Ancora nessuno studente inserito</h4>";
        }else{
        ?>
        <h4>Elenco studenti</h4>
        <table class="table table-striped">
            <thead>
                <th>Matricola</th>
                <th>Corso</th>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Data immatricolazione</th>
            </thead>
            <tbody>
            <?php
            include_once('lib/get/get_studente_bio.php');
            while ($studente = pg_fetch_assoc($res)){
                $info_studente = get_studente_bio($studente['matricola'], $studente['idcorso']);
                echo '<tr>';
                    echo '<td>' . $studente['matricola'] . '</td>';
                    echo '<td>' . $studente['idcorso'] . '</td>';
                    echo '<td>' . $info_studente['nome'] . '</td>';
                    echo '<td>' . $info_studente['cognome'] . '</td>';
                    echo '<td>' . date_format(new DateTime($studente['dataimmatricolazione']), 'd/m/Y') . '</td>';
                echo '</tr>';
            }
            ?>
            <tbody>
        </table>
        <?php    
        }
    ?>
</div>