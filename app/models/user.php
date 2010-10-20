<?php

class User extends AppModel {

	function  afterSave($created) {

		if ($created) {

            $password = md5($this->data['User']['password']);
			$this->saveField('password', $password);
		}
        return parent::afterSave($created);

	}

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
 * Generates random 10x10 codes matrix used for users login validations.
 *
 * @return array. 10x10 matrix array. Letters on columns, number on rows.
 */
    function generate_codes() {

            $return = array();

            for ($i = 0; $i < 10; $i++) {
				for ($j = 'A'; $j <= 'J'; $j++) {
						$return[$i][$j] = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
				}
            }

            return $return;
    }


/**
* Generates random coordinates.
*
* @return array. i pos and j pos.
*/
    function get_random_coordinates() {

            $letters = array();
            for ($j = 'A'; $j <= 'J'; $j++) {
				$letters[] = $j;
            }

            $randI = rand(0, 9);
            $randJ = rand(0, 9);

            return array('i' => $randI, 'j' => $letters[$randJ]);
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
					'conatin'		=> array('Office'),
					'conditions'	=> array(
						'User.email'	=> 'mradosta@pragmatia.com'
						//'User.email'	=> $data['User']['email'],
						//'User.password'	=> md5($data['User']['password']),
						//'User.state'	=> 'active'
					),
				)
            );
			//$user['User']['environment'] = getConf('/App/environment');
			//$user['User']['show_closed_matters'] = getConf('/App/show_closed_matters');
			return $user;

            if (!empty($user)) {
				if (empty($user['User']['last_login'])) {
						return $user;
				} else {
					$coordinates = unserialize($data['User']['coordinates']);
					$codes = unserialize($user['User']['codes']);
					if ($codes[$coordinates['i']][$coordinates['j']] == $data['User']['code']) {
						return $user;
					} else {
						return false;
					}
				}
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



/**
 * Return user's ids of the same office of the currently logged in user.
 *
 * @return array. cakephp find all array.
 */
    function getUsersOfMyOffice() {

		return $this->find('all',
			array(
				'recursive'		=> -1,
				'conditions'	=> array(
					'User.office_id'	=> User::get('/User/office_id')
				)
			)
		);

    }



/**
 * Return user's ids of the currently logged in user.
 *
 * @return array. List of ids.
 */
    function getUsersByOffice() {

		return Set::extract('/User/id',
			$this->find('all',
				array(
					'recursive'		=> -1,
					'fields'		=> array('User.id'),
					'conditions'	=> array(
						'User.office_id'	=> 1
					)
				)
			)
		);
    }




/*
 * log user's actions
 */
	function log($params = null) {
		$Log = ClassRegistry::Init('Log');
		$userData = User::get('/.');

		$data['Log'] = array(
			'office_id'		=> $userData['Office']['id'],
			'office_name'	=> $userData['Office']['name'],
			'date'			=> date('Y-m-d H:s'),
			'user_id'		=> $userData['User']['id'],
			'username'		=> $userData['User']['full_name']
		);

		if (!empty($userData['User']['sustitutor_id'])) {
			$User = ClassRegistry::Init('User');
			$replacer = $User->findById($userData['User']['sustitutor_id']);
			$data['Log']['replacer_user_id'] = $replacer['User']['id'];
			$data['Log']['replacer_user_name'] = $replacer['User']['full_name'];
		}

		if (is_array($params)) {
			foreach ($params as $param => $value) {
				$data['Log'][$param] = $value;
			}
		}

		return $Log->save($data);
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