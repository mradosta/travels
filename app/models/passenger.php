<?php
class Passenger extends AppModel {

	
	var $belongsTo = array('Charter', 'User');

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
			'email' => array(
				'notempty' => array(
					'rule' => array('email'),
					'message' => __("Invalid email", true),
					'last' => true, // Stop validation after this rule
				),
			),
			'dni' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __("Dni can't be empty", true),
					'last' => true, // Stop validation after this rule
				),
			),
			'amount' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __("Passengers can't be empty", true),
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