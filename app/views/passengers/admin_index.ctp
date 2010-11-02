<?php

// Normalize data array as a cakephp findall array
$dataForElement = array();
foreach ($data as $k => $record) {

	if (empty($record['Charter']['formated_date'])) {
		$record['Charter']['formated_date'] = date('d/m/Y', strtotime($record['Charter']['date']));
	}
	$record['Passenger']['accompanying'] = $record[0]['accompanying'];
	$dataForElement[$k]['Passenger'] = $record['Passenger'];
	$dataForElement[$k]['Passenger']['Charter'] = $record['Charter'];
	$dataForElement[$k]['Passenger']['User'] = $record['User'];
	$dataForElement[$k]['Passenger']['infoas'] = $record['0']['infoas'];
}

echo $this->MyHtml->tag('div', $this->element('list_passengers', array('data' => $dataForElement, 'paginate' => true)), array('class' => 'list_passengers'));
