<?php
/* Calendar Test cases generated on: 2010-05-20 05:05:51 : 1274345991*/
App::import('Model', 'Calendar');

class CalendarTestCase extends CakeTestCase {
	var $fixtures = array('app.calendar');

	function startTest() {
		$this->Calendar =& ClassRegistry::init('Calendar');
	}

	function endTest() {
		unset($this->Calendar);
		ClassRegistry::flush();
	}

}
?>