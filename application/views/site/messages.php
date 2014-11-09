<?php if (isset($GLOBALS['messages']) && $GLOBALS['messages']) { ?>
	<?php foreach ($GLOBALS['messages'] as $message) {
		list($type, $text) = $message;
	?>
		<div class="alert alert-warning"><?=$text?></div>
	<?php } ?>
<?php } ?>
