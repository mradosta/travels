<?php

$out[] = $this->MyHtml->tag('h2',
	__('Hotels', true),
	array('id' => 'tasks_title', 'class' => 'hotels')
);


$links = null;
$links[] = $this->MyHtml->link(
	__('Add Hotel', true),
	array(
		'controller'	=> 'hotels',
		'action'		=> 'add',
	),
	array('class' => 'cancel', 'title' => __('Add charter', true))
);
$out[] = $this->MyHtml->tag('div', $this->element('actions', array('links' => $links)), array('class' => 'clear-both'));

/** The grid */
$header	= null;
$header[] = __('Action', true);
$header[] = __('Name', true);

$head = $this->MyHtml->tag('thead', $this->MyHtml->tableHeaders($header));

$body = array();
foreach ($data as $record) {

	$td = null;
	$actions = null;
	$actions[] = $this->MyHtml->image(
		'view.png',
		array(
			'class' => 'open_modal',
			'title' => __('View', true) . ' ' . $record['Hotel']['name'],
			'url' => array(
				'controller' 	=> 'hotels',
				'action' 		=> 'view',
				$record['Hotel']['id']
			),
		)
	);
	$actions[] = $this->MyHtml->image(
		'edit.png',
		array(
			'class' => 'open_modal',
			'title' => __('Edit', true) . ' ' . $record['Hotel']['name'],
			'url' => array(
				'controller' 	=> 'hotels',
				'action' 		=> 'add',
				$record['Hotel']['id']
			),
		)
	);

	$td[] = $this->MyHtml->tag('td', $actions);
	$td[] = $this->MyHtml->tag('td', $record['Hotel']['name']);

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

$out[] = $this->MyPaginator->getNavigator(false);

echo implode("\n", $out);
echo $this->Js->writeBuffer();
