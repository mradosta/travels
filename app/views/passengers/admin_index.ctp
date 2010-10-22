<?php

$out[] = $this->MyHtml->tag('h2',
	__('Passengers', true),
	array('id' => 'tasks_title', 'class' => 'passengers')
);

//$out[] = $this->MyForm->input(
//	'user_id',
//	array(
//		'label' 	=> __('Charter', true),
//		'type' 		=> 'select',
//		'options' 	=> $charters,
//		'default' 	=> $id,
//		'empty' 	=> true,
//		'div' => array('class' => 'filter')
//	)
//);
//$out[] = $this->MyForm->input(
//	'user_id',
//	array(
//		'label' 	=> __('Agency', true),
//		'type' 		=> 'select',
//		'options' 	=> $users,
//		'default' 	=> $id,
//		'empty' 	=> true,
//		'div' => array('class' => 'filter')
//	)
//);

/** Filters */
$out[] = $this->element('filters', array('fields' => array('user_id', 'charter_id')));



/** The grid */
$header	= null;
$header[] = __('Actions', true);
$header[] = __('Charter', true);
$header[] = __('First Name', true);
$header[] = __('Last Name', true);
$header[] = __('Type', true);
$header[] = __('State', true);
$header[] = __('Agency', true);


$head = $this->MyHtml->tag('thead', $this->MyHtml->tableHeaders($header));

$body = array();
foreach ($data as $record) {
	$td = null;
	$actions = null;
	$actions[] = $this->MyHtml->image(
		'view.png',
		array(
			'class' => 'open_modal',
			'title' => __('View', true) . ' ' . $record['Passenger']['first_name'] . ' ' . $record['Passenger']['last_name'],
			'url' => array(
				'controller' 	=> 'passengers',
				'action' 		=> 'view',
				$record['Passenger']['id']
			),
		)
	);
	$actions[] = $this->MyHtml->image(
		'edit.png',
		array(
			'class' => 'open_modal',
			'title' => __('Edit', true) . ' ' . $record['Passenger']['first_name'] . ' ' . $record['Passenger']['last_name'],
			'url' => array(
				'controller' 	=> 'passengers',
				'action' 		=> 'edit',
				$record['Passenger']['id']
			),
		)
	);

	$invertCurrentState = (($record['Passenger']['state'] == 'authorized') ? 'unauthorized' : 'authorized');
	$state = $this->MyHtml->link(
		$this->MyHtml->image($invertCurrentState . '.png'),
		array(
			'controller'	=> 'passengers',
			'action'		=> 'update_state',
			$invertCurrentState,
			$record['Passenger']['id']
		),
		array('escape' => false, 'title' => __('Change state', true))
	);

	$td[] = $this->MyHtml->tag('td', $actions);
	$td[] = $this->MyHtml->tag('td', $record['Charter']['description'] . ' ' . date('d-m-Y', strtotime($record['Charter']['date'])));
	$td[] = $this->MyHtml->tag('td', $record['Passenger']['first_name']);
	$td[] = $this->MyHtml->tag('td', $record['Passenger']['last_name']);
	$td[] = $this->MyHtml->tag('td', __($record['Passenger']['type'], true));
	$td[] = $this->MyHtml->tag('td', $state);
	$td[] = $this->MyHtml->tag('td', $record['User']['full_name']);
	
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

$out[] = $this->MyPaginator->getNavigator(false);

echo implode("\n", $out);

?>
<script type="text/javascript">
	
	$(document).ready(function($) {
		$("#user_id").change(function() {
			location.href = '<?php echo Router::url(array('controller' => 'passengers')); ?>/index/' + $(this).val();
		});
	}); //$(document).ready
</script>



