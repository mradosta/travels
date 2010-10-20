<?php
/* Calendars Test cases generated on: 2010-05-20 06:05:27 : 1274346027*/
App::import('Controller', 'Calendars');

class TestCalendarsController extends CalendarsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class CalendarsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.calendar');

	function startTest() {
		$this->Calendars =& new TestCalendarsController();
		$this->Calendars->constructClasses();
	}

	function endTest() {
		unset($this->Calendars);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testView() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

	}

}
?>