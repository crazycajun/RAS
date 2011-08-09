<?php

$pageName = "register";
include('templates/header.php'); 

?>

<h3>Register - Success!!</h3>
<p>Your registration has been processed. Unfortunately, there are "bots" out there
   that like to crawl websites and register bogus members. Therefore, we have added
   an additional step. An email has been sent to you that contains an activation token.
   Go to our <a href='activate.php'>activation page</a> and enter the token to
   complete the process.
</p>
   <p>
      Thanks again for using RAS!!
   </p>

<?php include('templates/footer.php'); ?>