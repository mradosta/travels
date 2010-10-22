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
		__('Are your sure to delete the Charter?', true)
	);

	$out[] = $this->element('actions',
		array('links' => $links)
	);





	
	$charter[] = $this->MyHtml->tag('dt',
		__('Destination', true)
	);
	$charter[] = $this->MyHtml->tag('dd',
		$data['Destination']['name']
	);


	$charter[] = $this->MyHtml->tag('dt',
		__('Date', true)
	);

	$charter[] = $this->MyHtml->tag('dd',
		date('d-m-Y', strtotime($data['Charter']['date'])) . ' ' . $data['Charter']['time']
	);


	$charter[] = $this->MyHtml->tag('dt',
		__('Description', true)
	);

	$charter[] = $this->MyHtml->tag('dd',
		$data['Charter']['description']
	);

	$charter[] = $this->MyHtml->tag('dt',
		__('Weekly capacity', true)
	);

	$charter[] = $this->MyHtml->tag('dd',
		$data['Charter']['weekly']
	);


	$charter[] = $this->MyHtml->tag('dt',
		__('Fortnightly capacity', true)
	);

	$charter[] = $this->MyHtml->tag('dd',
		$data['Charter']['fortnightly']
	);


	$charter[] = $this->MyHtml->tag('dt',
		__('Reserved capacity', true)
	);

	$charter[] = $this->MyHtml->tag('dd',
		 $data['Charter']['reserved']
	);


	$charter[] = $this->MyHtml->tag('dt',
		__('Total capacity', true)
	);

	$charter[] = $this->MyHtml->tag('dd',
		 $total_capacity
	);


	$charter[] = $this->MyHtml->tag('dt',
		__('Occupied', true)
	);

	$charter[] = $this->MyHtml->tag('dd',
		 $percent . '% (' . $occupied . ')'
	);

	$out[] = $this->MyHtml->tag('div', $this->MyHtml->tag('dl', $charter), array('class' => 'view'));

	$out[] = $this->MyHtml->tag('div',
		$this->MyHtml->tag('div', '&nbsp;', array('class' => 'aircraft-background', 'style' => 'width:' . $percent . '%;'))
		. $this->MyHtml->tag(
			'div',
			$this->MyHtml->image('aircraft.png'), array('class' => 'aircraft-img')
		),
		array('class' => 'aircraft')
	);

	$header	= null;
	$header[] = __('Actions', true);
	$header[] = __('First Name', true);
	$header[] = __('Last Name', true);
	$header[] = __('Type', true);
	$header[] = __('State', true);
	$header[] = __('Agency', true);


	$head = $this->MyHtml->tag('thead', $this->MyHtml->tableHeaders($header));

	$body = array();
	foreach ($data['Passenger'] as $record) {
		$td = null;
		$actions = null;
		$actions[] = $this->MyHtml->image(
			'view.png',
			array(
				'class' => 'open_modal',
				'title' => __('View', true) . ' ' . $record['first_name'] . ' ' . $record['last_name'],
				'url' => array(
					'controller' 	=> 'passengers',
					'action' 		=> 'view',
					$record['id']
				),
			)
		);
		$actions[] = $this->MyHtml->image(
			'edit.png',
			array(
				'class' => 'open_modal',
				'title' => __('Edit', true) . ' ' . $record['first_name'] . ' ' . $record['last_name'],
				'url' => array(
					'controller' 	=> 'passengers',
					'action' 		=> 'edit',
					$record['id']
				),
			)
		);

		$invertCurrentState = (($record['state'] == 'authorized') ? 'unauthorized' : 'authorized');
		$state = $this->MyHtml->link(
			$invertCurrentState,
			array(
				'controller'	=> 'passengers',
				'action'		=> 'update_state',
				$invertCurrentState,
				$record['id'],
				'charters',
				$data['Charter']['id']
			)
		);

		$td[] = $this->MyHtml->tag('td', $actions);
		$td[] = $this->MyHtml->tag('td', $record['first_name']);
		$td[] = $this->MyHtml->tag('td', $record['last_name']);
		$td[] = $this->MyHtml->tag('td', $record['type']);
		$td[] = $this->MyHtml->tag('td', $state);
		$td[] = $this->MyHtml->tag('td', '');

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
	




	echo $this->MyHtml->tag('div', $out);