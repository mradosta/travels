<?php
class ChartersController extends AppController {


	function admin_add($id = null) {

        $this->set(
			'destinations',
			$this->Charter->Destination->find(
				'list',
				array(
					'recursive' => -1,
					'fields'	=> array('Destination.id', 'Destination.name')
				)
			)
		);
		if (!empty($this->data)) {

			if ($this->Charter->save($this->data)) {
				return $this->Session->setFlash(__('The charter has been saved.', true), 'flash_success');
			} else {
				return $this->Session->setFlash(
					__(
						'The charter could not be saved. Please, check errors and try again.',
						true
					),
					'flash_error'
				);
			}
		} else {
			
			if (!empty($id)) {
				$this->data = $this->Charter->read(null, $id);
			}

		}
	}


	function admin_index() {
		$this->set('destinations', $this->Charter->Destination->find('list'));
		$this->Filter->process();
		$this->paginate['order'] = array('Charter.date' => 'asc');
		$this->paginate['limit'] = 15;
		$this->set('data', $this->paginate());
	}

	
	function admin_view($id) {

		$this->Charter->contain(array('Passenger.User', 'Destination'));
		$data = $this->Charter->findById($id);
		
		$total = $data['Charter']['weekly'] + $data['Charter']['fortnightly'] + $data['Charter']['reserved'];
		$occupied = sizeof($data['Passenger']) + $data['Charter']['reserved'];
		$percent = ($occupied * 100) / $total;
		$this->set('percent', ceil($percent));
		$this->set('occupied', $occupied);
		$this->set('total_capacity', $total);


		$this->set('data', $data);
	}


	function admin_delete($id) {

		if ($this->Charter->delete($id)) {
			$this->Session->setFlash(
			__('Charter deleted', true),
			'flash_success');
		} else {
			$this->Session->setFlash(
			__('Charter was not deleted', true),
			'flash_error');
		}

		$this->redirect(array('action'=>'index'));
	}
 
}
