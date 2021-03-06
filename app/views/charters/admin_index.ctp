<?php

$out[] = $this->MyHtml->tag('h2',
	__('Charters', true),
	array('id' => 'tasks_title', 'class' => 'charters')
);


/** Filters */
$out[] = $this->element('filters', array('fields' => array('destination_id')));


$links = null;
$links[] = $this->MyHtml->link(
	__('Add Charter', true),
	array(
		'controller'	=> 'charters',
		'action'		=> 'add',
	),
	array('class' => 'cancel', 'title' => __('Add charter', true))
);
$out[] = $this->MyHtml->tag('div', $this->element('actions', array('links' => $links)), array('class' => 'clear-both'));

/** The grid */
$header	= null;
$header[] = __('Action', true);
$header[] = __('Fecha', true);
$header[] = __('Hora', true);
$header[] = __('Destination', true);
$header[] = __('Compañia Aerea', true);
$header[] = __('Numero de Vuelo', true);
$header[] = __('Ruta', true);
$header[] = __('Weekly capacity', true);
$header[] = __('Fortnightly capacity', true);
$header[] = __('Reserved', true);
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
	$td[] = $this->MyHtml->tag('td', $record['Charter']['formated_date']);
	$td[] = $this->MyHtml->tag('td', $record['Charter']['time']);
	$td[] = $this->MyHtml->tag('td', $record['Destination']['name']);
	$td[] = $this->MyHtml->tag('td', $record['Charter']['airline']);
	$td[] = $this->MyHtml->tag('td', $record['Charter']['flight_number']);
	$td[] = $this->MyHtml->tag('td', $record['Charter']['route']);
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
