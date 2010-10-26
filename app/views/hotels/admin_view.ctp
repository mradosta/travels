<?php

	$out =  null;

	/** Actions */
	$links =  null;
	$links[] = $this->MyHtml->link(
		__('Delete', true),
		array(
			'controller' 	=> 'hotels',
			'action' 		=> 'delete',
			$data['Hotel']['id']
		),
		array(
			'title' => __('Delete hotel', true),
			'class' => 'cancel'
		),
		__('Are you sure to delete the hotel?', true)
	);

	$out[] = $this->element('actions',
		array('links' => $links)
	);




	$fields[__('Destination', true)] = $data['Destination']['name'];
	
	$fields[__('Name', true)] = $data['Hotel']['name'];

	echo $this->element('view', array('data' => $fields));

	echo $this->MyHtml->tag('div', $out, array('class' => ''));