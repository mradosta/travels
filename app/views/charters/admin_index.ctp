<?php

$out[] = $this->MyHtml->tag('h2',
	__('Charters', true),
	array('id' => 'tasks_title')
);

$links = null;
$links[] = $this->MyHtml->link(
	__('Add Charter', true),
	array(
		'controller'	=> 'charters',
		'action'		=> 'add',
	),
	array('class' => 'cancel', 'title' => __('Add charter', true))
);
echo $this->element('actions', array('links' => $links));


/** The grid */
$header	= null;
$header[] = __('Action', true);
$header[] = __('Date', true);
$header[] = __('Description', true);
$header[] = __('Weekly capacity', true);
$header[] = __('Fortnightly capacity', true);
$header[] = __('Reserved capacity', true);
$header[] = __('Occupied', true);

$head = $this->MyHtml->tag('thead', $this->MyHtml->tableHeaders($header));

$body = array();
foreach ($data as $record) {
	$td = null;
	$actions = null;
	$actions[] = $this->MyHtml->image(
		'view.png',
		array(
			'class' => 'open_modal',
			'title' => __('View', true) . ' ' . date('d-m-Y', strtotime($record['Charter']['date'])),
			'url' => array(
				'controller' 	=> 'charters',
				'action' 		=> 'view',
				$record['Charter']['id']
			),
		)
	);
	$actions[] = $this->MyHtml->image(
		'edit.png',
		array(
			'class' => 'open_modal',
			'title' => __('Edit', true) . ' ' . date('d-m-Y', strtotime($record['Charter']['date'])),
			'url' => array(
				'controller' 	=> 'charters',
				'action' 		=> 'add',
				$record['Charter']['id']
			),
		)
	);

	$td[] = $this->MyHtml->tag('td', $actions);
	$td[] = $this->MyHtml->tag('td', date('d-m-Y', strtotime($record['Charter']['date'])) . ' ' . $record['Charter']['time']);
	$td[] = $this->MyHtml->tag('td', $record['Charter']['description']);
	$td[] = $this->MyHtml->tag('td', $record['Charter']['weekly']);
	$td[] = $this->MyHtml->tag('td', $record['Charter']['fortnightly']);
	$td[] = $this->MyHtml->tag('td', $record['Charter']['reserved']);
	$td[] = $this->MyHtml->tag('td', sizeof($record['Passenger']));

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
