<?php 
	
$out[] = $this->MyForm->create('Passenger');

$content[] = $this->MyForm->input(
	'charter_id'
);
$content[] = $this->MyForm->input(
	'hotel_id'
);
$content[] = $this->MyForm->input('meal_packages',
	array('options' => $meal_packages, 'label' => __('Meal Packages', true))
);
$content[] = $this->MyForm->input('type');
$content[] = $this->MyForm->input('amount', array('label' => __('Passengers', true), 'default' => '2'));

$out[] = $this->MyHtml->tag('div', $content, array('id' => 'container', 'class' => 'passenger_data'));

$out[] = $this->element('footer', array('controller' => 'passengers'));

$out[] = $this->MyForm->end();

echo implode("\n", $out);