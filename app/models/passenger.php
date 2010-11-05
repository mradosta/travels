<?php
class Passenger extends AppModel {

	
	var $belongsTo = array('Charter', 'User', 'Hotel', 'Ejecutive');
	var $virtualFields = array(
		'formated_birthday' => 'DATE_FORMAT(Passenger.birthday, "%d/%m/%Y")',
		'formated_created' => 'DATE_FORMAT(Passenger.created, "%d/%m/%Y")',
		'full_name' => 'CONCAT(Passenger.first_name, " ", Passenger.last_name)'
	);

	protected function _initialitation() {


		$this->validate = array(
			'first_name' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __("First Name can't be empty", true),
					'last' => true, // Stop validation after this rule
				),
			),
			'last_name' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __("Last Name can't be empty", true),
					'last' => true, // Stop validation after this rule
				),
			),
			'birthday' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __("Birthday can't be empty", true),
					'last' => true, // Stop validation after this rule
				),
			),
			'phone' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __("Telephone can't be empty", true),
					'last' => true, // Stop validation after this rule
				),
			),
			'dni' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __("Dni can't be empty", true),
					'last' => true, // Stop validation after this rule
				),
			)
		);

    }

	function checkAvailability($charterId, $charterType, $amount) {
		$passangers = $this->find(
			'count',
			array(
				'conditions' => array(
					'Passenger.charter_id'	=> $charterId,
					'Passenger.type'		=> $charterType,
				)
			)
		);

		$charter = $this->Charter->find(
			'first',
			array(
				'conditions' => array(
					'Charter.id'	=> $charterId,
				)
			)
		);

		if (($charter['Charter'][$charterType] - $passangers) < $amount) {
			return false;
		} else {
			return true;
		}

	}


}