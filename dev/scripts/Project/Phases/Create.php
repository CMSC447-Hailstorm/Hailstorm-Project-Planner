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
		<link href ="../../style.css" rel="stylesheet">
	</head>
	<body>
	
		<!--Title Bar-->
		<div class="w3-top w3-card">
			<div class="w3-bar w3-padding">
				<a class="w3-bar-item" ><h1>Project Planner</h1></a>
			</div>
		</div>	
		
		<div class="w3-container">
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] 
									. "?prid=" . $_GET['prid']); ?>" autocomplete="off">
			<div class="w3-panel w3-display-middle w3-border w3-padding">
			
			<label>Name:</label>
			<input class="w3-input w3-border" type="text" name="Name" required></br>
			
			<label>Description:</label>
			<input class="w3-input w3-border" type="text" name="Description" required></br>
			
			<button class="w3-button w3-green" type="submit" name="PhaseSubmit">Save</button>
			<button class="w3-button w3-red" name="cancel">Cancel</button>
			
			</div>
		</form>
		</div>
	
	</body>
	<footer>
	</footer>
</html>