<?php 
/* * * * * * * * * * * * * * * * * * * * * *
** findFile
** JS library: Mootools 1.4.5 - http://mootools.net
** Author: Jorge Chaclán - http://kukugt.com
** Twitter: @kukugt
*/
if (isset($_REQUEST['dir'])): include_once('findFile/findFile.php'); else:
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="pragma" content="no-cache" />
	<title>findFile</title>
	<meta name="application-name" content="findFile" />
	<meta name="author" content="Jorge Chaclán">
	<meta name="version" content="1" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<link rel="shortcut icon" href="findFile/img/favicon.png" type="image/x-icon" />
	<link rel="apple-touch-icon" href="findFile/img/apple-touch-icon.png" />
	<link rel="apple-touch-startup-image" href="findFile/img/ios-startup-320x460.png" />
	<link rel="stylesheet" type="text/css" href="findFile/css/findFile.css" />
	<script type="text/javascript" src="findFile/js/mootools.js"></script>
	<script type="text/javascript" src="findFile/js/More.js"></script>
	<script type="text/javascript" src="findFile/js/Nested.js"></script>
	<script type="text/javascript" src="findFile/js/findFile.js"></script>
</head>
<body></body>
</html>
<?php endif;