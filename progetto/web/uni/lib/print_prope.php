<?php
function print_prope($corso_laurea){
    include_once('lib/get/get_manifesto.php');
    $res = get_manifesto($corso_laurea);
    if (pg_num_rows($res)==0){
        echo 'Non vi è ancora nessun insegnamento nel manifesto per il corso di laurea ' . $corso_laurea;
    }else{
    ?>
    <div>
        <?php
        include_once('lib/get/get_insegnamento.php');
        include_once('lib/get/get_propedeuticita.php');
        echo '<ul>';
        while($insegnamento = pg_fetch_assoc($res)){
            $info_insegnamento = get_insegnamento($insegnamento['idinsegnamento']);
            echo '<li style="margin: 5px 0px;">' . $info_insegnamento['nome'] . '</li>';
            $res3 = get_propedeuticita($info_insegnamento['idinsegnamento']);
            if(pg_num_rows($res3)==0){
                echo 'Nessun corso richiesto per questo insegnamento';
            }else{
                echo '<table class="table table-striped">';
                while($propedeuticita = pg_fetch_assoc($res3)){
                    $insegnamentoRichiesto = get_insegnamento($propedeuticita['insegnamentorichiesto']);
                    echo ' <tr><td>' . $insegnamentoRichiesto['nome'] . '<td></tr>';
                }
                echo '</table>';
            }
        }
        echo '</ul>';
        ?>
    </div>
    <?php
    }
}
?>