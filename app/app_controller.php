<?php


class AppController extends Controller {


	var $paginate = array('limit' => 15);
	var $helpers = array('MyHtml', 'MyPaginator', 'MyForm', 'MyText', 'Session');
	var $components = array('Cookie', 'Session');


	function delete($id) {

		if ($this->{$this->modelClass}->delete($id)) {
 			$this->Session->setFlash(__('Record deleted', true), 'flash_success');
		} else {
			$this->Session->setFlash(__('Record was not deleted', true), 'flash_error');
		}

		$this->redirect(array('action' => 'index'));
	}

	private function __setAjaxFlash($options) {

		$errors = array();
		if ($options['state'] == 'error') {
			if (empty($options['errors'])) {
				foreach ($this->{$this->modelClass}->validationErrors as $field => $message) {
					$errors[$this->{$this->modelClass}->name . Inflector::camelize($field)] = $message;
				}
			}
		}

		if (!empty($options['fields'])) {
			$errors = array_merge($errors, $options['fields']);
		}

		$_defaults = array(
			'title'		=> $options['state'],
			'fields'	=> $errors);
		$options = array_merge($_defaults, $options);


		$this->set('data', json_encode($options));
		$this->render('/elements/only_text', 'ajax');
	}


	function setSuccessAjaxFlash($message = '', $options = array()) {
		if (empty($message)) {
			$message = __('Success', true);
		}

		$_defaults = array(
			'state'		=> 'success',
			'message'	=> $message);
		$options = array_merge($_defaults, $options);
		$this->__setAjaxFlash($options);
	}


	function setErrorAjaxFlash($message = '', $options = array()) {
		if (empty($message)) {
			$message = __('Error', true);
		}

		$_defaults = array(
			'state'		=> 'error',
			'message'	=> $message);
		$options = array_merge($_defaults, $options);
		$this->__setAjaxFlash($options);
	}



/**
 * Called before the controller action.
 *
 *	1) Load config xml if not loaded.
 *
 */
	function beforeFilter() {


		/** Language settings */
		$language = $this->Cookie->read('language');
		if (empty($language)) {
			$language = 'eng';
			$this->Cookie->write('language', $language);
		}
		Configure::write('Config.language', $language);


		/** Sets the layout */
		if (!empty($this->params['admin'])) {
			$this->layout = 'admin';
		} elseif (!empty($this->params['mining'])) {
			$this->layout = 'admin';
		} elseif (!empty($this->params['user'])) {
			$this->layout = 'user';
		}


		/** Check for a logged in user */
		//$this->Session->delete('User');
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
					'admin'			=> false,
					'user'			=> false,
					'controller' 	=> 'users',
					'action' 		=> 'login'
				)
			);
		}


	}




}