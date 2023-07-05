<div class="col-6 offset-1 home_element">
    <?php
        include_once('lib/get/get_all_exstudente.php');
        $res = get_all_exstudente();
        if (pg_num_rows($res)==0){
            echo "<h4>Ancora nessuno ex studente</h4>";
        }else{
        ?>
        <h4>Elenco ex-studenti</h4>
        <table class="table table-striped">
            <thead>
                <th>Matricola</th>
                <th>Corso</th>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Iscrizione</th>
                <th>Fine</th>
                <th>Laurea</th>
            </thead>
            <tbody>
            <?php
            include_once('lib/get/get_exstudente_bio.php');
            include_once('lib/get/get_laurea.php');
            while ($studente = pg_fetch_assoc($res)){
                $info_studente = get_exstudente_info($studente['matricola'], $studente['idcorso']);
                $reslaurea = get_thelaurea($studente['matricola'], $studente['idcorso']);
                echo '<tr>';
                    echo '<td>' . $studente['matricola'] . '</td>';
                    echo '<td>' . $studente['idcorso'] . '</td>';
                    echo '<td>' . $info_studente['nome'] . '</td>';
                    echo '<td>' . $info_studente['cognome'] . '</td>';
                    echo '<td>' . date_format(new DateTime($studente['dataiscrizione']), 'd/m/Y') . '</td>';
                    echo '<td>' . date_format(new DateTime($studente['datarimozione']), 'd/m/Y') . '</td>';
                    $laurea="Rinuncia";
                    $l = pg_fetch_assoc($reslaurea);
                    if (!is_null($l['voto'])){
                        $laurea = $l['voto'];
                        if ($l['lode']=="t"){$laurea+="L";}
                    }
                    echo '<td>' . $laurea . "</td>";
                echo '</tr>';
            }
            ?>
            <tbody>
        </table>
        <?php    
        }
    ?>
</div>