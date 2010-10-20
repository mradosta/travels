<?php 
	
$out[] = $this->MyForm->create('Passenger', array('class' => 'ajax_formx'));
$passangers = null;
//d($passengersIds);

$content[] = $this->MyForm->input('Extra.amount', array('type' => 'hidden', 'value' => $amount));

for ($i = 0; $i < $amount; $i++) {
	$content[] = $this->MyHtml->tag('h3', __('Passenger', true) . ' ' . ($i + 1));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.first_name', array('type' => 'text'));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.charter_id', array('type' => 'hidden', 'value' => $charterId));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.user_id', array('type' => 'hidden', 'value' => User::get('/User/id')));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.type', array('type' => 'hidden', 'value' => $charterType));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.first_last_name', array('type' => 'text'));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.birthday', array('type' => 'text', 'class' => 'datepicker'));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.dni', array('type' => 'text'));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.email', array('type' => 'text'));
	$content[] = $this->MyForm->input('Passenger.' . $i . '.phone', array('type' => 'text'));
	//$content[] = $this->MyForm->input('Passenger.' . $i . '.state', array('type' => 'radio', 'default' => 'unauthorized'));
	$passangers[] = $this->MyHtml->tag('div', $content, array('class' => 'passanger'));
	$content = null;
}

$out[] = $this->MyHtml->tag('div', $passangers, array('id' => 'container', 'class' => 'passenger_data'));

$out[] = $this->element('footer', array('controller' => 'passengers'));

$out[] = $this->MyForm->end();

echo implode("\n", $out);