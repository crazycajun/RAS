<?php

$pageName = 'activate';
require_once('classes/FlashMessenger.php');
require_once('utils/forms.php');
include('templates/header.php'); 

?>

<h3>Membership Activation</h3>
<p>Please enter the account activation information below.</p>



<?php rasFlash(); ?>

<form id="memberActivationForm" action="activateMember.php" method="post">
	<p>
		<?php echo rasRequiredLabel("Email", "memberEmail"); ?><br />
		<?php echo rasRequiredTextField(array(
			 'name' => 'memberEmail'
			,'class' => 'email'
			,'id' => 'memberEmail'
			,'maxlength' => 256
			,'value' => $_GET['e']
		)); ?>
	</p>
	<p>
		<?php echo rasRequiredLabel("Token", "activationToken"); ?><br />
		<?php echo rasRequiredTextField(array(
			 'name' => 'activationToken'
			,'id' => 'activationToken'
			,'maxlength' => 256
			,'value' => $_GET['t']
		)); ?>
	</p>
	<input type="submit" value="Activate" />
</form>

<script type="text/javascript">
(function($){
	// Register with JQuery's document ready function to ensure
	// that jQuery Validate plugin is validating the form.
	$(function(){
		// Register the form for validation with JQuery.validate
		$('#memberActivationForm').validate();
	});
})(jQuery);
</script>

<?php include('templates/footer.php'); ?>