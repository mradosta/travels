<?php

	$out =  null;

	/** Actions */
	$links =  null;
	$links[] = $this->MyHtml->link(
		__('Delete', true),
		array(
			'controller' 	=> 'ocupations',
			'action' 		=> 'delete',
			$data['Ocupation']['id']
		),
		array(
			'title' => __('Delete ocupation', true),
			'class' => 'cancel'
		),
		__('Are your sure to delete the Ocupation?', true)
	);

	$out[] = $this->element('actions',
		array('links' => $links)
	);

	
	$charter[] = $this->MyHtml->tag('div',
		$this->MyHtml->tag('span', __('Weekly', true), array('class' => 'label'))
	);
	$charter[] = $this->MyHtml->tag('div',
		$this->MyHtml->tag('span', $data['Ocupation']['weekly'], array('class' => 'data'))
	);


	$charter[] = $this->MyHtml->tag('div',
		$this->MyHtml->tag('span', __('Fortnightly', true), array('class' => 'label'))
	);

	$charter[] = $this->MyHtml->tag('div',
		$this->MyHtml->tag('span', $data['Ocupation']['fortnightly'], array('class' => 'data'))
	);


	$out[] = $this->MyHtml->tag('div', $charter);

	echo $this->MyHtml->tag('div', $out);