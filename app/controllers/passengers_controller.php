<?php
class PassengersController extends AppController {

	function add($id = null) {

        $this->set(
			'charters',
			$this->Passenger->Charter->find(
				'list',
				array(
					'fields' => array('Charter.id', 'Charter.formated_date', 'Charter.description')
				)
			)
		);

		$this->set(
			'types',
			array(
				'weekly' 		=> __('weekly', true),
				'fortnightly' 	=> __('fortnightly', true),
				'reserved' 		=> __('reserved', true)
			)
		);

		$this->set(
			'meal_packages',
			array(
				'full board' 	=> __('full board', true),
				'half board' 	=> __('half board', true),
				'breakfast' 	=> __('breakfast', true)
			)
		);

		$this->set(
			'base',
			array(
				'single' 		=> __('single', true),
				'double' 		=> __('double', true),
				'family plan' 	=> __('family plan', true)
			)
		);

		$this->set('states',
			array(
				'authorized' => __('authorized', true),
				'unauthorized' => __('unauthorized', true)
			)
		);

		$this->set('ejecutives',
			$this->Passenger->Ejecutive->find('list', array('fields' => array('Ejecutive.id', 'Ejecutive.full_name')))
		);

		if (!empty($this->data)) {

			$r = $this->checkAvailability($this->data);
			if ($r === true) {

				$this->set('charter_data', $this->data['Passenger']);
				$this->render('add_passengers');
			} else {
				$this->Session->setFlash($r, 'flash_error');
			}

		} else {
			
			if (!empty($id)) {
				$this->data = $this->Passenger->read(null, $id);
				$this->set('id', $id);
				$this->render('admin_edit_passengers');
			} 
		}

	}

	function __sendEmail($group_id, $toAgency = false) {

		$passengers = $this->Passenger->findAllByGroup($group_id);
		$this->set('passengers', $passengers);
		if ($toAgency) {
			
			$this->Email->to = $passengers[0]['User']['email'];
			$this->Email->subject = __('Passenger', true) . ' ' . $passengers[0]['Passenger']['state'];
			$this->Email->template = 'email/state';

		} else {
			$this->Email->to = EMAIL;
			$this->Email->subject = __('Passenger added', true);
			$this->Email->template = 'email/added';
		}

		$result = $this->Email->send();
	}


	function checkAvailability($data) {
		
		if (!empty($data['Passenger']['amount'])) {

			$charterId = $data['Passenger']['charter_id'];
			$charterType = $data['Passenger']['type'];
			$amount = $data['Passenger']['amount'];

			// Check charter departure date
			$this->Passenger->Charter->recursive = -1;
			$charter = $this->Passenger->Charter->findById($charterId);
			if (date('Y-m-d') > $charter['Charter']['date']) {
				return __('Passengers could not be saved, because the selected charter has gone.', true);
			}


			if ($this->Passenger->checkAvailability($charterId, $charterType, $amount)) {
				return true;
			} else {
				return __('Passengers could not be saved, because no availables charters.', true);
			}
		} else {
			return __('Passengers could not be saved, because you must enter the number of passengers.', true);
		}
	}

	function admin_update_state($group_id, $controller = 'passengers', $view_id = null) {
		if(User::get('/User/type') != 'admin') {
			$this->Session->setFlash(
				__(
					"Your user don't have access.",
					true
				),
				'flash_error'
			);
			$this->redirect(array('admin' => false, 'controller' => 'users', 'action' => 'login'));
		}

		$passengers = $this->Passenger->findAllByGroup($group_id);
		$state = (($passengers[0]['Passenger']['state'] == 'pending') ? 'authorized' : 'unauthorized');
		$state = ((!empty($this->params['named']['pending'])) ? 'pending' : $state);
		foreach ($passengers as $passenger) {
			$passengerSave = array(
				'Passenger' => array(
					'state'	=> $state,
					'id'	=> $passenger['Passenger']['id']
				)
			);
			$save = $this->Passenger->save($passengerSave);
		}
		$this->__sendEmail($passengers[0]['Passenger']['group'], true);

		if ($controller == 'passengers') {
			if (!empty($view_id)) {
				$redirect = array('controller' => 'passengers', 'action' => 'view', $view_id);
			} else {
	
				$redirect = array('controller' => 'passengers', 'action' => 'index');
			}
		} else {
			$redirect = array('controller' => $controller, 'action' => 'view', $view_id);
		}

		$this->redirect($redirect);
	}

	private function __edit($group_id = null) {
		$this->set('states', array('authorized' => 'authorized', 'unauthorized' => 'unauthorized'));
		if (!empty($this->data)) {
			$saved = $this->Passenger->saveAll($this->data['Passenger'], array('validate' => 'first'));
			if ($saved) {

				$this->Session->setFlash(
					__('The passengers has been saved.', true), 'flash_success'
				);
				$this->redirect(array('action' => 'index'));

			} else {
				$this->Session->setFlash(
					__(
						'The passengers could not be saved. Please, check errors and try again.',
						true
					),
					'flash_error'
				);
			}
			
		} else {
			$this->set('data', $this->Passenger->findAllByGroup($group_id));
			$this->render('__edit');
		}
	}

	function edit($group_id = null) {
		$this->__edit($group_id);
	}
	function admin_edit($group_id = null) {
		$this->__edit($group_id);
	}

	function add_passengers() {

		if (!empty($this->data)) {

			$uuid = uniqid();
			foreach ($this->data['Passenger'] as $k => $v) {
				$this->data['Passenger'][$k]['group'] = $uuid;
			}

			if ($this->Passenger->saveAll($this->data['Passenger'], array('validate' => 'only'))) {

				foreach ($this->data['Passenger'] as $passenger) {

					$this->Passenger->create();
					$this->Passenger->save($passenger);
				}
				$this->__sendEmail($uuid);
				$this->Session->setFlash(
					__('The passengers has been saved.', true), 'flash_success'
				);
				$this->redirect(array('action' => 'index'));

			} else {
				$this->Session->setFlash(
					__(
						'The passengers could not be saved. Please, check errors and try again.',
						true
					),
					'flash_error'
				);
				$this->set('charter_data', unserialize($this->data['Extra']['charter_data']));
			}
		} else {

			$this->set('charter_data',
				array(
					'charterId' 	=> $this->params['named']['charter_id'],
					'charterType' 	=> $this->params['named']['charter_type'],
					'mealPackages' 	=> $this->params['named']['meal_packages'],
					'base' 			=> $this->params['named']['base'],
					'amount' 		=> $this->params['named']['amount']
				)
			);
		}
	}


	function admin_index($id = null) {

		$users = $this->Passenger->User->find('list',
			array(
				'fields' 		=> array('User.id', 'User.full_name'),
				'conditions' 	=> array('User.type !=' => 'admin')
			)
		);
		$this->set('users', $users);

		$charters = $this->Passenger->Charter->find('list',
			array('fields' => array('Charter.id', 'Charter.date', 'Charter.description'))
		);
		$this->set('charters', $charters);

		$this->set('id', $id);

		$this->__index($id, true);
	}

	function index() {
		$this->__index(User::get('/User/id'));
	}

	private function __index($userId = null, $admin = false) {

		if (!empty($userId)) {
			$this->paginate['conditions']['Passenger.user_id'] = $userId;
		}
		$this->paginate['order'] = array('Charter.date' => 'ASC', 'Passenger.id' => 'ASC');
		$this->paginate['group'] = array('Passenger.group');
		$this->paginate['contain'] = array(
			'Charter'	=> array('fields' =>
				array(
					'Charter.date',
					'Charter.description'
				)
			),
			'User' 		=> array('fields' => array('User.username', 'User.full_name'))
		);
		$this->paginate['fields'] = array(
			'count(1) as accompanying',
			'SUM(Passenger.infoa) as infoas',
			'Passenger.id',
			'Passenger.first_name',
			'Passenger.last_name',
			'Passenger.full_name',
			'Passenger.type',
			'Passenger.state',
			'Passenger.group'
		);
		$this->set('data', $this->paginate());

		if (!empty($userId) && !$admin) {
			$this->render('index');
		} else {
			$this->render('admin_index');
		}
	}

	function admin_view($id) {
		$this->__view($id);
	}
	function view($id) {
		$this->__view($id);
	}
	private function __view($id) {

		$this->set('data', $this->Passenger->find('all',
			array(
				'contain'		=> array('Charter.Destination'),
				'order'			=> array('Passenger.id' => 'ASC'),
				'conditions' 	=> array('Passenger.group' => $id)
			)
		));
		$this->render('__view');
	}


	function admin_delete($group_id) {
		$passengers = $this->Passenger->findAllByGroup($group_id);
		$prefix = false;
		if ($passengers[0]['Passenger']['state'] == 'authorized') {

			$this->Session->setFlash(
				__(
					"Passengers can't be deleted because it's already authorized.",
					true
				),
				'flash_error'
			);

			if (User::get('/User/type') == 'admin') {
				$prefix = true;
			}

			$this->redirect(array('admin' => $prefix, 'controller' => 'passengers', 'action' => 'index'));
		} else {

			foreach ($passengers as $passenger) {
				$this->Passenger->delete($passenger['Passenger']['id']);
			}
			
			$this->Session->setFlash(
				__('Passengers deleted', true),
				'flash_success'
			);
			
			$this->redirect(
				array('admin' => $prefix, 'controller' => 'passengers', 'action' => 'index')
			);
		}
	}

}