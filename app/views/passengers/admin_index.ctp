<?php

// Normalize data array as a cakephp findall array
$dataForElement = array();
foreach ($data as $k => $record) {
	$dataForElement[$k]['Passenger'] = $record['Passenger'];
	$dataForElement[$k]['Passenger']['Charter'] = $record['Charter'];
	$dataForElement[$k]['Passenger']['User'] = $record['User'];
}

echo $this->MyHtml->tag('div', $this->element('list_passengers', array('data' => $dataForElement, 'paginate' => true)), array('class' => 'list_passengers'));
