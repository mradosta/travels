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
		$this->paginate['order'] = array('Charter.date' => 'asc');
		$this->paginate['limit'] = 15;
		$this->set('data', $this->paginate());
	}

	
	function admin_view($id) {
		$charter = $this->Charter->find(
			'first',
			array(
				'conditions' => array(
					'Charter.id'	=> $id
				),
			)
		);
		$this->set(
			'data',
			$charter
		);
		$total = $charter['Charter']['weekly'] + $charter['Charter']['fortnightly'] + $charter['Charter']['reserved'];
		$occupied = sizeof($charter['Passenger']);
		$percent = ($occupied * 100) / $total;
		$this->set('percent', ceil($percent));
		$this->set('occupied', $occupied);
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
