<?php

	$out =  null;

	/** Actions */
	$links =  null;
	$links[] = $this->MyHtml->link(
		__('Delete', true),
		array(
			'controller' 	=> 'passengers',
			'action' 		=> 'delete',
			$data['Passenger']['id']
		),
		array(
			'title' => __('Delete passenger', true),
			'class' => 'cancel'
		),
		__('Are your sure to delete the Passenger?', true)
	);

	$out[] = $this->element('actions',
		array('links' => $links)
	);


	if (User::get('/User/type') == 'admin') {
		$links =  null;
		$invertCurrentState = (($data['Passenger']['state'] == 'authorized') ? 'unauthorize' : 'authorize');
		$links[] = $this->MyHtml->link(
			__($invertCurrentState, true),
			array(
				'controller'	=> 'passengers',
				'action'		=> 'update_state',
				$invertCurrentState,
				$data['Passenger']['id'],
				'passengers',
				$data['Passenger']['id']
			),
			array('class' => 'cancel')
		);

		$out[] = $this->element('actions',
			array('links' => $links)
		);
	}


	foreach ($data as $record) {

		$fields[__('Charter', true)] = $record['Charter']['Destination']['name'] . ' ' . date('d/m/Y', strtotime($record['Charter']['date']));

		$fields[__('Type', true)] = __($record['Passenger']['type'], true);

		$fields[__('First Name', true)] = $record['Passenger']['first_name'];

		$fields[__('Last Name', true)] = $record['Passenger']['last_name'];

		$fields[__('Birthday', true)] = $record['Passenger']['formated_birthday'];

		$fields[__('DNI', true)] = $record['Passenger']['dni'];

		$fields[__('Email', true)] = $record['Passenger']['email'];

		$fields[__('Phone', true)] = $record['Passenger']['phone'];

		$fields[__('State', true)] = __($record['Passenger']['state'], true);
	}

	$passenger = $this->element('view', array('data' => $fields));


	//$header[] = __('Accompanying', true);
	
	$out[] = $this->MyHtml->tag('div', $passenger, array('class' => ''));

	echo $this->MyHtml->tag('div', $out);