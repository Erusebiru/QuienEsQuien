<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?	
		session_start();
		session_destroy();
		header('Location: '."index.php");
	?>
</body>
</html>