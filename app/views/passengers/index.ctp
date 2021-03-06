<?php

$out[] = $this->MyHtml->tag('h2',
	__('Passengers', true),
	array('id' => 'tasks_title')
);

$links = null;
$links[] = $this->MyHtml->link(
	__('Add Passenger', true),
	array(
		'controller'	=> 'passengers',
		'action'		=> 'add',
	),
	array('class' => 'cancel', 'title' => __('Add passenger', true))
);


echo $this->element('actions', array('links' => $links));


/** The grid */
$header	= null;
$header[] = __('Actions', true);
$header[] = __('Charter Description', true);
$header[] = __('First Name', true);
$header[] = __('Last Name', true);
$header[] = __('Reserva', true);
$header[] = __('State', true);
$header[] = __('Accompanying', true);
$header[] = __('Infoas', true);


$head = $this->MyHtml->tag('thead', $this->MyHtml->tableHeaders($header));

$body = array();
foreach ($data as $record) {
	$td = null;
	$actions = null;
	$actions[] = $this->MyHtml->image(
		'view.png',
		array(
			'class' => 'open_modal',
			'title' => __('View', true) . ' ' . $record['Passenger']['first_name'] . ' ' . $record['Passenger']['last_name'],
			'url' => array(
				'controller' 	=> 'passengers',
				'action' 		=> 'view',
				$record['Passenger']['group']
			),
		)
	);
	$actions[] = $this->MyHtml->image(
		'edit.png',
		array(
			'class' => 'open_modal',
			'title' => __('Edit', true) . ' ' . $record['Passenger']['first_name'] . ' ' . $record['Passenger']['last_name'],
			'url' => array(
				'controller' 	=> 'passengers',
				'action' 		=> 'edit',
				$record['Passenger']['group']
			),
		)
	);
	
	$td[] = $this->MyHtml->tag('td', $actions);
	$td[] = $this->MyHtml->tag('td', $record['Charter']['description'] . ' ' . date('d-m-Y', strtotime($record['Charter']['date'])));
	$td[] = $this->MyHtml->tag('td', $record['Passenger']['first_name']);
	$td[] = $this->MyHtml->tag('td', $record['Passenger']['last_name']);
	$td[] = $this->MyHtml->tag('td', $record['Passenger']['formated_created']);
	$td[] = $this->MyHtml->tag('td', $this->MyHtml->image($record['Passenger']['state'] . '.png', array('title' => __($record['Passenger']['state'], true))));
	$td[] = $this->MyHtml->tag('td', $record['0']['accompanying'] - 1 - $record['0']['infoas']);
	$td[] = $this->MyHtml->tag('td', $record['0']['infoas']);
	
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
