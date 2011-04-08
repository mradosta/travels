<?php 
	
$out[] = $this->MyForm->create('Charter');

$content[] = $this->MyForm->input(
	'destination_id', array('options' => $destinations)
);

$content[] = $this->MyForm->input('date', array('type' => 'text', 'class' => 'datepicker'));
$content[] = $this->MyForm->input('time', array('type' => 'text', 'label' => __('Hora', true)));
$content[] = $this->MyForm->input('description', array('type' => 'text'));
$content[] = $this->MyForm->input('airline', array('type' => 'text', 'label' => __('Compañia Aerea', true)));
$content[] = $this->MyForm->input('flight_number', array('type' => 'text', 'label' => __('Numero de Vuelo', true)));
$content[] = $this->MyForm->input('route', array('type' => 'text', 'label' => __('Ruta', true)));
$content[] = $this->MyForm->input('weekly', array('type' => 'text'));
$content[] = $this->MyForm->input('fortnightly', array('type' => 'text'));
$content[] = $this->MyForm->input('reserved', array('type' => 'text', 'label' => __('Reserved', true)));

$out[] = $this->MyHtml->tag('div', $content, array('id' => 'container'));

$out[] = $this->element('footer', array('controller' => 'charters'));


$out[] = $this->MyForm->end();

echo implode("\n", $out);