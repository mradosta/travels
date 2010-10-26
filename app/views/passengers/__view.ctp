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

	$fields[__('Charter', true)] = $data['Charter']['Destination']['name'] . ' ' . $data['Charter']['formated_date'];

	$fields[__('Type', true)] = __($data['Passenger']['type'], true);

	$fields[__('First Name', true)] = $data['Passenger']['first_name'];

	$fields[__('Last Name', true)] = $data['Passenger']['last_name'];

	$fields[__('Birthday', true)] = $data['Passenger']['formated_birthday'];

	$fields[__('DNI', true)] = $data['Passenger']['dni'];

	$fields[__('Email', true)] = $data['Passenger']['email'];

	$fields[__('Phone', true)] = $data['Passenger']['phone'];

	$fields[__('State', true)] = __($data['Passenger']['state'], true);


	$passenger = $this->element('view', array('data' => $fields));
	
	$out[] = $this->MyHtml->tag('div', $passenger, array('class' => ''));

	echo $this->MyHtml->tag('div', $out);