<?php 

require_once('utils/header_funcs.php');
require_once('classes/Db.php');
require_once('interfaces/iUserAccountRepository.php');
require_once('interfaces/iPhpWebHelpers.php');
require_once('classes/TokenGenerator.php');
require_once('classes/Validator.php');
require_once('classes/FlashMessenger.php');
require_once('classes/EmailService.php');
require_once('classes/SystemClock.php');
require_once('classes/UserAccountRepository.php');
require_once('utils/PhpWebHelpers.php');
require_once('classes/tasks/RegisterMemberTask.php');

$task = new RegisterMemberTask(
	 new UserAccountRepository()
	,new FlashMessenger()
	,new PhpWebHelpers()
	,new EmailService()
);

$task->execute();

?>