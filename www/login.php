<?php

$pageName = 'login';
require_once('utils/forms.php');
include('templates/header.php'); 

?>

<h3>Membership Login</h3>
<form id="memberLoginForm" action="loginMember.php" method="post">
	<p>
		<?php echo rasRequiredLabel("Email", "memberEmail"); ?><br />
		<?php echo rasRequiredTextField(array(
			 'name' => 'memberEmail'
			,'id' => 'memberEmail'
		)); ?>
	</p>
	<p>
		<?php echo rasRequiredLabel("Password", "memberPassword"); ?><br />
		<?php echo rasRequiredPassword(array(
			 'name' => 'memberPassword'
			,'id' => 'memberPassword'
		)); ?><br />
	</p>
	<input type="submit" value="Login" />
</form>

<script type="text/javascript">
(function($){
	// Register with JQuery's document ready function to ensure
	// that jQuery Validate plugin is validating the form.
	$(function(){
		// Register the form for validation with JQuery.validate
		$('#memberLoginForm').validate();
	});
})(jQuery);
</script>

<?php include('templates/footer.php'); ?>