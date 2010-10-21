<?php
class PassengersController extends AppController {

	function add($id = null) {

        $this->set(
			'charters',
			$this->Passenger->Charter->find(
				'list',
				array(
					'fields' => array('Charter.id', 'Charter.date', 'Charter.description')
				)
			)
		);

		$this->set(
			'types',
			array('weekly' => 'weekly', 'fortnightly' => 'fortnightly', 'reserved' => 'reserved')
		);

		$this->set('states', array('authorized' => 'authorized', 'unauthorized' => 'unauthorized'));

		if (!empty($this->data)) {

			$r = $this->checkAvailability($this->data);
			if ($r === true) {

				/*
				$this->redirect(
					array(
						'admin'			=> false,
						'action'		=> 'add_passengers',
						'charter_id'	=> $this->data['Passenger']['charter_id'],
						'charter_type'	=> $this->data['Passenger']['type'],
						'amount'		=> $this->data['Passenger']['amount']
					)
				);
				*/
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

	function __sendEmail($idPassenger, $toAgency = false) {

		$passenger = $this->Passenger->findById($idPassenger);
		$this->set('passenger', $passenger);
		if ($toAgency) {
			
			$this->Email->to = $passenger['User']['email'];
			$this->Email->subject = __('Passenger', true) . ' ' . $passenger['Passenger']['state'];
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

			// CHeck charter departure date
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

	function admin_update_state($state, $id, $controller = 'passengers', $view_id = null) {
		$passenger = array(
			'Passenger' => array(
				'state'	=> $state,
				'id'	=> $id
			)
		);
		$this->Passenger->save($passenger);
		$this->__sendEmail($this->Passenger->id, true);

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

	private function __edit($id) {
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
			$this->data = $this->Passenger->read(null, $id);
			$this->set('id', $id);
			$this->render('__edit');
		}
	}

	function edit($id) {
		$this->__edit($id);
	}
	function admin_edit($id) {
		$this->__edit($id);
	}

	function add_passengers() {

		if (!empty($this->data)) {
			
			if ($this->Passenger->saveAll($this->data['Passenger'], array('validate' => 'only'))) {

				foreach ($this->data['Passenger'] as $passenger) {

					$this->Passenger->create();
					if ($this->Passenger->save($passenger)) {
						$this->__sendEmail($this->Passenger->id);
					}
				}

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
				$this->set('charter_data',
					array(
						'amount' 		=> $this->data['Extra']['amount'],
						'type'			=> $this->data['Passenger'][0]['type'],
						'charter_id'	=> $this->data['Passenger'][0]['charter_id']
					)
				);
				$this->set('amount', $this->data['Extra']['amount']);
				$this->set('charterId', $this->data['Passenger'][0]['charter_id']);
				$this->set('charterType', $this->data['Passenger'][0]['type']);
			}
		} else {
			
			$this->set('charterId', $this->params['named']['charter_id']);
			$this->set('charterType', $this->params['named']['charter_type']);
			$this->set('amount', $this->params['named']['amount']);
		}
	}


	function admin_index($id = null) {
		$users = $this->Passenger->User->find('list',
			array('fields' => array('User.id', 'User.full_name'))
		);
		$this->set('users', $users);

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

		$this->set('data', $this->Passenger->read(null, $id));
		$this->render('__view');
	}


	function admin_delete($id) {

		if ($this->Passenger->delete($id)) {
			$this->Session->setFlash(
			__('Passenger deleted', true),
			'flash_success');
		} else {
			$this->Session->setFlash(
			__('Passenger was not deleted', true),
			'flash_error');
		}

		$this->redirect(array('action'=>'index'));
	}

}
