<?php

	$out =  null;

	/** Actions */
	$links =  null;
	$links[] = $this->MyHtml->link(
		__('Delete', true),
		array(
			'admin'			=> true,
			'controller' 	=> 'passengers',
			'action' 		=> 'delete',
			$data[0]['Passenger']['group']
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
		$invertCurrentState = (($data[0]['Passenger']['state'] == 'authorized') ? 'unauthorize' : 'authorize');
		$links[] = $this->MyHtml->link(
			__($invertCurrentState, true),
			array(
				'controller'	=> 'passengers',
				'action'		=> 'update_state',
				$data[0]['Passenger']['group'],
				'passengers',
				$data[0]['Passenger']['group']
			),
			array('class' => 'cancel')
		);

		$out[] = $this->element('actions',
			array('links' => $links)
		);
	}


	foreach ($data as $key => $record) {
		if ($key == 0) {
			$fields[__('Charter', true)] = $record['Charter']['Destination']['name'] . ' ' . $record['Charter']['formated_date'];
			$fields[__('Type', true)] = __($record['Passenger']['type'], true);
			$fields[__('Hotel', true)] = __($record['Hotel']['name'], true);
			$fields[__('Meal packages', true)] = __($record['Passenger']['meal_packages'], true);
			$fields[__('Base', true)] = __($record['Passenger']['base'], true);
		}
		$fields[__('First Name', true)] = $record['Passenger']['first_name'];

		$fields[__('Last Name', true)] = $record['Passenger']['last_name'];

		$fields[__('Birthday', true)] = $record['Passenger']['formated_birthday'];

		$fields[__('DNI', true)] = $record['Passenger']['dni'];

		$fields[__('Email', true)] = $record['Passenger']['email'];

		$fields[__('Phone', true)] = $record['Passenger']['phone'];

		$fields[__('State', true)] = __($record['Passenger']['state'], true);

		$passenger = $this->element('view', array('data' => $fields));
		$out[] = $this->MyHtml->tag('div', $passenger, array('class' => ''));
		if ($key == 0 && !empty($data[1])) {
			$out[] = $this->MyHtml->tag('div', $this->MyHtml->tag('div', $this->MyHtml->tag('h4', __('Accompanying', true)), array('class' => 'view')), array('class' => ''));
		}
		$fields = null;
	}

	


	//$header[] = __('Accompanying', true);
	
	

	echo $this->MyHtml->tag('div', $out);