<?php 
	
$out[] = $this->MyForm->create('Ocupation', array('class' => 'ajax_formx'));

$content[] = $this->Form->input(
	'charter_id'
);

$content[] = $this->Form->input('weekly', array('type' => 'text'));
$content[] = $this->Form->input('fortnightly', array('type' => 'text'));

$out[] = $this->MyHtml->tag('div', $content, array('id' => 'container'));

$out[] = $this->element('footer');

$out[] = $this->MyForm->end();

echo implode("\n", $out);