<div class="col-6 offset-1 home_element">
    <?php 
    include_once('lib/get/get_insegnamenti.php');
    $res = get_insegnamenti($_SESSION['utente']);
    if (pg_num_rows($res)==0){
        echo '<h4>Nessun insegnamento di cui è responsabile</h4>';
    }else{
    ?>
    <h4>Elenco insegnamenti di cui è responsabile <?php echo $info['nome'] . " " . $info['cognome']  ?></h4>
        <table class="table table-striped">
        <thead>
            <th>Insegnamento</th>
            <th>Crediti</th>
        </thead>
        <tbody>
        <?php
        include_once('lib/get/get_insegnamento.php');
        while ($insegnamento = pg_fetch_assoc($res)){
            $info_insegnamento = get_insegnamento($insegnamento['idinsegnamento']);
            echo '<tr>';
                echo '<td>' . $info_insegnamento['nome'] . '</td>';
                echo '<td>' . $info_insegnamento['crediti'] . '</td>';
            echo '</tr>';
        }
        ?>
        <tbody>
    </table>
    <?php
    }
    ?>
</div>