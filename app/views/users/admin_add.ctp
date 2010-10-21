<?php 

/** Actions */
if (!empty($this->data['User']['id'])) {
	$links =  null;
	$links[] = $this->MyHtml->link(
		__('Delete', true),
		array(
			'controller' 	=> 'users',
			'action' 		=> 'delete',
			$this->data['User']['id']
		),
		array(
			'title' => __('Delete passenger', true),
			'class' => 'cancel'
		),
		__('Are your sure to delete the Passenger?', true)
	);

	$out[] = $this->element('actions',
		array('links' => $links)
	);

	$links =  null;
	$links[] = $this->MyHtml->link(
		__('Change password', true),
		array(
			'controller' 	=> 'users',
			'action' 		=> 'change_password',
			$this->data['User']['id']
		),
		array(
			'title' => __('Change password', true),
			'class' => 'cancel'
		)
	);

	$out[] = $this->element('actions',
		array('links' => $links)
	);
}

$out[] = $this->MyForm->create('User', array('class' => 'ajax_formx'));


$content[] = $this->MyForm->input('username', array('label' => __('User Name', true)));
if (!empty($id)) {
	$content[] = $this->MyForm->input('id', array('value' => $id, 'type' => 'hidden'));
}
$content[] = $this->MyForm->input('full_name', array('label' => __('Full Name', true)));
$content[] = $this->MyForm->input('email', array('label' => __('Email', true)));
if (empty($this->data['User']['id'])) {
	$content[] = $this->MyForm->input('password', array('label' => __('Password', true)));
	$content[] = $this->MyForm->input('re-password',
		array('type' => 'password', 'label' => __('Retype Password', true))
	);
}
$content[] = $this->MyForm->input('type', array('label' => __('Type', true), 'default' => 'agency'));


	
$out[] = $this->MyHtml->tag('div', $content, array('id' => 'container'));


$out[] = $this->element('footer', array('controller' => 'users'));

$out[] = $this->MyForm->end();

echo implode("\n", $out);