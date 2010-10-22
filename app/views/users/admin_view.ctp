<?php

	$out =  null;

	/** Actions */
	$links =  null;
	$links[] = $this->MyHtml->link(
		__('Edit', true),
		array(
			'controller' 	=> 'users', 
			'action' 		=> 'edit',
			$data['User']['id']
		),
		array(
			'class' => 'open_modal',
			'title' => __('Add new user', true)
		)
	);
	$links[] = $this->MyHtml->link(
		__((($data['User']['state'] == 'active') ? 'Block' : 'Active' ) . ' ' .'User', true),
		array(
			'controller' 	=> 'users',
			'action' 		=> 'block',
			(($data['User']['state'] == 'active') ? 'blocked' : 'active' ),
			$data['User']['id']
		),
		array(
			'title' => __('Block User', true)
		)
	);
	$links[] = $this->MyHtml->link(
		__('Delete', true),
		array(
			'controller' 	=> 'users', 
			'action' 		=> 'delete',
			$data['User']['id']
		),
		array(
			'title' => __('Delete the user', true)
		),
		__('Are your sure to delete the user?', true)
	);
	echo $this->element('actions',
		array('links' => array(
			__('Actions', true) => $links)
		)
	);

	
	$out[] = $this->MyHtml->tag('span', __('Full Name', true), array('class' => 'label'));
	$out[] = $this->MyHtml->tag('span', $data['User']['full_name'], array('class' => 'data'));

	$out[] = $this->MyHtml->tag('span', __('Email', true), array('class' => 'label'));
	$out[] = $this->MyHtml->tag('span', $data['User']['email'], array('class' => 'data'));

	$out[] = $this->MyHtml->tag('span', __('Last login', true), array('class' => 'label'));
	$out[] = $this->MyHtml->tag('span', $data['User']['last_login'], array('class' => 'data'));

	$out[] = $this->MyHtml->tag('span', __('Reset code', true), array('class' => 'label'));
	$out[] = $this->MyHtml->tag('span', ' ', array('class' => 'data'));

	$out[] = $this->MyHtml->tag('span', __('Web app access', true), array('class' => 'label'));
	$out[] = $this->MyHtml->tag('span', ' ', array('class' => 'data'));

	/*$out[] = $this->MyHtml->tag('span', __('Stats', true), array('class' => 'label'));
	$out[] = $this->MyHtml->tag('span',
		$this->MyForm->input(
			'stats',
			array(
				'multiple'	=> 'checkbox',
				'options'	=> $reports,
				'div'       => array('class' => ''),
				'label'		=> false
			)
		),
		array('class' => 'data')
	);*/

	

			
	echo $this->MyHtml->tag('div', $out, array('id' => 'container'));

	echo $this->MyHtml->scriptBlock('

		var afterRender = function() {
			
			$.getJSON($.path("users/get_user_allowed_lists/' . $data['User']['id'] .'"), function (data) {
				$(".lists .checkbox input:checkbox").each(function() {
					if ($.inArray($(this).val(), data) >= 0) {
						$(this).attr("checked", "checked");
					}
				});
			});

		};
	');