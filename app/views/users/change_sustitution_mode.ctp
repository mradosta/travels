<?php 

$out[] = $this->MyForm->create('User', array('class' => 'xajax_form'));

$content[] = $this->Form->input('user_id', array('label' => 'User to replace', 'empty' => true));

$out[] = $this->MyHtml->tag('div', $content, array('id' => 'container'));

$out[] = $this->element('footer');

$out[] = $this->MyForm->end();

echo implode("\n", $out);