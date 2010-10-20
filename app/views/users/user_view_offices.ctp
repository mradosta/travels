<?php

	
	$body = null;

	foreach ($data as $d) {
		$btn = $this->MyHtml->link(
			'View',
			array(
				'controller' 	=> 'offices',
				'action' 		=> 'view_report',
				1
			),
			array(
				'title' => __('View', true)
			)
		);
		$body[] = array(
			$this->MyHtml->tag('span', __($d['label'], true)),
			$this->MyHtml->tag('span', $btn, array('class' => 'view_button'))
		);

	}

	echo $this->MyHtml->tag('div', $this->MyHtml->table($body));
