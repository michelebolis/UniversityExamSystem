<div class="col-6 offset-1 home_element">
    <?php
        include_once('lib/get/get_past_insegnamento_bydoc.php');
        $res = get_past_insegnamento_bydoc($_SESSION['utente']);
        if (pg_num_rows($res)==0){
            echo "<h4>Ancora nessun insegnamento passato</h4>";
        }else{
        ?>
        <h4>Elenco insegnamenti passati di <?php echo $info['nome'] . " " . $info['cognome']; ?></h4>
        <table class="table table-striped">
            <thead>
                <th>Nome</th>
                <th>Crediti</th>
                <th>Anno inizio</th>
                <th>Anno fine</th>
            </thead>
            <tbody>
            <?php
            while ($insegnamento = pg_fetch_assoc($res)){
                echo '<tr>';
                    echo '<td>' . $insegnamento['nome'] . '</td>';
                    echo '<td>' . $insegnamento['crediti'] . '</td>';
                    echo '<td>' . $insegnamento['annoinizio'] .  '</td>';
                    echo '<td>' . $insegnamento['annofine'] .  '</td>';
                echo '</tr>';
            }
            ?>
        <tbody>
    </table>
        <?php    
        }
    ?>
</div>