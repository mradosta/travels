<p>La agencia "<?php echo strtoupper($passengers[0]['User']['full_name']);?>" a dado de alta un grupo de pasajeros</p>


<br />
<?php foreach ($passengers as $passenger) { ?>
Nombre: <?php echo $passenger['Passenger']['first_name'];?>
<br />
Apellido:  <?php echo $passenger['Passenger']['last_name'];?>
<br />
Fecha Nacimiento:  <?php echo $passenger['Passenger']['birthday'];?>
<br />
Dni:  <?php echo $passenger['Passenger']['dni'];?>
<br />
Telefono:  <?php echo $passenger['Passenger']['phone'];?>
<br />
Email:  <?php echo $passenger['Passenger']['email'];?>
<hr>
<br />

<?php } //end foreach ?>

<p>Información del charter</p>
<br />
Destino: <?php echo $passengers[0]['Charter']['description'];?>
<br />
Fecha: <?php echo date('d-m-Y', strtotime($passengers[0]['Charter']['date']));?>
<br />
Tipo: <?php echo $passengers[0]['Passenger']['type'];?>
<br />

Ver pasajero <a href="<?php echo Router::url('/', true) . 'passengers/view/' . $passengers[0]['Passenger']['group']; ?>"><?php echo Router::url('/', true) . 'passengers/view/' . $passengers[0]['Passenger']['group']; ?></a>