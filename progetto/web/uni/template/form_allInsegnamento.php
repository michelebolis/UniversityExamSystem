<div class="col-6 offset-1 home_element">
    <?php
        include_once('lib/get/get_all_insegnamento.php');
        $res = get_all_insegnamento();
        if (pg_num_rows($res)==0){
            echo "<h4>Ancora nessun insegnamento inserito</h4>";
        }else{
        ?>
        <h4>Elenco insegnamenti</h4>
        <table class="table table-striped">
            <thead>
                <th>Nome</th>
                <th>Crediti</th>
                <th>Docente responsabile</th>
            </thead>
            <tbody>
            <?php
            include_once('lib/get/get_utente_bio.php');
            while ($insegnamento = pg_fetch_assoc($res)){
                echo '<tr>';
                    echo '<td>' . $insegnamento['nome'] . '</td>';
                    echo '<td>' . $insegnamento['crediti'] . '</td>';
                    if(is_null($insegnamento['iddocente'])){
                        $info_docente = "Nessun docente";
                    }else{
                        $docente = get_utente_bio($insegnamento['iddocente']);
                        $info_docente = $docente['nome'] . " " . $docente['cognome'];
                    }
                    echo '<td>' . $info_docente .  '</td>';
                echo '</tr>';
            }
            ?>
        <tbody>
    </table>
        <?php    
        }
    ?>
</div>