<?php 

	/** Title */
	$this->set('title_for_layout',  __('Users', true));


	/** Actions */
	$links =  null;
	$links[] = $this->MyHtml->link(
		__('Add', true),
		array(
			'controller' 	=> 'users', 
			'action' 		=> 'add'
		), 
		array(
			'class' => 'open_modal',
			'title' => __('Add new user', true)
		)
	);

	$out[] = $this->element('actions', array('links' => $links));


	
	/** The grid */
	/** TODO: do translations */
	$header = null;
	$header[] = __('User Name', true);
	$header[] = __('Full Name', true);
	$header[] = __('Type', true);
	

	$body = null;
	foreach ($data as $record) {

		$viewLink = $this->MyHtml->link(
			'##LABEL##',
			array(
				'controller' 	=> 'users',
				'action' 		=> 'add',
				$record['User']['id']
			),
			array(
				'class' => 'open_modal',
				'title' => __('View', true) . ' ##LABEL##'
			)
		);

		$body[] = array(
			str_replace('##LABEL##', $record['User']['username'], $viewLink),
			str_replace('##LABEL##', $record['User']['full_name'], $viewLink),
			$record['User']['type'],
			
		);
	}

	$out[] = $this->MyHtml->tag('div',
		$this->MyHtml->table($body, $header),
		array('id' => 'grid')
	);


	/** Pagination counter and navigation */
	$out[] = $this->MyPaginator->getNavigator();

	echo $this->MyHtml->tag('div', $out);