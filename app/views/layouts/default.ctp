<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');
		echo $this->Html->css('smoothness/jquery-ui-1.8.1.custom');

		$jsFiles[] = 'jquery/jquery-1.4.3.min';
		$jsFiles[] = 'jquery/jquery-ui-1.8.1.custom';
		$jsFiles[] = 'default';
		echo $this->Html->script($jsFiles);

		echo $scripts_for_layout;
	?>
</head>
<body>
	<div id="main-container">
		<div id="header">
			<h1>
				<?php echo $this->MyHtml->link(__('Passengers', true), array('controller' => 'passengers', 'action' => 'index')); ?>
				<?php echo $this->MyHtml->link(__('Change password', true), array('controller' => 'users', 'action' => 'change_password')); ?>
				<?php echo $this->MyHtml->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout')); ?>
			</h1>
		</div>
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $content_for_layout; ?>

		</div>
		<div id="footer">
			<?php //echo $this->Html->link(
//					$this->Html->image('cake.power.gif', array('alt'=> __('CakePHP: the rapid development php framework', true), 'border' => '0')),
//					'http://www.cakephp.org/',
//					array('target' => '_blank', 'escape' => false)
//				);
			?>
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>