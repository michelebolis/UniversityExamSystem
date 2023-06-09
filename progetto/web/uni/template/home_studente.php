<?php
    include('template/navbar_studente.php');
?>
<div class="container justify-content-center">
    <div class="row" >
        <div class="col home_element">
        <?php
            include_once('template/personal_info.php')
        ?>
        </div>
        <?php if (!isset($exstudente)){ 
        ?>
        <div class="col offset-1 home_element">
            <h5> Next exam </h5>
            <?php
                include_once('lib/get/get_all_iscrizione.php');
                include_once('lib/get/get_insegnamento.php');
                $res = get_all_nextiscrizione($info['matricola']);
                if (pg_num_rows($res)==0){
                    echo '<tr>Non hai iscrizioni ad esami futuri</tr>';
                }else{
            ?>
            <table name="esame" id="esame" class="table table-striped">
                <thead>
                    <td>Data</td>
                    <td>Orario</td>
                    <td>Insegnamento</td>
                </thead>
            <?php
                while($esame = pg_fetch_assoc($res)){           
                    echo '<tr>';
                        echo '<td>' . date_format(new DateTime($esame['data']), 'd/m/Y') . '</td>';
                        echo '<td>' . $esame['orario'] . '</td>';
                        $insegnamento = get_insegnamento($esame['idinsegnamento']);
                        echo '<td>' . $insegnamento['nome'] . '</td>';
                    echo '</tr>';
                }
            echo '</table>';
                }        
            ?>
        </div>  
    </div>    
    <div class="row justify-content-center" >
        <div class="col-5 home_element">
            <h5> Statistiche carriera </h5>   
            <table class="table table-striped">
                <?php
                    include_once('lib/get/get_studente_stats.php');
                    $stats = get_studente_stats($info['matricola'], $info['idcorso']);
                ?>
                <tr>
                    <td>Corso di laurea</td>
                    <td><?php echo $info['idcorso'];?></td>
                </tr>
                <tr>
                    <td>Media</td>
                    <td><?php echo round($stats['media'], 2);?></td>
                </tr>
                <tr>
                    <td>Crediti</td>
                    <td><?php echo $stats['crediti'];?></td>
                </tr>
                <tr>
                    <td>Esami passati</td>
                    <td><?php echo get_num_esami_passati($info['matricola'], $info['idcorso']);?></td>
                </tr>
            </table>
        </div>   
        <?php
        } 
        include_once('lib/get/get_laurea.php');
        $res = get_laurea($info['matricola']);
        if (pg_num_rows($res)>0){
            ?>
        <div class="col offset-1 home_element">
            <h5> Laurea/e </h5>    
            <table name="esame" id="esame" class="table table-striped">
                <thead>
                    <th>Corso di laurea</th>
                    <th>Voto finale</th>
                    <th>Data</th>
                </thead>
                <tbody>
            <?php
                include_once('lib/get/get_corso.php');
                while($laurea = pg_fetch_assoc($res)){      
                    $corso = get_corso($laurea['idcorso']);     
                    echo '<tr>';
                        echo '<td>' . $corso['idcorso'] . ' ' . $corso['nome'] . '</td>';                 
                        echo '<td>' . $laurea['voto'];
                        if ($laurea['lode']=='t'){echo 'L';}
                        echo '</td>';
                        echo '<td>' . date_format(new DateTime($laurea['data']), 'd/m/Y') . '</td>';     
                    echo '</tr>';
                }
            ?>
                </tbody>
            </table>
        </div>
            <?php
        }
    ?>
    </div>
</div>
