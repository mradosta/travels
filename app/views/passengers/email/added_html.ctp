<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<body>
		Nuevo pasajero agregado: <?php echo $passenger['Passenger']['first_name'] . ' ' . $passenger['Passenger']['first_last_name'];?>
		<br />
		Información del charter
		<br />
		Destino: <?php echo $passenger['Charter']['description'];?>
		Fecha: <?php echo date('d-m-Y', strtotime($passenger['Charter']['date']));?>
	</body>
</html>
