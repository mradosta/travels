<?php
//http://nik.chankov.net/2008/03/01/filtering-component-for-your-tables/
/**

 * Filter component
 * Benefits:
 * 1. Keep the filter criteria in the Session
 * 2. Give ability to customize the search wrapper of the field types

 **

 * @author  Nik Chankov

 * @website http://nik.chankov.net

 * @version 1.0.0

 *

 */


class FilterComponent extends Object {


/**
 * A reference to the instantiating controller object
 */
	var $controller;


/**
 * Fields which will replace the regular syntax in where i.e. field = 'value'
 */
    var $fieldFormatting    = array(
		//'string'	=> array('key' => '%s.%s LIKE', 'value' => '%s%%'),
		'string'	=> array('key' => '%s.%s LIKE', 'value' => '%s'),
		'date'		=> array('key' => '%s.%s')
	);


/**
 * Initializes FilterComponent for use in the controller
 *
 * @param object $controller A reference to the instantiating controller object
 * @return void
 * @access public
 */
    function initialize(&$controller, $settings = array()) {
		$this->controller = $controller;
	}
	
	
/**
 * Function which prepares controller->data array to fit into Cakephp Conditions;
 *
 * @param boolean $return If true, conditions array will be return, false will set conditions to controllers paginate.
 * @access public
 */
	function process($return = false) {
	
		$this->controller->data = $this->__getFilter();
		
        $conditions = array();
        if (isset($this->controller->data)) {

			foreach ($this->controller->data as $model => $value) {

                if (isset($this->controller->{$model})) {
				
                    $columns = $this->controller->{$model}->getColumnTypes();

                    foreach ($value as $field => $v) {
					
                        if ($v != '') {
							if (isset($this->fieldFormatting[$columns[$field]])) {
								$key = sprintf($this->fieldFormatting[$columns[$field]]['key'], $model, $field);
								$value = sprintf($this->fieldFormatting[$columns[$field]]['value'], $v);
                            } else {
                                $key = $model . '.' . $field;
								$value = $v;
                            }
							$conditions[$key] = $value;
                        }
                    }

                    if (count($value) == 0){
                        unset($this->controller->data[$model]);
                    }
                }
            }
        }
		
		if ($return) {
			return $conditions;
		} else {
			$this->controller->paginate['conditions'] = $conditions;
		}
    }


/**
 * Take care of storing the filter data and loading after this from the Session.
 *
 * @return array. Array with conditions
 * @access private
 */
    private function __getFilter() {

        if (isset($this->controller->data)) {
		
			if (isset($this->controller->data['Filter']['action']) && $this->controller->data['Filter']['action'] == 'clear') {
				$this->controller->Session->delete($this->controller->name . '.' . $this->controller->params['action']);
				return array();
			}
			
            foreach ($this->controller->data as $model => $fields) {
                foreach ($fields as $key => $field) {
                    if ($field == '') {
                        unset($this->controller->data[$model][$key]);
                    }
                }
            }
            $this->controller->Session->write($this->controller->name . '.' . $this->controller->params['action'], $this->controller->data);
			return $this->controller->data;

        } else {
			if ($this->controller->Session->check($this->controller->name . '.' . $this->controller->params['action'])) {
				return $this->controller->Session->read($this->controller->name . '.' . $this->controller->params['action']);
			} else {
				return array();
			}
		}
    }

}