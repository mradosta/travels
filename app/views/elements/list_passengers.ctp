<?php
	$out[] = $this->MyHtml->tag('h2',
		__('Passengers', true),
		array('id' => 'tasks_title', 'class' => 'passengers')
	);

	/** Filters */
	if (!empty($paginate)) {
		$out[] = $this->element('filters', array('fields' => array('user_id', 'charter_id')));
	}
	/** The grid */
	$header	= null;
	$header[] = __('Actions', true);
	$header[] = __('Charter', true);
	$header[] = __('First Name', true);
	$header[] = __('Last Name', true);
	$header[] = __('Type', true);
	$header[] = __('Reserva', true);
	$header[] = __('Accompanying', true);
	$header[] = __('Infoas', true);
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
				'title' => __('View', true) . ' ' . $record['Passenger']['full_name'],
				'url' => array(
					'controller' 	=> 'passengers',
					'action' 		=> 'view',
					$record['Passenger']['group']
				),
			)
		);
		$actions[] = $this->MyHtml->image(
			'edit.png',
			array(
				'class' => 'open_modal',
				'title' => __('Edit', true) . ' ' . $record['Passenger']['full_name'],
				'url' => array(
					'controller' 	=> 'passengers',
					'action' 		=> 'edit',
					$record['Passenger']['group']
				),
			)
		);

		$currentState = (($record['Passenger']['state'] == 'authorized') ? 'authorized' : 'unauthorized');
		$currentState = (($record['Passenger']['state'] == 'pending') ? 'pending' : $currentState);
		$state = $this->MyHtml->image($currentState . '.png', array('title' => __($record['Passenger']['state'], true)));

		
		$td[] = $this->MyHtml->tag('td', $actions);
		$td[] = $this->MyHtml->tag('td', $record['Passenger']['Charter']['description'] . ' ' . $record['Passenger']['Charter']['formated_date']);
		$td[] = $this->MyHtml->tag('td', $record['Passenger']['first_name']);
		$td[] = $this->MyHtml->tag('td', $record['Passenger']['last_name']);
		$td[] = $this->MyHtml->tag('td', $record['Passenger']['formated_created']);
		$td[] = $this->MyHtml->tag('td', $state);
		$td[] = $this->MyHtml->tag('td', $record['Passenger']['accompanying'] - 1 - $record['Passenger']['infoas']);
		$td[] = $this->MyHtml->tag('td', $record['Passenger']['infoas']);
		$td[] = $this->MyHtml->tag('td', $record['Passenger']['User']['full_name']);
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
	if (!empty($paginate)) {
		$out[] = $this->MyPaginator->getNavigator(false);
	}
	echo implode("\n", $out);
?>

<script type="text/javascript">

  $(document).ready(function($) {

    $(".change_state").click(function() {
		
		$("#list_passengers").load($(this).attr('href'));
    });

  }); //$(document).ready

</script>
