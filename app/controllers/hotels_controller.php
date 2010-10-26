<?php
class HotelsController extends AppController {


	function get($detinationId) {
		$this->Hotel->recursive = -1;
		$data = $this->Hotel->findAllByDestinationId($detinationId);
		$this->set('data', json_encode($data));
		$this->render('../elements/only_text', 'ajax');
	}

	function admin_add($id = null) {

        $this->set(
			'destinations',
			$this->Hotel->Destination->find(
				'list',
				array(
					'recursive' => -1,
					'fields'	=> array('Destination.id', 'Destination.name')
				)
			)
		);

        if (!empty($this->data)) {

			if ($this->Hotel->save($this->data)) {
				return $this->Session->setFlash(__('The hotel has been saved.', true), 'flash_success');
			} else {
				return $this->Session->setFlash(
					__(
						'The hotel could not be saved. Please, check errors and try again.',
						true
					),
					'flash_error'
				);
			}
		} else {

			if (!empty($id)) {
				$this->data = $this->Hotel->read(null, $id);
			}

		}
	}


	function admin_index() {
		$this->paginate['limit'] = 15;
		$this->set('data', $this->paginate());
	}


	function admin_view($id) {

		$this->set('data', $this->Hotel->read(null, $id));
	}


	function admin_delete($id) {

		if ($this->Hotel->delete($id)) {
			$this->Session->setFlash(
			__('Hotel deleted', true),
			'flash_success');
		} else {
			$this->Session->setFlash(
			__('Hotel was not deleted', true),
			'flash_error');
		}

		$this->redirect(array('action'=>'index'));
	}

}