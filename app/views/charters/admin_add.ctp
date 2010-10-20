<?php 
	
$out[] = $this->MyForm->create('Charter', array('class' => 'ajax_formx'));

$content[] = $this->MyForm->input(
	'destination_id', array('options' => $destinations)
);

$content[] = $this->MyForm->input('date', array('type' => 'text', 'class' => 'datepicker'));
$content[] = $this->MyForm->input('time', array('type' => 'text'));
$content[] = $this->MyForm->input('description', array('type' => 'text'));
$content[] = $this->MyForm->input('weekly', array('type' => 'text'));
$content[] = $this->MyForm->input('fortnightly', array('type' => 'text'));
$content[] = $this->MyForm->input('reserved', array('type' => 'text'));

$out[] = $this->MyHtml->tag('div', $content, array('id' => 'container'));

$out[] = $this->element('footer', array('controller' => 'charters'));


$out[] = $this->MyForm->end();

echo implode("\n", $out);