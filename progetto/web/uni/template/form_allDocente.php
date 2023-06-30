<div class="col-6 offset-1 home_element">
    <?php
        include_once('lib/get/get_all_docente.php');
        $res = get_all_docente();
        if (pg_num_rows($res)==0){
            echo "<h4>Ancora nessun docente inserito</h4>";
        }else{
        ?>
        <h4>Elenco docenti</h4>
        <table class="table table-striped">
            <thead>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Inizio attività</th>
            </thead>
            <tbody>
            <?php
            include_once('lib/get/get_utente_bio.php');
            while ($docente = pg_fetch_assoc($res)){
                $info_docente = get_utente_bio($docente['iddocente']);
                echo '<tr>';
                    echo '<td>' . $info_docente['nome'] . '</td>';
                    echo '<td>' . $info_docente['cognome'] . '</td>';
                    echo '<td>' . date_format(new DateTime($docente['iniziorapporto']), 'd/m/Y') . '</td>';
                echo '</tr>';
            }
            ?>
        <tbody>
    </table>
        <?php    
        }
    ?>
</div>