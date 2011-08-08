<?php

// This is the facade that will send all system emails.
class EmailService {
	function sendMemberConfirm($email, $token) {
		$emailBody = <<<BODY
Thank you for registering with the RAS System. Below is your account activation token. 
Please navigate to our home page and click on the Account Activation button in the footer. 
Enter the email address used during registration and the token below to activate your account.\n
\n
Token:	$token\n
\n
Thanks,\n
RAS Team
BODY;
    	
    	mail($email, 'RAS Membership Activation', $emailBody);
	}
}

?>