<?php

$out[] = $this->MyHtml->tag('h2',
	__('Ocupations', true),
	array('id' => 'tasks_title')
);

$links = null;
$links[] = $this->MyHtml->link(
	__('Add Ocupation', true),
	array(
		'controller'	=> 'ocupations',
		'action'		=> 'add',
	),
	array('class' => 'cancel', 'title' => __('Add ocupation', true))
);
echo $this->element('actions', array('links' => $links));


/** The grid */
$header	= null;
$header[] = __('Actions', true);
$header[] = __('Charter Description', true);
$header[] = __('Weekly', true);
$header[] = __('Fortnightly', true);


$head = $this->MyHtml->tag('thead', $this->MyHtml->tableHeaders($header));

$body = array();
foreach ($data as $record) {
	$td = null;
	$actions = null;
	$actions[] = $this->MyHtml->image(
		'view.png',
		array(
			'class' => 'open_modal',
			'title' => __('View', true) . ' ' . $record['Charter']['description'],
			'url' => array(
				'controller' 	=> 'ocupations',
				'action' 		=> 'view',
				$record['Ocupation']['id']
			),
		)
	);
	$actions[] = $this->MyHtml->image(
		'edit.png',
		array(
			'class' => 'open_modal',
			'title' => __('Edit', true) . ' ' . $record['Charter']['description'],
			'url' => array(
				'controller' 	=> 'ocupations',
				'action' 		=> 'add',
				$record['Ocupation']['id']
			),
		)
	);

	$td[] = $this->MyHtml->tag('td', $actions);
	$td[] = $this->MyHtml->tag('td', $record['Charter']['description']);
	$td[] = $this->MyHtml->tag('td', $record['Ocupation']['weekly']);
	$td[] = $this->MyHtml->tag('td', $record['Ocupation']['fortnightly']);

	$body[] = $this->MyHtml->tag('tr', $td);

}

if ($body != null) {
    $body = implode("\n", $body);
} else {
    $body = '';
}

$out[] = $this->MyHtml->tag('div',
	$this->MyHtml->tag('table', $head . $body),
	array('id' => 'grid')
);

echo implode("\n", $out);
echo $this->Js->writeBuffer();
