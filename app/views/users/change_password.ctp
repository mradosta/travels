<?php

$out[] = $this->MyForm->create('User', array('class' => 'ajax_form'));
if (empty($id)) {
	$content[] = $this->MyForm->input(
			'current', array('label' => __('Current', true), 'type' => 'password')
	);
} else {
	$content[] = $this->MyForm->input(
		'id',
		array('type' => 'hidden', 'value' => $id)
	);
}

$content[] = $this->MyForm->input(
		'new_password',
		array('label' => __('New password', true), 'type' => 'password')
);
$content[] = $this->MyForm->input(
		'retype_password',
		array('label' => __('Retype password', true), 'type' => 'password')
);

$out[] = $this->MyHtml->tag('div', $content, array('id' => 'container'));

$out[] = $this->element('footer', array('controller' => 'passengers'));

$out[] = $this->MyForm->end();

echo implode("\n", $out);