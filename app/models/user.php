<?php

class User extends AppModel {

	protected function _initialitation() {

		//$this->options = array(0 => __('No', true), 1 => __('Yes', true));


        $this->validate = array(
			'username' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __("Username can't be empty", true),
					'last' => true, // Stop validation after this rule
				),
			),
			'full_name' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __("Full name can't be empty", true),
					'last' => true, // Stop validation after this rule
				),
			),
			'email' => array(
				'notempty' => array(
					'rule' => array('email'),
					'message' => __("Email can't be empty", true),
					'last' => true, // Stop validation after this rule
				),
			),
			'password' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __("Password can't be empty", true),
					'last' => true, // Stop validation after this rule
				),
			),
			're-password' => array(
				'notempty' => array(
					'rule' => array('isSamePassword'),
					'message' => __('Passwords do not match', true),
					'last' => true, // Stop validation after this rule
				),
			),
			
	);

    }

    function isSamePassword($values) {
        if ($values['re-password'] == $this->data['User']['password']) {
            return true;
        } else {
            return false;
        }
    }

        




/**
* Callback.
*
*		1) When a new user is created, generates random codes.
*
* @return array. i pos and j pos.
*/
    function xbeforeSave($options = array()) {

            if (empty($this->id)) {
				$this->data[$this->name]['password'] = md5($this->data[$this->name]['password']);
				$this->data[$this->name]['codes'] = serialize($this->generate_codes());
				$this->data[$this->name]['last_login'] = null;
            }

            return parent::beforeSave($options);
    }


/**
* Validates a users.
*
* @return mixed. User data array on a succesfull login, false in other case.
*/
    function validate($data) {

			/** TODO: Remove hardcoded */
            $user = $this->find('first',
				array(
					'conditions'	=> array(
						//'User.email'	=> 'mradosta@pragmatia.com'
						'User.username'	=> $data['User']['username'],
						'User.password'	=> md5($data['User']['password']),
						//'User.state'	=> 'active'
					),
				)
            );
			//$user['User']['environment'] = getConf('/App/environment');
			//$user['User']['show_closed_matters'] = getConf('/App/show_closed_matters');
			//return $user;

            if (!empty($user)) {
				return $user;
            } else {
				return false;
            }

    }


/**
* Update last login field.
*
* @return mixed. Last login date on success, false in other case.
*/
    function updateLastLogin($id) {

		$date = date('Y-m-d h:i:s');
		if ($this->save(
			array('User' => array(
				'id'			=> $id,
				'last_login'	=> $date
				)
			),
			array('validate' => false)
		)) {
			return $date;
		} else {
			return $date;
		}
    }


/**
* Update password field.
* @param int $id The User Id
* @param string $password The new password to store, not hashed
* @return mixed. Hashed password on success, false in other case.
*/
    function updatePassword($id, $password) {
            $hashedPassword = md5($password);
            if ($this->save(array('User' => array(
				'id' 		=> $id,
				'password' 	=> $hashedPassword)), array('validate' => false))) {
				return $hashedPassword;
            } else {
				return false;
            }
    }


/**************************************************************************************************
*
http://www.pseudocoder.com/archives/2008/10/06/accessing-user-sessions-from-models-or-anywhere-in-cakephp-revealed/
*
**************************************************************************************************/
    function &getInstance($user=null) {
		static $instance = array();

		if ($user) {
			$instance[0] =& $user;
		}

		if (!$instance) {
			trigger_error(__('User not set.', true), E_USER_WARNING);
			return false;
		}

		return $instance[0];
    }


    function store($user) {
		if (empty($user)) {
				return false;
		}

		User::getInstance($user);
    }


    function get($path = '/User/.') {

		$_user = User::getInstance();
/*
		//$path = str_replace('.', '/', $path);
		if (strpos($path, 'User') !== 0) {
				$path = sprintf('User/%s', $path);
		}

		if (strpos($path, '/') !== 0) {
				$path = sprintf('/%s', $path);
		}
*/
		$value = Set::extract($path, $_user);

		if (!$value) {
				return false;
		}

		return $value[0];
    }

}