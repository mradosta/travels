<?php
class Event extends AppModel {

	
	var $belongsTo = array(
		'CreatorUser' 	=> array(
			'className'		=> 'User',
			'foreignKey'    => 'creator_user_id'
		),
		'TargetUser' 	=> array(
			'className'		=> 'User',
			'foreignKey'    => 'target_user_id'
		),
		'ResolutorUser' => array(
			'className'		=> 'User',
			'foreignKey'    => 'resolutor_user_id'
		)
	);


	function getDates($date, $subject_id = null) {

		$conditions['Event.type'] = 'date';
		$conditions['Event.date'] = $date;

		if ($subject_id) {
			$conditions['Event.subject_id'] = $subject_id;
		} //else {
//			if (User::get('/User/environment') == 'colaborative') {
//				$conditions['TargetUser.office_id'] = User::get('/User/office_id');
//			} else {
//				$conditions['Event.target_user_id'] = User::get('/User/id');
//			}



		//}
		$matter = User::get('/Matter/.');

		if (User::get('/User/environment') == 'Colaborative') {
			
			if (!empty($matter['id'])) {
				$conditions['Event.matter_id'] = $matter['id'];
			}
			
			$conditions['Event.visibility'] = 'public';
			$conditions['Event.type'] = 'date';
			$conditions['Event.date'] = $date;
			$conditions['TargetUser.office_id']	= User::get('/Office/id');
		
			$publics = $this->find('all',
				array(
					'contain'		=> array('TargetUser'),
					'conditions'	=> $conditions
				)
			);

			$conditions['Event.visibility'] = 'private';
			unset($conditions['TargetUser.office_id']);
			$conditions['Event.target_user_id'] = User::get('/User/id');

			$privates = $this->find('all',
				array(
					'conditions'	=> $conditions
				)
			);
		} else {

			$conditions['Event.visibility'] = 'public';
			$conditions['Event.type'] = 'date';
			$conditions['Event.date'] =  $date;
			$conditions['TargetUser.office_id']	= User::get('/Office/id');

			$conditions['OR'] = array(
				'Event.matter_id' 		=> 0,
				'Event.target_user_id' 	=> User::get('/User/id'),
			);

			if (!empty($matter['id'])) {
				$conditions['Event.matter_id'] = $matter['id'];
			}
			
			
			$publics = $this->find('all',
				array(
					'contain'		=> array('TargetUser'),
					'conditions'	=> $conditions
				)
			);
			

			$conditions['Event.visibility'] = 'private';
			unset($conditions['TargetUser.office_id']);
			$conditions['Event.target_user_id'] = User::get('/User/id');
			
			$privates = $this->find('all',
				array(
					//'contain'		=> array('Subject'),
					'conditions'	=> $conditions
				)
			);
		}
		
		return array_merge($privates, $publics);
	}


/**
 * Validation method
 */	
	function messageIsEmpty($values) {
		if ($this->data['Event']['type'] == 'pospone') {
			return true;
		} else {
			if (empty($values['notes'])) {
				return false;
			} else {
				return true;
			}
		}
	}



	function getAlerts($date) {

		$privates = $this->find('all',
			array(
				'contain'		=> array('CreatorUser'),
				'conditions'	=> array(
					'Event.target_user_id' 	=> User::get('/User/id'),
					'Event.visibility'		=> 'private',
					'Event.type' 			=> 'alert',
					'Event.date' 			=> $date,
					'Event.state'			=> 'pending'
				)
			)
		);
		$publics = $this->find('all',
			array(
				'contain'		=> array('CreatorUser', 'TargetUser'),
				'conditions'	=> array(
					'Event.visibility'		=> 'public',
					'Event.type' 			=> 'alert',
					'Event.date' 			=> $date,
					'Event.state'			=> 'pending',
					'TargetUser.office_id'	=> User::get('/Office/id')
				)
			)
		);

		return array_merge($privates, $publics);

	}


	protected function _initialitation() {

		$this->validate = array(
			'date' => array(
				'date' => array(
					'rule' => array('date'),
					'message' => __('Enter a valid date', true),
					'last' => true,

				),
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __('Enter date', true),
					'last' => true, 
				)
			),
			'notes' => array(
				'notempty' => array(
					'rule' => array('messageIsEmpty'),
					'message' => __("Message can't be empty", true),
					'required' => false,
					'last' => true,
				)
			)
		);

    }



}