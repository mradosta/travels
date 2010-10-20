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
			'title' => __('Delete passenger', true)
		),
		__('Are your sure to delete the Passenger?', true)
	);

	$out[] = $this->element('actions',
		array('links' => $links)
	);

	
	$charter[] = $this->MyHtml->tag('dt',
		__('Charter', true)
	);
	$charter[] = $this->MyHtml->tag('dd',
		$data['Charter']['description'] . ' ' . date('d-m-Y', strtotime($data['Charter']['date']))
	);


	$charter[] = $this->MyHtml->tag('dt',
		__('Type', true)
	);
	$charter[] = $this->MyHtml->tag('dd',
		$data['Passenger']['type']
	);



	$charter[] = $this->MyHtml->tag('dt',
		__('First Name', true)
	);

	$charter[] = $this->MyHtml->tag('dd',
		$data['Passenger']['first_name']
	);


	$charter[] = $this->MyHtml->tag('dt',
		__('First Last Name', true)
	);

	$charter[] = $this->MyHtml->tag('dd',
		$data['Passenger']['first_last_name']
	);


	$charter[] = $this->MyHtml->tag('dt',
		__('Birthday', true)
	);

	$charter[] = $this->MyHtml->tag('dd',
		$data['Passenger']['birthday']
	);


	$charter[] = $this->MyHtml->tag('dt',
		__('DNI', true)
	);

	$charter[] = $this->MyHtml->tag('dd',
		$data['Passenger']['dni']
	);



	$charter[] = $this->MyHtml->tag('dt',
		__('Email', true)
	);

	$charter[] = $this->MyHtml->tag('dd',
		$this->MyHtml->tag('span', $data['Passenger']['email'], array('class' => 'data'))
	);


	$charter[] = $this->MyHtml->tag('dt',
		__('Phone', true)
	);

	$charter[] = $this->MyHtml->tag('dd',
		$data['Passenger']['phone']
	);


	$charter[] = $this->MyHtml->tag('dt',
		__('State', true)
	);

	$charter[] = $this->MyHtml->tag('dd',
		$data['Passenger']['state']
	);

	$charterData = $this->MyHtml->tag('dl', $charter);
	$out[] = $this->MyHtml->tag('div', $charterData, array('class' => 'view'));

	echo $this->MyHtml->tag('div', $out);