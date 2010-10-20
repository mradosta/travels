<html>
<head>
<script type="text/javascript">
	var base_url = "<?php echo Router::url('/'); ?>";
</script>
	<title><?php __('User'); ?></title>

	<?php
		echo $this->Html->css('smoothness/jquery-ui-1.8.1.custom');
		echo $this->Html->css('app.generic') . "\n"; 
		echo $this->Html->css('app.user') . "\n"; 
		echo $this->Html->css('app.user.jquery-ui/jquery-ui-1.8.2.custom');


		$jsFiles[] = 'jquery/jquery-1.4.2';
		$jsFiles[] = 'jquery/jquery-ui-1.8.1.custom';

		$jsFiles[] = 'default';
		$jsFiles[] = 'dialogs';
		$jsFiles[] = 'modals';
		$jsFiles[] = 'uploaders';
		echo $this->Html->script($jsFiles);

		$info = json_encode(
			array(
				'base_url'				=> Router::url('/') . $this->params['prefix'] . '/',
				'current_controller' 	=> $this->params['controller'],
				'current_action' 		=> $this->params['action']
			)
		);

	?>
	
</head>
<body>

	<div id="page">

		<?php
		$user = User::get();
		echo $this->element('header', array('user' => $user));

		if (!empty($user['sustitutor_id'])) {
			$sustitutionMenuLabel = __('Sustitution off', true);
			$sustitutionMenuTitle = __('Set sustitution mode off', true);
			$sustitutionMenuClass = '';
			$sustitutionMode = 'off';
		} else {
			$sustitutionMenuLabel = __('Sustitution on', true);
			$sustitutionMenuTitle = __('Set sustitution mode on', true);
			$sustitutionMenuClass = 'open_modal';
			$sustitutionMode = 'on';

			$modeOptions['class'] = 'hidden';
		}
		$modeOptions['id'] = 'mode_bar';
		echo $this->MyHtml->tag('div', __('Sustitution Mode', true), $modeOptions);

		?>

		<div id="actions_bar">

			<div class="left">
				<?php
				$links = null;
				$links[] = $this->MyHtml->link(
					__('My Subjects', true),
					array(
						'user'			=> false,
						'controller'	=> 'subjects',
						'action'		=> 'lists',
					),
					array('class' => 'open_modal wide', 'title' => __('Subjects Office List', true))
				);
				echo $this->element('actions', array('links' => $links));

				$links = null;
				$links[] = $this->MyHtml->link(
					__('My Matters', true),
					array(
						'user'			=> false,
						'controller'	=> 'matters',
						'action'		=> 'lists'
					),
					array('class' => 'open_modal wide', 'title' => __('Cases Office List', true))
				);
				echo $this->element('actions', array('links' => $links));

				$links = null;
				$links[] = $this->MyHtml->link(
					__('My Queries', true),
					array('user' => false, 'controller' => 'queries', 'action' => 'search'),
					array('class' => 'open_modal', 'title' => __('Query', true))
				);
				echo $this->element('actions', array('links' => $links));
				?>
			</div> <!-- left div-->

			<div class="right">
				<?php
				//Actions
				$links = null;
				$links[] = $this->MyHtml->link(
					__('Add Charter', true),
					array(
						'controller'	=> 'charters',
						'action'		=> 'add',
					),
					array('class' => 'open_modal', 'title' => __('Add Charter', true))
				);
				echo $this->element('actions', array('links' => $links));


				
			?>
			</div> <!-- right div-->
		</div> <!-- actions_bar-->

		<div id="content_ext">
			<h1 class="title"><?php __('My Space') ?></h1>
			<div id="content_for_layout">
				<div id="content">
					<?php echo $content_for_layout; ?>
				</div>
			</div>
		</div>

 	</div><!--page-->

	
	<script type="text/javascript">
	
		var info = '<?php echo $info; ?>';

		/** Set the refresh timmer */
		var refresh = setInterval(
			function() {

				// Get active tabs inside tasks
				var taskUrl;
				$('#tasks ul li').each(
					function() {
						if ($(this).hasClass('active')) {
			
							taskUrl = $('a', $(this)).attr('href');
						}
					}
				);
				$('#tasks').load(taskUrl);

				$('#alerts').load($.path('events/alerts'));

				$('#dates').load($.path('events/dates/date:' + $.datepicker.formatDate('yy-mm-dd', $('#datepicker').datepicker('getDate'))));

			}, <?php echo ((int)getConf('/App/refresh') * 1000); ?>
		);


		$(document).ready(function($) {

			$.getJSON($.path('logs/has_sustitutions'),
				function(data) {
					if (data == 'true') {
						var a = $('<a/>').attr(
							'href', $.path('logs/show_sustitutions')
						).addClass('open_modal');
						$(a).trigger('click').remove();
					}
				}
			);


			/** Show flash message comming straight out from the controller */
			var flashMessage = '<?php echo str_replace("'", "\'", $this->Session->flash()); ?>';
			if (flashMessage != '') {
				var flashMessageData = flashMessage.split('|');
				if (flashMessageData[0] == 'SUCCESS') {
					dialogs.showSuccess(flashMessageData[1]);
				} else if (flashMessageData[0] == 'ERROR') {
					dialogs.showError(flashMessageData[1]);
				}
			}

		}); //$(document).ready
	</script>
		
	<?php echo $scripts_for_layout; ?>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>