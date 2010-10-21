
Pasajero <?php echo (($passenger['Passenger']['state'] == 'authorized') ? 'AUTORIZADO' : 'DESAUTORIZADO');?>:

Nombre: <?php echo $passenger['Passenger']['first_name'];?>

Apellido:  <?php echo $passenger['Passenger']['first_last_name'];?>

Fecha Nacimiento:  <?php echo $passenger['Passenger']['birthday'];?>

Dni:  <?php echo $passenger['Passenger']['dni'];?>

Telefono:  <?php echo $passenger['Passenger']['phone'];?>

Email:  <?php echo $passenger['Passenger']['email'];?>


Información del charter

Destino: <?php echo $passenger['Charter']['description'];?>

Fecha: <?php echo date('d-m-Y', strtotime($passenger['Charter']['date']));?>

Tipo: <?php echo $passenger['Passenger']['type'];?>


Ver pasajero <?php echo BASE_URL . 'passengers/view/' . $passenger['Passenger']['id']; ?>
