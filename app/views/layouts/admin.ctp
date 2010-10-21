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
				<?php echo $this->MyHtml->link(__('Charters', true), array('controller' => 'charters', 'action' => 'index')); ?>
				<?php echo $this->MyHtml->link(__('Destinations', true), array('controller' => 'destinations', 'action' => 'index')); ?>
				<?php echo $this->MyHtml->link(__('Passengers', true), array('controller' => 'passengers', 'action' => 'index')); ?>
				<?php echo $this->MyHtml->link(__('Users', true), array('controller' => 'users', 'action' => 'index')); ?>
				<?php echo $this->MyHtml->link(__('Logout', true), array('admin' => false, 'controller' => 'users', 'action' => 'logout', 'admin' => false)); ?>
			</h1>
		</div>
		<div id="content">

			<?php
				$message = explode('|', $this->Session->flash());
				if (!empty($message[0])) {
					echo $this->MyHtml->tag('div', $message[1], array('class' => 'message ' . $message[0]));
				}
			?>

			<?php echo $content_for_layout; ?>

		</div>
		<div id="footer">
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>