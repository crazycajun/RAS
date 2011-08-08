<?php
// This class is intentionally named without the "_test" convention
// so that it is not run with the test suites. You can run this in the browser
// or the commandline (php EmailServiceTests.php)

require_once('../../classes/EmailService.php');

$emailAddress = 'bchiasso@hotmail.com';

if (is_null($emailAddress)) throw new RuntimeException('Put in an email address to test this silly!');

$service = new EmailService();
$service->sendMemberConfirm($emailAddress, 'fakeToken');

echo 'Now go check your email as long as there was no error!!\n';

?>