<?php
class UsersController extends AppController {

	var $paginate = array('order' => array('User.username'));

    /**
     *  The AuthComponent provides the needed functionality
     *  for login, so you can leave this function blank.
     */
    function login() {
		//Security::setHash('md5');
    }


    function logout() {
        $this->redirect($this->Auth->logout());
    }


/**
 * return json array of available replacement for a user id.
 *
 * @return void.
 */
	function user_get_replacers($id) {

		$user = $this->User->Replacement->find('all',
			array(
				'recursive'		=> -1,
				'conditions'	=> array('Replacement.user_id' => $id)
			)
		);

		$this->set('data', json_encode($user));
		$this->render('/elements/only_text', 'ajax');
	}

	function user_replace() {

		$user = User::get();

		if (!empty($this->data)) {

			if (empty($this->data['User']['replacer_user_id'])) {
				$this->data['User']['replacer_user_id'] = array();
			}

			if (empty($this->data['User']['user_id'])) {
				$this->data['User']['user_id'] = $user['id'];
			}

			$replacements = $this->User->Replacement->find('all',
				array(
					'conditions' => array('Replacement.user_id' => $this->data['User']['user_id'])
				)
			);
			$replacementIds = Set::extract('/Replacement/replacer_user_id', $replacements);
			$activeReplacementIds = Set::extract('/Replacement[state=active]/replacer_user_id', $replacements);


			foreach ($this->data['User']['replacer_user_id'] as $replacerUserId) {

				// When supervisor and not already set, must add new ones
				if ($user['supervisor'] && !in_array($replacerUserId, $replacementIds)) {

					$save = null;
					$save = array('Replacement' =>
						array(
							'user_id'			=> $this->data['User']['user_id'],
							'replacer_user_id'	=> $replacerUserId,
							'state'				=> 'active'
						)
					);
					$this->User->Replacement->create();
					$this->User->Replacement->save($save);

				// When normal user and not already set, must activate
				} elseif (!$user['supervisor']
					&& !in_array($replacerUserId, $activeReplacementIds)
					&& in_array($replacerUserId, $replacementIds))
				{

					$conditions = null;
					$conditions = array(
						'user_id'			=> $this->data['User']['user_id'],
						'replacer_user_id'	=> $replacerUserId
					);
					$this->User->Replacement->updateAll(
						array('state' 	=> "'active'"),
						$conditions
					);
				}
			} //foreach


			$toDelete = null;
			//If supervisor, delete everything
			if ($user['supervisor']) {
				$toDelete = array_diff(
					$replacementIds, $this->data['User']['replacer_user_id']
				);
				foreach ($toDelete as $replacerUserIdToDelete) {
					$this->User->Replacement->deleteAll(
						array(
							'Replacement.user_id' 			=> $this->data['User']['user_id'],
							'Replacement.replacer_user_id' 	=> $replacerUserIdToDelete
						)
					);
				}
			//If normal user, inactivate
			} else {
				$toUpdate = array_diff(
					$activeReplacementIds, $this->data['User']['replacer_user_id']
				);
				foreach ($toUpdate as $replacerUserIdToUpdate) {
					$this->User->Replacement->updateAll(
						array('state' 	=> "'inactive'"),
						array(
							'user_id'						=> $this->data['User']['user_id'],
								'Replacement.replacer_user_id' => $replacerUserIdToUpdate
						)
					);
				}
			}

			return $this->setSuccessAjaxFlash(
				__('The user sustitutor assignment has been saved.', true)
			);

		} else {

			// HARD CODE TO TEST
			//$user['sustitution'] = false;


			if ($user['supervisor']) {

				$me[$user['id']] = __('Me', true) . ' - ' . $user['fullname'];

				foreach ($this->User->getUsersOfMyOffice() as $availableUser) {
					if ($availableUser['User']['id'] != $user['id']) {
						$users[$availableUser['User']['id']] = $availableUser['User']['fullname'];
					}
				}
				$this->set('allUsers', $me + $users);
				$this->set('usersOfMyOfficeButMe', $users);

				$this->render('user_replace_supervisor');
			} else {
				$this->set('allowedUsersOfMyOffice',
					$this->User->Replacement->find('all',
						array(
							'contain'		=> array('Replacer'),
							'conditions'	=> array('Replacement.user_id' => $user['id'])
						)
					)
				);
			}

			//$this->set('user', $user);
			/*
			if ($user['sustitution']) {
				$user['sustitution'] = false;
				$this->autoRender = false;
			} else {
				$user['sustitution'] = true;
				//$this->set('user', $this->User->findById($user['id']));
				//$this->render('replace');
			}
			*/
		}

	}


/**
 * Updates user's session setting the setted sustitution mode.
 *
 * @return void.
 */
	function change_sustitution_mode() {

		if (!empty($this->params['named']['mode']) && $this->params['named']['mode'] == 'off') {

			$user = $this->User->find('first',
				array(
					'contain'		=> array('Office'),
					'conditions'	=> array(
						'User.id'	=> User::get('/User/sustitutor_id')
					)
				)
			);
			$user['User']['environment'] = User::get('/User/environment');
			$this->Session->write('User', $user);
			$this->Session->setFlash(__('Sustitution mode off', true), true);

			//Log action
			$params = array(
				'action' => __('Sustitution mode OFF', true),
				'detail' => User::get('/User/full_name') .
					' ' . __('is again', true) . ' ' . $user['User']['full_name']
			);
			User::log($params);

			$this->redirect(
				array(
					'user'			=> true,
					'controller'	=> 'events',
					'action'		=> 'index',
				)
			);
		} else {

			if (empty($this->data)) {

				$data = $this->User->Replacement->find('all',
					array(
						'contain'		=> array('User'),
						'conditions'	=> array(
							'Replacement.replacer_user_id'	=> User::get('/User/id'),
							'Replacement.state'				=> 'active'
						)
					)
				);
				$this->set('users', Set::combine($data, '{n}.User.id', '{n}.User.full_name'));

			} else {

				$user = $this->User->find('first',
					array(
						'contain'		=> array('Office'),
						'conditions'	=> array(
							'User.id'	=> $this->data['User']['user_id']
						)
					)
				);
				$user['User']['sustitutor_id'] = User::get('/User/id');
				$user['User']['environment'] = User::get('/User/environment');
				
				$this->Session->write('User', $user);


				//Log action
				$params = array(
					'action' => __('Sustitution mode ON', true),
					'detail' => User::get('/User/fullname') .
						' ' . __('is now', true) . ' ' . $user['User']['full_name']
				);
				User::log($params);

				$this->Session->setFlash(__('Sustitution mode off', true), true);
				$this->redirect(
					array(
						'user'			=> true,
						'controller'	=> 'events',
						'action'		=> 'index',
					)
				);
			}
		} // else of mode
	}



/**
	TODO: Validar longitud del password y caracteres obligatorios.
*/
	function change_password() {

		if (!empty($this->data)) {
			
			$user = $this->Session->read('User');
			$current_password = $this->data['User']['current'];
			$new_password = $this->data['User']['new_password'];
			$retype_password = $this->data['User']['retype_password'];
			
			if (md5($current_password) == $user['User']['password']) {
				if(empty($new_password) || empty($retype_password)) {
					return $this->setErrorAjaxFlash(
						__('Please type new password.', true),
						array('fields' => array(
							'UserNewPassword' 		=> __('Enter new password', true),
							'UserRetypePassword' 	=> __('Retype new password', true)
							)
						)
					);
				} else {
					if ($new_password == $retype_password) {
						$updatedPassword =  $this->User->updatePassword(
							$user['User']['id'],
							$new_password
						);
						$user['User']['password'] = $updatedPassword;
						if ($user['User']['password'] == $updatedPassword) {
							$this->Session->write('User', $user);
							return $this->Session->setFlash(
								__('The password was changed succesfully.', true),
								'flash_success'
							);
						} else {
							return $this->Session->setFlash(
								__('There was a problem updating the password.', true),
								'flash_error'
							);
						}
					} else {
						return $this->Session->setFlash(
							__('The new password and the retype fields must be equals.', true),
							'flash_error'
						);
					}
				}
			} else {
				return $this->Session->setFlash(
					__('Current password error.', true),
					'flash_error'
				);
			}
		}
	}


/**
 * Shows the user's code table.
 *
 * @return void.
 */
	function user_show_code_table($codes = null) {
		if (empty($codes)) {
			$user = User::get();
			$codes = $user['codes'];
		}

		$this->set('codes', unserialize($codes));
	}


/**
 * Prints the user's code table.
 *
 * @return void.
 */
	function user_print_codes() {
		$this->layout = 'print';
		$this->setAction('user_show_code_table');
		//$this->render('user_show_code_table');
	}


	/**
	 * TODO: Do comment
	 */
	function user_view_offices() {

		$user = User::get();
		$office = $this->User->Office->find('first',
			array(
				'recursive'		=> -1,
				'conditions'	=> array('Office.id' => $user['office_id']),
				'fields'		=> array('Office.type'),
			)
		);

		$reports = getConf(
			sprintf('/App/Offices/Office[type=%s]/Reports/Report/.', $office['Office']['type'])
		);
		$this->set('data', $reports);
	}



	function admin_index() {

		$this->set('data', $this->paginate());

	}





/**
 *******************************************************************************
 * CRUDs
 *******************************************************************************
 */


/**
 * Create
 */
	function admin_add($id = null) {
		$this->set('types', array('admin' => 'admin', 'agency' => 'agency'));
		if (!empty($this->data)) {
			
			$this->User->create();
			//$this->data['User']['password'] = md5($this->data['User']['password']);
			if ($this->User->save($this->data)) {
				return $this->Session->setFlash(__('The user has been saved.', true), 'flash_success');
			} else {
				return $this->Session->setFlash(
					__('The user could not be saved. Please, check errors and try again.', true),
					'flash_error'
				);
			}
		} else {
			if (!empty($id)) {
				$this->set('id', $id);
				//$this->set('attributes', getConf('/App/Cards/Card[name=users]/Attributes/Attribute/.'));
				$user = $this->User->read(null, $id);
				$this->data = $user;
			}
		}
	}


/**
 * Read
 */
	function admin_view($id) {
		$this->set('types', array('admin' => 'admin', 'agency' => 'agency'));
		$this->set('id', $id);
		//$this->set('attributes', getConf('/App/Cards/Card[name=users]/Attributes/Attribute/.'));
		$user = $this->User->read(null, $id);
		$this->data = $user;
		$this->render('admin_add');
		
	}


/**
 * Update
 */
	function admin_edit($id) {

		if (!empty($this->data)) {
			if ($this->User->save($this->data)) {
				return $this->setSuccessAjaxFlash(__('The user has been saved.', true));
			} else {
				return $this->setErrorAjaxFlash(
					__('The user could not be saved. Please, check errors and try again.', true)
				);
			}
		} else {
			$this->data = $this->User->read(null, $id);
			$this->get_lists();
			$this->render('admin_add');
		}

	}


/**
 * Delete
 */
	function admin_delete($id) {

		if ($this->User->delete($id)) {
			$this->Session->setFlash(
				__('User deleted', true),
				'flash_success'
			);
		} else {
			$this->Session->setFlash(
				__('User was not deleted', true),
				'flash_error'
			);
		}

		$this->redirect(array('action' => 'index'));
	}

	/*
	 * Block user
	 */
	function admin_block($state, $id) {
		
		$update = array(
			'User' => array(
				'id'				=> $id,
				'state'				=> $state,
			)
		);
		if ($this->User->save($update)) {
			$this->Session->setFlash(
				__('User' . ' ' .$state, true),
				'flash_success'
			);
		} else {
			$this->Session->setFlash(
				__('User was not' . ' ' . $state, true),
				'flash_error'
			);
		}

		$this->redirect(array('action' => 'index'));
	}

}
