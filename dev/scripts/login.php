<?php
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/classes/Session.class.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/classes/User.class.php");
	Session::Start();

	if(isset($_POST['submit']) && !empty($_POST)) 
	{
		$User_ID = $_POST['User_ID'];
		$User_Password = $_POST['User_Password'];
	
		$_SESSION['CURRENT_USER'] = new User();
		
		if ($_SESSION['CURRENT_USER']->Login($User_ID, $User_Password))
		{
			header("Location: Project/View.php");
		}
		else
		{
			echo "<script type='text/Javascript'>alert('Error: Username or Password invalid.');</script>";
		}
	}
?>

<html>
	<head>
		<meta charset=utf-8 />
		<link href ="style.css" rel="stylesheet">
	</head>
	<body>
		
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
			<div class="login_box">
			
				User ID: </br>
				<input type="text" placeholder="Enter ID" name="User_ID" required></br>
				
				Password: </br>
				<input type="password" placeholder="Enter Password" name="User_Password" required></br>
				
				</br>
				<button type="submit" name="submit">Login</button>
				
			</div>
		</form>

	</body>
	<footer>
	</footer>
</html>
