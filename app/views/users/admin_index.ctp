<?php 

	/** Title */
	$out[] = $this->MyHtml->tag('h2',
		__('Users', true),
		array('id' => 'tasks_title')
	);

	/** Actions */
	$links = null;
$links[] = $this->MyHtml->link(
	__('Add User', true),
	array(
		'controller'	=> 'users',
		'action'		=> 'add',
	),
	array('class' => 'cancel', 'title' => __('Add user', true))
);
echo $this->element('actions', array('links' => $links));


	
	/** The grid */
	/** TODO: do translations */
	$header = null;
	$header[] = __('Actions', true);
	$header[] = __('User Name', true);
	$header[] = __('Full Name', true);
	$header[] = __('Type', true);
	

	$body = null;
	foreach ($data as $record) {

		$actions = $this->MyHtml->image(
			'view.png',
			array(
				'class' => 'open_modal',
				'title' => __('View', true) . ' ' . $record['User']['full_name'],
				'url' => array(
					'controller' 	=> 'users',
					'action' 		=> 'add',
					$record['User']['id']
				)
			)
		);


		$body[] = array(
			$actions,
			$record['User']['username'],
			$record['User']['full_name'],
			$record['User']['type'],
			
		);
	}

	$out[] = $this->MyHtml->tag('div',
		$this->MyHtml->table($body, $header),
		array('id' => 'grid')
	);


	/** Pagination counter and navigation */
	$out[] = $this->MyPaginator->getNavigator(false);

	echo $this->MyHtml->tag('div', $out);