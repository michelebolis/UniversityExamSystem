<h5> Personal info </h5>    
<table class="table table-striped">
    <tr>
        <td>Nome</td>
        <td><?php echo $info['nome'];?></td>
    </tr>
    <tr>
        <td>Cognome</td>
        <td><?php echo $info['cognome'];?></td>
    </tr>
    <tr>
        <td>Ruolo</td>
        <td><?php echo $_SESSION['ruolo'];?></td>
    </tr>
    <tr>
        <td>Email</td>
        <td><?php echo $info['email'];?></td>
    </tr>
    <tr>
        <td>Recapito telefonico</td>
        <td><?php echo $info['cellulare'];?></td>
    </tr>
    <tr>
        <td>Codice fiscale</td>
        <td><?php echo $info['codicefiscale'];?></td>
    </tr>
</table>