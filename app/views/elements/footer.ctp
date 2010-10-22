<?php 
	
$out = null;


$out[] = $this->MyForm->submit(__('Save', true), array('class' => 'action', 'id' => 'save', 'div' => false));
$out[] = $this->MyHtml->tag('span', $this->MyHtml->link(__('Cancel', true), array('controller' => $controller, 'action' => 'index')), array('class' => 'cancel'));


echo $this->MyHtml->tag('div', $out, array('id' => 'footer'));	