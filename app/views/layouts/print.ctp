<html>
<head>

	<title><?php __('Login'); ?></title>

	<?php 
		echo $this->Html->css('smoothness/jquery-ui-1.8.1.custom');
		echo $this->Html->css('app.print') . "\n";


		$jsFiles[] = 'jquery/jquery-1.4.2';
		$jsFiles[] = 'jquery/jquery-ui-1.8.1.custom';

		$jsFiles[] = 'default';
		echo $this->Html->script($jsFiles);

		
		$info = json_encode(
			array(
				'base_url'				=> Router::url('/'),
				'current_controller' 	=> $this->params['controller'],
				'current_action' 		=> $this->params['action']
			)
		);

	?>
	
</head>
<body>

	<div id="page">

		<div id="login">
				
				<div id="content_for_layout">
					<?php echo $content_for_layout; ?>
				</div>
				
		</div>
	</div>
	
	<script type="text/javascript">
	<?php echo $scripts_for_layout; ?>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>