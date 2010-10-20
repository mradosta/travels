<?php 

$out[] = $this->MyForm->create('User', array('class' => 'ajax_form'));

$content[] = $this->MyForm->input('user_id',
	array(
		'label' 	=> __('User to replace', true),
		'options'	=> $allUsers,
		'empty'		=> true
	)
);

$replacers = $this->MyForm->input('replacer_user_id',
	array(
		'multiple'	=> 'checkbox',
		'label' 	=> __('Replacer', true),
		'options'	=> $usersOfMyOfficeButMe,
	)
);
$content[] = $this->MyHtml->tag('div', $replacers, array('id' => 'replacers', 'class' => 'hidden'));

$out[] = $this->MyHtml->tag('div', $content, array('id' => 'container'));

$out[] = $this->element('footer');

$out[] = $this->MyForm->end();

echo implode("\n", $out);


echo $this->MyHtml->scriptBlock('

	var afterRender = function() {
		$(document).ready(function($) {

			$("#UserUserId").change(
				function() {

					$("#replacers input").each(function() {
						$(this).removeAttr("checked");
					});

					if ($("option:selected", $(this)).val() != "") {
						$.getJSON($.path("users/get_replacers/" + $("option:selected", $(this)).val()), function(data) {
							$.each(data, function(i, replacer) {
								$("#UserReplacerUserId" + replacer.Replacement.replacer_user_id).attr("checked", "checked");
							});
						});
						$("#replacers").show();
					} else {
						$("#replacers").hide();
					}
				}
			);
		
		});
	};

', array('inline' => true)
);