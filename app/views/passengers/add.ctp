<?php 
	
$out[] = $this->MyForm->create('Passenger');

//d($charters);
$content[] = $this->MyForm->input(
	'charter_id', array('empty' => true)
);
$content[] = $this->MyForm->input(
	'hotel_id'
);
$content[] = $this->MyForm->input('meal_packages',
	array('options' => $meal_packages, 'label' => __('Meal Packages', true))
);
$content[] = $this->MyForm->input('base',
	array('options' => $base, 'label' => __('Base', true))
);

$content[] = $this->MyForm->input('type');
$content[] = $this->MyForm->input('amount', array('label' => __('Passengers', true), 'default' => '2'));
$content[] = $this->MyForm->input('ejecutive_id', array('label' => __('Ejecutive', true)));

$out[] = $this->MyHtml->tag('div', $content, array('id' => 'container', 'class' => 'passenger_data'));

$out[] = $this->element('footer', array('controller' => 'passengers'));

$out[] = $this->MyForm->end();

echo implode("\n", $out);

?>
<script>

$(document).ready(function() {
	$('#PassengerCharterId').live('change', function() {
		if($(this).val().length != 0) {
			$.getJSON('<?php echo Router::url(array('controller' => 'hotels', 'action' => 'get')); ?>/' + $(this).val(),
				function(data) {
					var options = '';
					$.each(data, function(index, hotel) {
						options += '<option value="' + hotel.Hotel.id + '">' + hotel.Hotel.name + '</option>';
					});
					$('#PassengerHotelId').html(options);
				}
			);
		}
	});
});

</script>
