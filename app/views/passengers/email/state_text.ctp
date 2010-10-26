
Pasajero <?php echo (($passenger['Passenger']['state'] == 'authorized') ? 'AUTORIZADO' : 'DESAUTORIZADO');?>:
<br />
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
<br />

<p>Información del charter</p>
<br />
Destino: <?php echo $passenger['Charter']['description'];?>
<br />
Fecha: <?php echo date('d-m-Y', strtotime($passenger['Charter']['date']));?>
<br />
Tipo: <?php echo $passenger['Passenger']['type'];?>
<br />

Ver pasajero <a href="<?php echo Router::url('/', true) . 'passengers/view/' . $passenger['Passenger']['group']; ?>"><?php echo Router::url('/', true) . 'passengers/view/' . $passenger['Passenger']['group']; ?></a>
