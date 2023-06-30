<div class="col-6 offset-1 home_element">
    <?php
        include_once('lib/get/get_all_corso.php');
        $res = get_all_corso();
        if (pg_num_rows($res)==0){
            echo "<h4>Ancora nessun corso inserito</h4>";
        }else{
        ?>
        <h4>Elenco corsi di laurea</h4>
        <table class="table table-striped">
            <thead>
                <th>Codice</th>
                <th>Nome</th>
                <th>Anni totali</th>
                <th>Valore lode</th>
                <th>Attivo</th>
            </thead>
            <tbody>
            <?php
            while ($corso = pg_fetch_assoc($res)){
                echo '<tr>';
                    echo '<td>' . $corso['idcorso'] . '</td>';
                    echo '<td>' . $corso['nome'] . '</td>';
                    echo '<td>' . $corso['annitotali'] . '</td>';
                    echo '<td>' . $corso['valorelode'] . '</td>';
                    echo '<td>' ; if($corso['attivo']=="t"){echo 'SI';} ; echo '</td>';
                echo '</tr>';
            }
            ?>
        <tbody>
    </table>
        <?php    
        }
    ?>
</div>