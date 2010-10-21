<?php 
	
$out[] = $this->MyForm->create('Passenger', array('class' => 'ajax_formx'));

$content[] = $this->MyForm->input('id', array('type' => 'hidden', 'value' => $id));
$content[] = $this->MyForm->input('first_name', array('type' => 'text'));
$content[] = $this->MyForm->input('last_name', array('type' => 'text'));
$content[] = $this->MyForm->input('birthday', array('type' => 'text', 'class' => 'datepicker'));
$content[] = $this->MyForm->input('dni', array('type' => 'text'));
$content[] = $this->MyForm->input('email', array('type' => 'text'));
$content[] = $this->MyForm->input('phone', array('type' => 'text'));

//$out[] = $this->MyHtml->link('Add 3 passengers', array('controller' => false), array('id' => 'add_passenger'));

$out[] = $this->MyHtml->tag('div', $content, array('id' => 'container', 'class' => 'passenger_data'));

$out[] = $this->element('footer', array('controller' => 'passengers'));

$out[] = $this->MyForm->end();

echo implode("\n", $out);