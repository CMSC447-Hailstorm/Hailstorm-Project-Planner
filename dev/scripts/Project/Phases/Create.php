<?php
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/classes/Session.class.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/classes/User.class.php");
	Session::Start();
	if(!Session::UserLoggedIn())
	{
		header("Location: /login.php");
	}
	if(!isset($_GET))
	{
		header("Location: ../../home.php");
	}
	$conn = mysqli_connect($_SESSION["SERVER"], $_SESSION["DBUSER"], $_SESSION["DBPASS"], $_SESSION["DATABASE"]);
	if (!$conn)
	{
		die('Unable to connect' . mysqli_connect_error());
	}

	if(isset($_POST['PhaseSubmit']) && !empty($_POST))
	{
		$phaseName = $_POST['Name'];
		$description = $_POST['Description'];
		$creator = $_SESSION['CURRENT_USER']->getUserID();
		$project = $_GET['prid'];

		// get users assigned to phase

		$sql = "INSERT INTO Phases (User_ID_FK, Project_ID_FK, Phase_Name, Phase_Description) 
									VALUES
									('$creator', '$project', '$phaseName', 
									'$description')";

		if(mysqli_query($conn, $sql))
		{
			header("Location: ../View.php?proj=" . $project);
		}
		else
		{
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}
?>
<html>
	<head>
		<meta charset=utf-8 />
		<link href ="Style.css" rel="stylesheet">
	</head>
	<body>
	
		<h2>Create New Phase</h2>
		
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] 
									. "?prid=" . $_GET['prid']); ?>" autocomplete="off">
			Name: <input type="text" name="Name" required></br>
			Description: <input type="text" name="Description" required></br>
			
			
			<button type="submit" name="PhaseSubmit">Save</button>
			<a href="<?php echo '../View.php?proj=' . $_GET['prid'] ?>"><button type="cancel" name="cancel">Cancel</button></a>
		</form>
			
	</body>
	<footer>
	</footer>
</html>