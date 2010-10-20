<?php 

$out[] = $this->MyForm->create('User', array('class' => 'ajax_form'));

$replacers = '';
$replacers = $this->MyHtml->tag(
        'div',
        __('Target User', true),
        array('class' => 'title')
);
$replacers .= $this->MyForm->input('replacer_user_id',
	array(
		'multiple'	=> 'checkbox',
		'label' 	=> __('Replacer', true),
                'div'           => 'input select content',
		'options'	=> Set::combine($allowedUsersOfMyOffice, '{n}.Replacer.id', '{n}.Replacer.full_name'),
	)
);

$content2 = $this->MyHtml->tag('fieldset', $replacers);
$content[] = $this->MyHtml->tag('div', $content2, array('id' => 'replacers', 'class' => 'checkboxgroup'));

$out[] = $this->MyHtml->tag('div', $content, array('id' => 'container'));

$out[] = $this->element('footer');

$out[] = $this->MyForm->end();

echo implode("\n", $out);


echo $this->MyHtml->scriptBlock('

	var afterRender = function() {

		$(document).ready(function($) {
			var checkeds = "' . implode('|', Set::extract('/Replacement[state=active]/replacer_user_id', $allowedUsersOfMyOffice)) . '".split("|");

			$("#replacers input:checkbox").each(function() {
				$(this).removeAttr("checked");
				if ($.inArray($(this).val(), checkeds) >= 0) {
					$(this).attr("checked", "checked");
				}
			});

		});
	};

', array('inline' => true)
);