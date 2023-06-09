<?php
    include('template/navbar_docente.php');
?>
<div class="container">
    <div class="row" >
        <div class="col-6 home_element">
            <?php
            include_once('template/personal_info.php')
            ?>
        </div>
        <div class="col offset-1 home_element">
            <h5> Next exam </h5>
            <?php 
                include_once('lib/get/get_next_exam_bydoc.php');
                $res = get_next_exam_bydoc($_SESSION['utente']);
                if (pg_num_rows($res)==0){
                    echo 'Non ci sono esami in programma';
                }else{
                    include_once('lib/get/get_insegnamento.php');
                ?>
                <table class="table table-striped">
                    <thead>
                        <th>Insegnamento</th>
                        <th>Data</th>
                        <th>Orario</th>
                    <thead>
                    <tbody>
                <?php
                    while($esame = pg_fetch_assoc($res)){
                        $insegnamento = get_insegnamento($esame['idinsegnamento']);
                        echo '<tr>';
                            echo '<td>' . $insegnamento['nome'] . '</td>';
                            echo '<td>' . date_format(new DateTime($esame['data']), 'd/m/Y') . '</td>';
                            echo '<td>' . date_format(new DateTime($esame['orario']), 'H:i') . '</td>';
                        echo '</tr>';
                    };
                ?>
                    </tbody>
                </table>
                <?php
                }   
            ?>
        </div>    
    </div>  
</div>
