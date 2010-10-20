<?php
class DestinationsController extends AppController {


	function admin_add($id = null) {

        if (!empty($this->data)) {

			if ($this->Destination->save($this->data)) {
				return $this->Session->setFlash(__('The destination has been saved.', true), 'flash_success');
			} else {
				return $this->Session->setFlash(
					__(
						'The destination could not be saved. Please, check errors and try again.',
						true
					),
					'flash_error'
				);
			}
		} else {

			if (!empty($id)) {
				$this->data = $this->Destination->read(null, $id);
			}

		}
	}


	function admin_index() {
		$this->paginate['limit'] = 15;
		$this->set('data', $this->paginate());
	}


	function admin_view($id) {

		$this->set('data', $this->Destination->read(null, $id));
	}


	function admin_delete($id) {

		if ($this->Destination->delete($id)) {
			$this->Session->setFlash(
			__('Destination deleted', true),
			'flash_success');
		} else {
			$this->Session->setFlash(
			__('Destination was not deleted', true),
			'flash_error');
		}

		$this->redirect(array('action'=>'index'));
	}

}
