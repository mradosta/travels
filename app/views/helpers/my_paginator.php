<?php

App::import('helper', 'paginator');

class MyPaginatorHelper extends PaginatorHelper {


	var $helpers = array('MyHtml', 'Html');


/**
 * Everwrite cakephp sort method to remove order option.
 *
 * @return string The title of the "link".
 * @access public
 */
	function sort($title, $key = null, $options = array()) {
		return $title;
	}


/**
 * Returns a formatted div tag with the navigation over paginated data.
 *
 * @param boolean $counter Specify where to return or no the counter.
 * @return string The formatted div tag element.
 * @access public
 */
	function getNavigator($counter = true, $options = array()) {

		if (!empty($options)) {
			$this->options($options);
		}

		if ($counter === true) {
			$counterHtml = $this->counter(
				array('format' => '<div id="results">' . __('Showing', true) . ' <span class="current">%start% - %end%</span> ' . __('of', true) . ' <span class="total">%count%</span> ' . __('Results', true) . '</div>')
			);
		} else {
			$counterHtml = '';
		}


		$navigator[] = $this->prev('< ' . __('previous', true),
			array(),
			null,
			array('class'=>'disabled')
		);

		$navigator[] = $this->numbers(array('before' => ' ', 'after' => ' ', 'separator' => ' '));
		$navigator[] = $this->next(__('next', true) . ' >',
			array(),
			null,
			array('class' => 'disabled')
		);

		$navigatorHtml = $this->MyHtml->tag('div',
			implode('', $navigator),
			array('id' => 'nav')
		);

		return $this->MyHtml->tag('div', $counterHtml . $navigatorHtml, array('id' => 'paginator'));
	}

}