<?php

App::import('helper', 'text');

class MyTextHelper extends TextHelper {


	/**
	 * If a string is too long, shorten it in the middle
	 * @param string $text
	 * @param int $limit
	 * @return string
	 */
	function shorten($text, $limit = 25) {
		if (strlen($text) > $limit) {
			$pre = substr($text, 0, ($limit / 2));	
			$suf = substr($text, - ($limit / 2));	
			$text = $pre .' ... '. $suf;
		}
		
		return $text;
	}

	
}