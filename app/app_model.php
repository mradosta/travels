<?php

class AppModel extends Model {


	var $actsAs = array('containable');


	/** Overwrite constructor to do "mostly" translations */
    public function __construct($id = false, $table = null, $ds = null) {

        parent::__construct($id, $table, $ds);

        $this->_initialitation();
    }


	/** To be [optionally] implemented on child classes */
    protected function _initialitation() {
    }


/**
 * Returns true if model is before matter, false in other cases.
 */
	function amIBeforeMatter() {
		if (empty($this->pathToNode)) {
			return true;
		} elseif ($this->name == 'Matter') {
			return false;
		} elseif (strpos($this->pathToNode, 'Matter') === false) {
			return true;
		} else {
			return false;
		}
	}


/**
 * Returns Model's parent name. False if no parent
 */
	function getParentName() {
		if (!empty($this->pathToNode)) {
			return array_pop(explode('|', $this->pathToNode));
		} else {
			return false;
		}
	}


	function getRalation() {
		$parentName = $this->getParentName();
		if ($parentName) {
			if (in_array($this->name, array_keys($this->{$parentName}->hasOne))) {
				return '1:1';
			} else {
				return '1:N';
			}
		} else {
			return false;
		}
	}


/**
 * Returns current model's contain ready syntax array
 * From current Model:
 *			- recursivily descendant hasOne models
 *			- first level descendant hasMany models
 *			- all models in current path with also all recusively hasOne associated models
 */
	function getActiveBranchContain() {

		$ascendantContain = $descendantContain = array();

		$descendantContain = array_merge(
			array_keys($this->hasMany), $this->getHasOneContain($this)
		);

		if (!empty($this->pathToNode)) {

			$pathModelName = array_pop(explode('|', $this->pathToNode));
			$ascendantContain[] = implode('.', array_reverse(explode('|', $this->pathToNode)));
			foreach ($this->getHasOneContain($this->{$pathModelName}) as $hasOneContain) {

				if (in_array($this->name, explode('.', $hasOneContain))) {
					continue;
				}
				$ascendantContain[] = $pathModelName . '.' . $hasOneContain;
			}
		}
		return array_merge($ascendantContain, $descendantContain);
	}


/**
 * Recursively gets all descendant hasOne relations from a given model object.
 *
 */
	function getHasOneContain($Model) {

		$contain = array();
		if (!empty($Model->hasOne)) {
			foreach ($Model->hasOne as $hasOneModel => $v) {
				$r = $this->getHasOneContain($Model->{$hasOneModel});
				if (!empty($r)) {
					$contain[] = $hasOneModel . '.' . implode('.', $r);
				} else {
					$contain[] = $hasOneModel;
				}
			}
		}
		return $contain;
	}


/**
 * Collapses a multi-dimensional array into a single dimension, using a delimited array path for
 * each array element's key.
 *
 * @param array $data Array to flatten
 * @param string $separator String used to separate array key elements in a path, defaults to '.'
 * @return array
 * @access public
 * @static
 */
	function flatten($data, $separator = '.') {

		$result = array();
		$path = null;

		if (is_array($separator)) {
			extract($separator, EXTR_OVERWRITE);
		}

		if (!is_null($path)) {
			$path .= $separator;
		}

		foreach ($data as $key => $val) {
			if (is_array($val)) {

				if (isset($val[0])) { // 1:N relation
					foreach ($val[0] as $field => $foo) {

						// Standarize show
						if (preg_match('/[A-Za-z]__show/', $field)) {
							$field = 'show';
						}
						$result[$key . '.' . $field] = implode(
							',', Set::extract('/' . $key . '/' . $field, $data)
						);
					}
					break;
				} else {
					$result += (array)$this->flatten($val, array(
						'separator' => $separator,
						'path' 		=> $key
					));
				}
			} else {

				// Standarize show
				if (preg_match('/[A-Za-z]__show/', $key)) {
					$key = 'show';
				}
				$result[$path . $key] = $val;
			}
		}
		return $result;
	}


	function unidimensionalize($data) {

		if ($this->name == 'Subject') {
			return $data;
		}
		foreach ($data as $model => $fields) {

			foreach ($fields as $field => $value) {
				if (is_array($value) && !empty($value)) {
					$result += $this->unidimensionalize(array($field => $value));
				} else {
					$result[$model][$field] = $value;
				}
			}
		}

		return $result;
	}
	

	function getActiveBranchData($id, $options = array()) {

		$_defaults = array('flatten' => true);
		$options = array_merge($_defaults, $options);
		$contain = $this->getActiveBranchContain();
		$this->contain($contain);
		$data = $this->findById($id);
		
		if ($options['flatten'] === true) {
			if (!empty($data)) {
				return $this->flatten($data);
			} else {
				return array();
			}
		} elseif ($options['flatten'] == 'unidimentional') {
			return $this->unidimensionalize($data);	
		} else {
			return $data;
		}
	}

	function unflatten($data) {
		$result = null;
		foreach ($data as $field => $value) { 
			$field = explode('.', $field);
			$result[$field[0]][$field[1]] = $value;
		}
		return $result;
	}

	function getDataFromParent($parent, $id) {

		return $this->{$parent}->getActiveBranchData(
			$id,
			array('flatten' => 'unidimentional')
		);
	}

	function getActiveBranch($id, $childrenOnly = false, $options = array()) {

		$this->contain($this->getHasOneContain($this));
		$data = $this->findById($id);


		$title = sprintf('%s [%s]',
			$this->label,
			Set::check($data, $this->name . '.show')?
				$data[$this->name]['show']:$data[$this->name][$this->name . '__show']
		);


		$r = $this->__getTreeNode(
			array(
				'title'			=> $title,
				'href'			=> sprintf('%s/view/%s',
					getControllerName($this->name, true), $data[$this->name]['id']
				),
				'controller'	=> getControllerName($this->name),
				'id'			=> $this->name . '_' . $data[$this->name]['id'],
				'state'			=> 'open',
				'class' 		=> 'view_link initial'
			)
		);
		unset($data[$this->name]);


		$children = array();
		foreach ($data as $oneToOneModel => $v) {
			$children[] = $this->__getHasOneChildren($this->{$oneToOneModel}, $data);
		}

		foreach ($this->hasMany as $oneToManyModel => $v) {

			$title = $this->{$oneToManyModel}->label;

			$children[] = $this->__getTreeNode(
				array(
					'title'	=> $title,
					'href'	=> sprintf('%s/index/parent_id:%s',
						getControllerName($oneToManyModel, true),
						$id
					),
					'controller'	=> getControllerName($oneToManyModel),
					'id'			=> $oneToManyModel,
					'state'			=> 'closed',
					'class'			=> 'view_link one_to_many_in_tree',
				)
			);
		}

		if ($childrenOnly) {
			return $children;
		} else {
			$r['children'] = $children;
			return array('data' => $r);
		}
	}


	function __getHasOneChildren($Model, $data) {

		if (isset($data[$Model->name]['show']) || isset($data[$Model->name][$Model->name . '__show'])) {

			$tmpTitle = null;
			if (Set::check($data, $Model->name . '.show')
			&& !empty($data[$Model->name]['show'])) {
				$tmpTitle = $data[$Model->name]['show'];
			} elseif (Set::check($data, $Model->name . '__show')
			&& !empty($data[$Model->name][$Model->name . '__show'])) {
				$tmpTitle = $data[$Model->name][$Model->name . '__show'];
			}

			if (!empty($tmpTitle)) {
				$title = sprintf('%s [%s]',
					$Model->label,
					$tmpTitle
				);
			} else {
				$title = $Model->label;
			}

			$dataForTreeNode = array(
				'title'			=> $title,
				'href'			=> sprintf('%s/view/%s',
					getControllerName($Model->name, true), $data[$Model->name]['id']
				),
				'controller'	=> getControllerName($Model->name),
				'id'			=> $Model->name . '_' . $data[$Model->name]['id'],
				'state'			=> 'open',
			);

			if (!empty($Model->hasOne)) {
				$hasOneNode = $this->__getTreeNode($dataForTreeNode);
				foreach ($Model->hasOne as $hasOneModel => $v) {

					$hasOneNode['children'][] = $this->__getHasOneChildren($Model->{$hasOneModel}, $data[$Model->name]);
				}
			} else {
				if (!isset($dataForTreeNode['children'])) {
					unset($dataForTreeNode['state']);
				}
				$hasOneNode = $this->__getTreeNode($dataForTreeNode);
			}

			return $hasOneNode;
		}
	}
	

	function __getTreeNode($options) {

		$defaults = array('class'	=> 'view_link');
		$options = $options + $defaults;


		$r = array(
			'attr' 	=> array('id' => $options['id'], 'initial_title' => $options['title']),
			'data' 	=> array(
				'title' 		=> $options['title'],
				'attr'			=> array(
					'controller' 	=> strtolower($options['controller']),
					'class'			=> $options['class'],
					'title'			=> __('View', true) . ' ' . $options['title'],
					'href'			=> $options['href']
				)
			),
		);

		if (!empty($options['state'])) {
			$r['state']	= $options['state'];
		}

		return $r;
 
	}





	/** EVAL
    function __getCleanStr($str) {
        $str = str_replace('(', '', $str);
        $str = str_replace(')', '', $str);
        return trim($str);
    }

    function __getSimpleComparison($comparison) {

        $comparison = $this->__getCleanStr($comparison);

        $operators = array('==', '>', '<', '>=', '<=', '!=');
        foreach ($operators as $operator) {
            if (strstr($comparison, $operator)) {
                list($parta, $partb) = explode($operator, $comparison);
                $parta = str_replace("''", "'", "'" . trim($parta) . "'");
                $partb = str_replace("''", "'", "'" . trim($partb) . "'");
                switch ($operator) {
                    case '==':
                        $val = ($parta == $partb);
                    break;
                    case '>':
                        $val = ($parta > $partb);
                    break;
                    case '<':
                        $val = ($parta < $partb);
                    break;
                    case '>=':
                        $val = ($parta >= $partb);
                    break;
                    case '<=':
                        $val = ($parta <= $partb);
                    break;
                    case '!=':
                        $val = ($parta != $partb);
                    break;
                }
                return $val;
            }
        }
    }
	*/


   function evalExpression($formula, $data) {

        $formula = trim($formula);

        if (preg_match_all('/\[(.+)\]/U', $formula, $matches)) {
            foreach ($matches[1] as $match) {
                list($model, $field) = explode('.', $match);;
                $model = getModelName($model);
                $replaces['[' . $match . ']'] = "'" . $data[$model . '.' . $field] . "'";
            }
            $formula = str_replace(array_keys($replaces), $replaces, $formula);
        }

        $formula = str_replace('or', '||', $formula);
        $formula = str_replace('and', '&&', $formula);
        $formula = '(' . $formula . ');';

        eval("\$result = " . $formula);

        return $result;
    }

}