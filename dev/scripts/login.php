<?php
	session_start();
	
	if(isset($_POST['submit'])) {
		
		$User_ID = $_POST['User_ID'];
		$User_Password = $_POST['User_Password'];
		
		if($User_ID != '' && $User_Password != '') {

			$sql = "SELECT * FROM Users WHERE User_ID ='".$User_ID."' AND User_Password ='".$User_Password."'";
			$conn = mysql_connect(host, user, password);
			if(!$conn) {
				die('Unable to connect: ' . mysql_error());
			}
			
			$Result = mysql_query($sql, $conn);
			
			//Validate Results
			
		} else {
			
			//Display error message
			
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
				<input type="text" placeholder="Enter ID" name="User_ID"></br>
				
				Password: </br>
				<input type="password" placeholder="Enter Password" name="User_Password"></br>
				
				</br>
				<button type="submit">Login</button>
				
			</div>
		</form>

	</body>
	<footer>
	</footer>
</html>
