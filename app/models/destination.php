<?php
class Destination extends AppModel {

	protected function _initialitation() {


		$this->validate = array(
			'name' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __("Name can't be empty", true),
					'last' => true, // Stop validation after this rule
				),
			)
		);

    }


}