<?php 
	
$out[] = $this->MyForm->create('Hotel');

$content[] = $this->MyForm->input(
	'destination_id', array('options' => $destinations)
);

$content[] = $this->MyForm->input('name');

$out[] = $this->MyHtml->tag('div', $content, array('id' => 'container'));

$out[] = $this->element('footer', array('controller' => 'hotels'));


$out[] = $this->MyForm->end();

echo implode("\n", $out);