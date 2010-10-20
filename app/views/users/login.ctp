<div class="users form">
<?php echo $this->Form->create('User', array('action' => 'login'));?>
	<fieldset>
		<legend><?php __('Login'); ?></legend>
	<?php
		echo $this->Form->input('email');
		echo $this->Form->input('password');
		echo $this->Form->input('code', array('label' => __('Coordinates for ',true) . $coordinates['i'] . $coordinates['j'] . ' *'));
		echo $this->Form->input('coordinates', array('type' => 'hidden', 'value' => serialize($coordinates)));		
	?>
	<div class="help_msg"><?php echo __('* Leave it empty if the first time you login',true);?></div>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
