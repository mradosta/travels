<?php

	$lis = null;

	
	if (empty($options)) {
		$options = array();
	}
	$defaults = array('class' => 'button', 'div' => true);

	if (!empty($div_id)) {
		$defaults = array_merge($defaults, array('id' => $div_id));
	}

	if (Set::countDim($links) == 1) {
		
		$options += $defaults;

		$content = $links;

	} else {

		$menuLabel = array_pop(array_keys($links));

		foreach ($links[$menuLabel] as $link) {

			$lis[] = $this->MyHtml->tag('li', $link);
		
		}

		$ul = null;
		if (!empty($lis)) {
			$ul = $this->MyHtml->tag('ul', $lis);
		}

		$defaults['id']= 'actions';
		$options += $defaults;

		$content = $menuLabel . $ul;

	}


	if ($options['div']) {
		echo $this->MyHtml->tag('div', $content, $options);
	} else {
		echo $content;
	}
