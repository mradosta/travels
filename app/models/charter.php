<?php
class Charter extends AppModel {

	
	var $belongsTo = array('Destination');
	var $hasMany = array('Passenger');
	var $virtualFields = array(
		'formated_date' => 'DATE_FORMAT(date, "%d/%m/%Y")'
	);

	function afterFind($results) {

		foreach ($results as $key => $val) {
			
			if (!empty($val['Charter']['date'])) {
				$val['Charter']['date'] = date('d-m-Y', strtotime($val['Charter']['date']));
				$results[$key] = $val;
			}
			
		}
		return $results;
	}


	protected function _initialitation() {


		$this->validate = array(
			'date' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __("Date can't be empty", true),
					'last' => true, // Stop validation after this rule
				),
			),
			'time' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __("Time can't be empty", true),
					'last' => true, // Stop validation after this rule
				),
			),
			'description' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __("Description can't be empty", true),
					'last' => true, // Stop validation after this rule
				),
			),
			'weekly' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __("Weekly capacity can't be empty", true),
					'last' => true, // Stop validation after this rule
				),
			),
			'fortnightly' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __("Fortnightly capacity can't be empty", true),
					'last' => true, // Stop validation after this rule
				),
			),
			'reserved' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __("Reserved capacity can't be empty", true),
					'last' => true, // Stop validation after this rule
				),
			),

		);

    }


}