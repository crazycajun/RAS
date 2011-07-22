<?php require_once('utils/header_funcs.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--

	Nonzero1.0 by nodethirtythree design
	http://www.nodethirtythree.com
	missing in a maze

-->
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
		<title>Recall Alert System - RAS</title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<link rel="stylesheet" type="text/css" href="content/style.css" />
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
		<script type="text/javascript">
			var ras = {
				currentDir: '<?php echo $rasCurrentDir; ?>';
			};
		</script>
	</head>
	<body>

		<div id="header">
		
			<div id="header_inner" class="fixed">
		
				<div id="logo">
					<h1><span>RAS</span></h1>
				</div>
				
				<div id="menu">
					<ul>
						<li><a href="index.php" class="<?php if ($pageName == 'home') echo 'active'; ?>">Home</a>
						<li><a href="search.php" class="<?php if ($pageName == 'search') echo 'active'; ?>">Search</a></li>
						<li><a href="login.php" class="<?php if ($pageName == 'login') echo 'active'; ?>">Log In</a></li>
					</ul>
				</div>
				
			</div>
		</div>
		<!-- Starts the main content div -->
		<div id="main">

	<div id="main_inner" class="fixed">
		<div id="primaryContent_columnless">
			<div id="columnA_columnless">