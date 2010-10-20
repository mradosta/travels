<?php 
	
$out[] = $this->MyForm->create('Passenger', array('class' => 'ajax_formx'));

$content[] = $this->MyForm->input(
	'charter_id'
);
$content[] = $this->MyForm->input('type');
$content[] = $this->MyForm->input('amount', array('label' => 'Passengers'));

//$out[] = $this->MyHtml->link('Add 3 passengers', array('controller' => false), array('id' => 'add_passenger'));

$out[] = $this->MyHtml->tag('div', $content, array('id' => 'container', 'class' => 'passenger_data'));

$out[] = $this->element('footer', array('controller' => 'passengers'));

$out[] = $this->MyForm->end();

echo implode("\n", $out);