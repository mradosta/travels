<?php

$out[] = $this->MyHtml->tag('h2',
	__('Destinations', true),
	array('id' => 'tasks_title')
);

$links = null;
$links[] = $this->MyHtml->link(
	__('Add Destination', true),
	array(
		'controller'	=> 'destinations',
		'action'		=> 'add',
	),
	array('class' => 'cancel', 'title' => __('Add destination', true))
);
echo $this->element('actions', array('links' => $links));

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
			'title' => __('View', true) . ' ' . $record['Destination']['name'],
			'url' => array(
				'controller' 	=> 'destinations',
				'action' 		=> 'view',
				$record['Destination']['id']
			),
		)
	);
	$actions[] = $this->MyHtml->image(
		'edit.png',
		array(
			'class' => 'open_modal',
			'title' => __('Edit', true) . ' ' . $record['Destination']['name'],
			'url' => array(
				'controller' 	=> 'destinations',
				'action' 		=> 'add',
				$record['Destination']['id']
			),
		)
	);

	$td[] = $this->MyHtml->tag('td', $actions);
	$td[] = $this->MyHtml->tag('td', $record['Destination']['name']);
	
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
