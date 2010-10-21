<div class="users">
<?php
	echo $this->Form->create('User', array('action' => 'login'));
?>
	<fieldset>
		<legend><?php __('Login'); ?></legend>
	<?php
		echo $this->Form->input('username', array('label' => __('Username', true)));
		echo $this->Form->input('password', array('label' => __('Password', true)));
	?>
	</fieldset>
<?php echo $this->MyForm->submit(__('Submit', true), array('class' => 'action', 'id' => 'save', 'div' => false));
	$this->Form->end();
?>
</div>