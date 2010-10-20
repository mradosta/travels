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
			'title' => __('Delete destination', true)
		),
		__('Are your sure to delete the Destination?', true)
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

	$out[] = $this->MyHtml->tag('dl', $charter);

	echo $this->MyHtml->tag('div', $out, array('class' => 'view'));