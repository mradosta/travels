<?php

	$out =  null;

	/** Actions */
	$links =  null;
	$links[] = $this->MyHtml->link(
		__('Delete', true),
		array(
			'controller' 	=> 'charters',
			'action' 		=> 'delete',
			$data['Charter']['id']
		),
		array(
			'title' => __('Delete charter', true),
			'class' => 'cancel'
		),
		__('Are you sure to delete the Charter?', true)
	);

	$out[] = $this->element('actions',
		array('links' => $links)
	);




	$fields[__('Destination', true)] = $data['Destination']['name'];
	
	$fields[__('Date', true)] = $data['Charter']['formated_date'] . ' ' . $data['Charter']['time'];

	$fields[__('Description', true)] = $data['Charter']['description'];
	
	$fields[__('Weekly capacity', true)] = $data['Charter']['weekly'];

	$fields[__('Fortnightly capacity', true)] = $data['Charter']['fortnightly'];

	$fields[__('Reserved capacity', true)] = $data['Charter']['reserved'];

	$fields[__('Total capacity', true)] = $total_capacity;

	$fields[__('Occupied', true)] = $percent . '% (' . $occupied . ')';

	echo $this->element('view', array('data' => $fields));

	$out[] = $this->MyHtml->tag('div',
		$this->MyHtml->tag('div', '&nbsp;', array('class' => 'aircraft-background', 'style' => 'width:' . $percent . '%;'))
		. $this->MyHtml->tag(
			'div',
			$this->MyHtml->image('aircraft.png'), array('class' => 'aircraft-img')
		),
		array('class' => 'aircraft')
	);

	
	// Normalize data array as a cakephp findall array
	$dataForElement = array();
	foreach ($data['Passenger'] as $passenger) {
		$passenger['Charter'] = $data['Charter'];
		$passenger['accompanying'] = 1;
		$dataForElement[] = array(
			'Passenger' => $passenger
		);
	}
	$out[] = $this->MyHtml->tag('div',
		$this->element('list_passengers', array('data' => $dataForElement)),
		array('id' => 'list_passengers')
	);
	
	




	echo $this->MyHtml->tag('div', $out);