<?php 
	
$out[] = $this->MyForm->create('Destination', array('class' => 'ajax_formx'));

$content[] = $this->MyForm->input('name', array('type' => 'text'));

$out[] = $this->MyHtml->tag('div', $content, array('id' => 'container'));

$out[] = $this->element('footer', array('controller' => 'destinations'));


$out[] = $this->MyForm->end();

echo implode("\n", $out);