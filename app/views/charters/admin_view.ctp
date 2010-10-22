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


	$xx[__('Reserved capacity', true)] = $data['Charter']['reserved'];
	$xx[__('Reserved capacity', true)] = $data['Charter']['reserved'];
	$xx[__('Reserved capacity', true)] = $data['Charter']['reserved'];
	$xx[__('Reserved capacity', true)] = $data['Charter']['reserved'];
	$xx[__('Reserved capacity', true)] = $data['Charter']['reserved'];



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

	
	// Normalize data array as a cakephp findall array
	$dataForElement = array();
	foreach ($data['Passenger'] as $passenger) {
		$passenger['Charter'] = $data['Charter'];
		$dataForElement[] = array(
			'Passenger' => $passenger
		);
	}
	$out[] = $this->MyHtml->tag('div',
		$this->element('list_passengers', array('data' => $dataForElement)),
		array('id' => 'list_passengers')
	);
	
	




	echo $this->MyHtml->tag('div', $out);