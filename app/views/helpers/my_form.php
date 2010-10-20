<?php

App::import('helper', 'form');

class MyFormHelper extends FormHelper {

	var $helpers = array('MyHtml', 'Html');
	
	function dateControl() {
	
		//static
		/** TODO: get ranges out of prev version */
		
		$out[] = $this->input('date', array('type' => 'date'));
		$out[] = $this->input('start', array('type' => 'time'));
		$out[] = $this->input('end', array('type' => 'time'));
		$out[] = $this->input('message', array('type' => 'text'));
		
		return $this->MyHtml->tag('div', $out);
		
	}
	
	
	
/**
 * Generates a form input element complete with label and wrapper div
 *
 * ### Options
 *
 * See each field type method for more information. Any options that are part of
 * $attributes or $options for the different **type** methods can be included in `$options` for input().
 *
 * - `type` - Force the type of widget you want. e.g. `type => 'select'`
 * - `label` - Either a string label, or an array of options for the label. See FormHelper::label()
 * - `div` - Either `false` to disable the div, or an array of options for the div.
 *    See HtmlHelper::div() for more options.
 * - `options` - for widgets that take options e.g. radio, select
 * - `error` - control the error message that is produced
 * - `empty` - String or boolean to enable empty select box options.
 * - `before` - Content to place before the label + input.
 * - `after` - Content to place after the label + input.
 * - `between` - Content to place between the label + input.
 * - `format` - format template for element order. Any element that is not in the array, will not be in the output.
 *     Default input format order: array('before', 'label', 'between', 'input', 'after', 'error')
 *     Default checkbox format order: array('before', 'input', 'between', 'label', 'after', 'error')
 *     Hidden input will not be formatted
 *
 * @param string $fieldName This should be "Modelname.fieldname"
 * @param array $options Each type of input takes different options.
 * @return string Completed form widget.
 * @access public
 * @link http://book.cakephp.org/view/1390/Automagic-Form-Elements
 */
	function input($fieldName, $options = array()) {


		if (!empty($options['alias'])) {

			static $aliases;
			if (empty($aliases)) {
				$aliases = getConf('/App/Aliases/.', 'name');
			}
		
			$validOptions['type'] = $aliases[$options['alias']]['type'];
			if (!empty($aliases[$options['alias']]['Values'])) {
				$validOptions['options'] = Set::combine($aliases[$options['alias']]['Values']['Value'], '{n}.name', '{n}.label');
			}
			$options = null;
			$options = $validOptions;
		}

		if (!empty($options['before'])) {
			$options['before'] = $this->Html->tag('span','[ '.$options['before'].' ]', array('class' => 'before explanation'));
		}
		if (!empty($options['after'])) {
			$options['after'] = $this->Html->div('after_container',$this->Html->tag('span','[ '.$options['after'].' ]', array('class' => 'after explanation')));
		}

		if (!empty($options['type'])) {

			if ($options['type'] == 'file') {


				$divOptions['class'] = 'file-uploader';
				if (!empty($options['resolution'])) {
					$divOptions['resolution'] = $options['resolution'];
					unset($options['resolution']);
				}
				if (!empty($options['extensions'])) {
					$divOptions['extensions'] = $options['extensions'];
					unset($options['extensions']);
				}


				$options['type'] = 'hidden';
				$theHidden = parent::input($fieldName, $options);

				$options['type'] = 'text';
				$options['readonly'] = true;
				$tmp = $this->domId($options);
				$tmp['id'] .= '__';
				$theInput = parent::input($fieldName . '__', $options);

				$theDiv = $this->Html->tag('div', '', $divOptions);
				return $this->Html->tag('div', $theHidden . $theInput . $theDiv, array('class' => 'file'));

			} elseif ($options['type'] == 'aux_card') {

				$attributes = $this->_initInputField($fieldName);
				$attributes['id'] .= uniqid();

				$theHidden = parent::input($fieldName,
					array(
						'type' 	=> 'hidden',
						'id' 	=> $attributes['id']
					)
				);

				$options['type'] = 'text';
				$options['readonly'] = true;
				$options['div'] = false;

				$theText = parent::input($fieldName . '__',
					array_merge(
						array('id' 		=> $attributes['id'] . '__'),
						$options
					)
				);


				if (empty($options['title'])) {
					$options['title'] = __('Search', true);
				}
				$wide = 'wide';
				if (!empty($options['wide'])) {
					if ($options['wide'] == 'No') {
						$wide = '';
					}
				}

				$theCaller = $this->MyHtml->image('edit.png',
					array(
						'class' 	=> 'open_modal multiple_modal ' . $wide,
						'title' 	=> $options['title'],
						'return_to' => $attributes['id'],
						'url' 		=> $options['url']
					)
				);
				$div = $this->Html->tag('div',
					$theText . $theCaller,
					array('class' => 'input text')
				);

				return $this->Html->tag('div',
					$theHidden . $div,
					array('class' => 'aux_card')
				);
			} else {
				return parent::input($fieldName, $options);
			}

		} else {
			return parent::input($fieldName, $options);
		}
	}

}