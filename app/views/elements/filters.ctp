<?php 


$filters[] = $this->MyForm->create(Inflector::Classify($this->name));

$actions[] = $this->MyForm->submit(__('Search', true),
	array(
		'type' 		=> 'submit',
		'class' 	=> 'button',
		'div' 		=> false,
		'label' 	=> false,
		'style'		=> 'float:left;margin-right:15px'
	)
);
$actions[] = $this->MyForm->submit(__('Clear', true),
	array(
		'type' 		=> 'reset',
		'class' 	=> 'button clear cancel',
		'div' 		=> false,
		'label' 	=> false,
		'style'		=> 'margin-right:0;'
	)
);
$filters[] = $this->MyHtml->tag('div', $actions, 'action');
foreach ($fields as $k => $v) {
	if (is_array($v)) {
		$filters[] = $this->MyForm->input($k, $v);
	} else {
		$filters[] = $this->MyForm->input($v);
	}
}



$filters[] = $this->MyForm->end();

echo $this->MyHtml->tag('div', $filters, array('id' => 'filters'));