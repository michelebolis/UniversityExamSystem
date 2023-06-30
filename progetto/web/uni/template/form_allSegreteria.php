<div class="col-6 offset-1 home_element">
    <?php
        include_once('lib/get/get_all_segreteria.php');
        $res = get_all_segreteria();
        if (pg_num_rows($res)==0){
            echo "<h4>Ancora nessun utente segreteria inserito</h4>";
        }else{
        ?>
        <h4>Elenco utenti della segreteria</h4>
        <table class="table table-striped">
            <thead>
                <th>Nome</th>
                <th>Cognome</th>
            </thead>
            <tbody>
            <?php
            while ($segreteria = pg_fetch_assoc($res)){
                echo '<tr>';
                    echo '<td>' . $segreteria['nome'] . '</td>';
                    echo '<td>' . $segreteria['cognome'] . '</td>';
                echo '</tr>';
            }
            ?>
        <tbody>
    </table>
        <?php    
        }
    ?>
</div>