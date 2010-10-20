<?php 
	
$out[] = $this->MyForm->create('User', array('class' => 'ajax_formx'));


$content[] = $this->MyForm->input('username', array('label' => __('User Name', true)));
if (!empty($id)) {
	$content[] = $this->MyForm->input('id', array('value' => $id, 'type' => 'hidden'));
}
$content[] = $this->MyForm->input('full_name', array('label' => __('Full Name', true)));
$content[] = $this->MyForm->input('email', array('label' => __('Email', true)));
$content[] = $this->MyForm->input('password', array('label' => __('Password', true)));
$content[] = $this->MyForm->input('re-password', 
	array('type' => 'password', 'label' => __('Retype Password', true))
);
$content[] = $this->MyForm->input('type', array('label' => __('Type', true), 'default' => 'agency'));


	
$out[] = $this->MyHtml->tag('div', $content, array('id' => 'container'));


$out[] = $this->element('footer', array('controller' => 'users'));

$out[] = $this->MyForm->end();

echo implode("\n", $out);