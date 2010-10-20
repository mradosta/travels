<?php 
	
$out = null;


$out[] = $this->MyForm->submit(__('Save', true), array('class' => 'action', 'id' => 'save', 'div' => false));
//$out[] = $this->MyForm->submit(__('Cancel', true), array('onclick' => 'location.href="' . $referer . '"', 'type' => 'button', 'class' => 'action close_modal', 'id' => 'cancel', 'div' => false));
$out[] = $this->MyHtml->tag('span', $this->MyHtml->link('cancel', array('controller' => $controller, 'action' => 'index')), array('class' => 'cancel'));


echo $this->MyHtml->tag('div', $out, array('id' => 'footer'));	