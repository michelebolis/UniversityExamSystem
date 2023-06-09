<?php
    function print_carriera($matricola, $corso){
        include_once('lib/get/get_studente_bio.php');
        $info = get_studente_bio($matricola, $corso);
        ?>    
            <h5><?php  echo $info['nome'] . ' ' . $info['cognome'] . ' | Corso di laurea: ' . $corso ;?> </h5>
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
                $res= get_carriera_studente($matricola);
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