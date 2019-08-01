<div class="notification information png_bg" style="padding:25px; width:auto; margin:10px;">

<h2><?php echo __d('cake_dev', 'Database Error'); ?></h2>
<p>
	<strong><?php echo __d('cake_dev', 'Error'); ?>: </strong>
	<?php echo h($error->getMessage()); ?>
</p>
<?php if (!empty($error->queryString)) : ?>
	<p class="notice">
		<strong><?php echo __d('cake_dev', 'SQL Query'); ?>: </strong>
		<?php echo  $error->queryString; ?>
	</p>
<?php endif; ?>
<?php if (!empty($error->params)) : ?>
		<strong><?php echo __d('cake_dev', 'SQL Query Params'); ?>: </strong>
		<?php echo  Debugger::dump($error->params); ?>
<?php endif; ?>
<p class="notice">
	<strong><?php echo __d('cake_dev', 'Notice'); ?>: </strong>
	<?php echo __d('cake_dev', 'If you want to customize this error message, create %s', APP_DIR . DS . 'View' . DS . 'Errors' . DS . 'pdo_error.ctp'); ?>
</p>
<?php echo $this->element('exception_stack_trace'); ?>
</div>