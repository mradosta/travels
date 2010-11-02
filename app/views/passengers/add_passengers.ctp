<?php 
	
$out[] = $this->MyForm->create('Passenger', array('class' => 'ajax_formx', 'action' => 'add_passengers'));
$passangers = null;

$content[] = $this->MyForm->input('Extra.charter_data',
	array(
		'type' 	=> 'hidden',
		'value' => serialize($charter_data)
	)
);

for ($i = 0; $i < $charter_data['amount']; $i++) {
	$content[] = $this->MyHtml->tag('h3', __('Passenger', true) . ' ' . ($i + 1));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.first_name', array('type' => 'text'));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.charter_id', array('type' => 'hidden', 'value' => $charter_data['charter_id']));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.hotel_id', array('type' => 'hidden', 'value' => $charter_data['hotel_id']));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.meal_packages', array('type' => 'hidden', 'value' => $charter_data['meal_packages']));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.base', array('type' => 'hidden', 'value' => $charter_data['base']));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.user_id', array('type' => 'hidden', 'value' => User::get('/User/id')));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.type', array('type' => 'hidden', 'value' => $charter_data['type']));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.last_name', array('type' => 'text'));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.birthday', array('type' => 'text', 'class' => 'datepicker'));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.dni', array('type' => 'text', 'label' => __('Dni / Passport', true), 'after' => __('Viajes al caribe, solo pasaporte', true)));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.notes', array('type' => 'textarea'));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.phone', array('type' => 'text', 'class' => 'phone'));
	$content[] = $this->MyForm->input(
		'Passenger.' . $i . '.infoa',
		array(
			'type' 		=> 'select',
			'options'	=> array(0 => __('No', true), 1 => __('Yes', true))
		)
	);
	$passangers[] = $this->MyHtml->tag('div', $content, array('class' => 'passanger'));
	$content = null;
}

$out[] = $this->MyHtml->tag('div', $passangers, array('id' => 'container', 'class' => 'passenger_data'));

$out[] = $this->element('footer', array('controller' => 'passengers'));

$out[] = $this->MyForm->end();

echo implode("\n", $out);
?>
<script>

$(document).ready(function() {
	$('#save').live('click', function() {
		$('.phone').each(function() {
			if ($(this).val() == '') {
				$(this).val($('#Passenger0Phone').val());
			}
		});
	});
});

</script>