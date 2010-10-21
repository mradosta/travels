<?php


class AppController extends Controller {


	var $paginate = array('limit' => 15);
	var $helpers = array('MyHtml', 'MyPaginator', 'MyForm', 'MyText', 'Session');
	var $components = array('Session', 'Email');


	function delete($id) {

		if ($this->{$this->modelClass}->delete($id)) {
 			$this->Session->setFlash(__('Record deleted', true), 'flash_success');
		} else {
			$this->Session->setFlash(__('Record was not deleted', true), 'flash_error');
		}

		$this->redirect(array('action' => 'index'));
	}


/**
 * Called before the controller action.
 *
 *	1) Load config xml if not loaded.
 *
 */
	function beforeFilter() {


		/** Check for a logged in user */
		if ($this->Session->check('User')) {

			/** Saves current logged in user */
			$user = $this->Session->read('User');
			App::import('Model', 'User');
			User::store($user);

		} else if (
			!in_array(
				$this->params['action'],
				array('login', 'logout')))
		{
			$this->redirect(
				array(
					'controller' 	=> 'users',
					'action' 		=> 'login'
				)
			);
		}

	}




}