<div id="header">
	<div id="logo">
		<?php echo $this->MyHtml->image('logo.png') . "\n"; ?>
	</div>
	<div id="info">
		<div id="login_date">
			<div class="left"><?php echo date('Y-m-d'); ?></div>
			<div class="right">Kaikaia Nicaragua</div>
		</div>

		<div id="login_info">
			<?php
				echo $this->MyHtml->tag('div', $user['full_name'], 'top');
				echo $this->MyHtml->tag('div', __('Last Login', true) . ': ' . $user['last_login'], 'bottom');
			?>
		</div>

		<div id="login_actions">
			<div class="left">
				<?php
					echo $this->MyForm->input('language',
						array(
							'label'		=> false,
							'div'		=> false,
							'class' 	=> 'combo',
							'options' 	=> getConf('/App/Languages/Language/.', 'code', 'name')
						)
					);
				?>
			</div>
			<div class="right">
				<?php echo $this->MyHtml->link(
					__('Exit', true),
					array(
						'admin'			=> false,
						'user'			=> false,
						'data'			=> false,
						'controller' 	=> 'users',
						'action' 		=> 'logout'
					),
					array('class' => 'button'),
					__('Are you sure you want to leave the App?', true));
				?>
			</div>
		</div>
	</div><!--info-->
</div><!--header-->
