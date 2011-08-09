<?php

$pageName = "register";
require_once('classes/FlashMessenger.php');
require_once('utils/forms.php');
include('templates/header.php'); 

?>

<h3>Register</h3>
<p>Fill this form out to register as a member of the Recall Alert System. As a member, 
you can sign up for recall notifications.
</p>

<?php rasFlash(); ?>

<form id="registrationForm" action="registerMember.php" method="post">
	<p>
		<?php echo rasRequiredLabel("Name", "memberName"); ?><br />
		<?php echo rasRequiredTextField(array(
			 'name' => 'memberName'
			,'id' => 'memberName'
			,'maxlength' => 256
			,'value' => $_GET['n']
		)); ?>
	</p>
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
		<?php echo rasRequiredLabel("Password", "memberPassword"); ?><br />
		<?php echo rasRequiredPassword(array(
			 'name' => 'memberPassword'
			,'id' => 'memberPassword'
			,'maxlength' => 256
		)); ?><br />
		<?php echo rasRequiredLabel("Confirm Password", "memberPasswordConfirm"); ?><br />
		<?php echo rasPasswordConfirmation('memberPassword'); ?>
	</p>
	<input type="submit" value="Register" />
</form>

<script type="text/javascript">
(function($){
	// Register with JQuery's document ready function to ensure
	// that jQuery Validate plugin is validating the form.
	$(function(){
		// Register the form for validation with JQuery.validate
		$('#registrationForm').validate();
	});
})(jQuery);
</script>

<?php include('templates/footer.php'); ?>