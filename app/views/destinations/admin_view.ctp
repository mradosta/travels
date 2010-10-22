<?php

	$out =  null;

	/** Actions */
	$links =  null;
	$links[] = $this->MyHtml->link(
		__('Delete', true),
		array(
			'controller' 	=> 'destinations',
			'action' 		=> 'delete',
			$data['Destination']['id']
		),
		array(
			'title' => __('Delete destination', true),
			'class' => 'cancel'
		),
		__('Are your sure to delete the Destination?', true)
	);

	$out[] = $this->element('actions',
		array('links' => $links)
	);


	$fields[__('Destination', true)] = $data['Destination']['name'];

	echo $this->element('view', array('data' => $fields));

	echo $this->MyHtml->tag('div', $out, array('class' => ''));