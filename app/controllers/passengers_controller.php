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
			
			if ($this->checkAvailability($this->data)) {
				
				$this->redirect(
					array(
						'admin'			=> false,
						'action'		=> 'add_passengers',
						'charter_id'	=> $this->data['Passenger']['charter_id'],
						'charter_type'	=> $this->data['Passenger']['type'],
						'amount'		=> $this->data['Passenger']['amount']
					)
				);
			} else {
				$this->Session->setFlash(
					__(
						'The passenger could not be saved, because no availables charters.',
						true
					),
					'flash_error'
				);
			}

		} else {
			
			if (!empty($id)) {
				$this->data = $this->Passenger->read(null, $id);
				$this->set('id', $id);
				$this->render('admin_edit_passengers');
			} 
		}

	}

	function checkAvailability($data) {
		
		if (!empty($data['Passenger']['amount'])) {

			$charterId = $data['Passenger']['charter_id'];
			$charterType = $data['Passenger']['type'];
			$amount = $data['Passenger']['amount'];

			if ($this->Passenger->checkAvailability($charterId, $charterType, $amount)) {
				return true;
			} else {
				return false;
			}
		} else {

			return false;
			
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

		if ($controller == 'passengers') {
			$redirect = array('controller' => 'passengers', 'action' => 'index');
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

			if ($this->Passenger->saveAll($this->data['Passenger'], array('validate' => 'first'))) {

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


	function admin_index() {
		$this->__index();
	}

	function index() {
		$this->__index(User::get('/User/id'));
	}

	private function __index($userId = null) {
		if (!empty($userId)) {
			$this->paginate['conditions']['Passenger.user_id'] = $userId;
		}
		$this->set('data', $this->paginate());
		if (!empty($userId)) {
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
