<?php 
	
$out[] = $this->MyForm->create('Passenger');
//$content[] = $this->MyForm->input('group_id', array('type' => 'hidden',  'value' => $data[0]['Passenger']['group']));
for ($i = 0; $i < sizeof($data); $i++) {
	$content[] = $this->MyHtml->tag('h3', __('Passenger', true) . ' ' . ($i + 1));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.id', array('type' => 'hidden',  'value' => $data[$i]['Passenger']['id']));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.first_name', array('type' => 'text',  'value' => $data[$i]['Passenger']['first_name']));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.last_name', array('type' => 'text', 'value' => $data[$i]['Passenger']['last_name']));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.birthday', array('type' => 'text', 'class' => 'datepicker', 'value' => $data[$i]['Passenger']['birthday']));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.dni', array('type' => 'text', 'value' => $data[$i]['Passenger']['dni']));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.email', array('type' => 'text', 'value' => $data[$i]['Passenger']['email']));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.phone', array('type' => 'text', 'value' => $data[$i]['Passenger']['phone']));
	$passengers[] = $this->MyHtml->tag('div', $content, array('class' => 'passanger'));
	$content = null;
}

$out[] = $this->MyHtml->tag('div', $passengers, array('id' => 'container', 'class' => 'passenger_data'));

$out[] = $this->element('footer', array('controller' => 'passengers'));

$out[] = $this->MyForm->end();

echo implode("\n", $out);