<?php



	$fields = array();

	foreach($data as $field => $value) {
		$fields[] = $this->MyHtml->tag('dt',
			$field
		);
		$fields[] = $this->MyHtml->tag('dd',
			((empty($value)) ? '&nbsp;' : $value)
		);
	}

	echo $this->MyHtml->tag('div', $this->MyHtml->tag('dl', $fields), array('class' => 'view'));
