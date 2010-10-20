<?php

$trs = null;
$out = null;
$links = null;
$links[] = $this->MyHtml->link(
	__('Print Code Table', true),
	array(
		'controller' 	=> 'users',
		'action' 		=> 'print_codes',
		User::get('/User/id')
	),
	array(
		'title' => __('Print code table', true)
	)
);

$out[] = $this->element('actions',
	array('links' => array(
		__('Actions', true) => $links
		)
	)
);


foreach ($codes as $row => $cols) {

	$tds = null;
	$tds[] = $row;

	foreach ($cols as $col => $value) {
		$tds[] = $value;
	}
	//$trs[] = $this->Html->tag('tr', implode('', $tds));
	$trs[] = $tds;
}

$headers = range('A', 'J');
array_unshift($headers,' ');

$out[] = $this->MyHtml->tag('div',
	$this->MyHtml->table($trs, $headers),
	array('class' => 'modal_width_actions labeled')
);
echo $this->MyHtml->tag('div', $out);

