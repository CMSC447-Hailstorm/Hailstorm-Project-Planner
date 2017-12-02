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
		$phaseName = mysqli_real_escape_string($conn, $_POST['Name']);
		$description = mysqli_real_escape_string($conn, $_POST['Description']);
		$creator = $_SESSION['CURRENT_USER']->getUserID();
		$project = mysqli_real_escape_string($conn, $_GET['prid']);

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

<!DOCTYPE html>
<html>
	<head>
		<meta charset=utf-8 />
		<link href ="/style.css" rel="stylesheet">
	</head>
	<body>
	
		<!--Title Bar-->
		<div class="w3-top w3-card w3-white" style="height:10%">
			<div class="w3-bar w3-padding">
				<a class="w3-bar-item" href="/home.php"><h1>Project Planner</h1></a>
				<div class="w3-right">
					<a class="w3-bar-item" href="/Users/View.php">Logged in as <?php echo $_SESSION['CURRENT_USER']->GetFirstName() . " " . $_SESSION['CURRENT_USER']->GetLastName() . " (" . $_SESSION['CURRENT_USER']->GetUsername() . ")";?></a>
					<a href="/logout.php"><button class="w3-bar-item w3-button w3-red">Sign Out</button></a>
				</div>
			</div>
		</div>
		
		<!--Form-->
		<div class="w3-container" style="margin-top:10%">
			<div class="w3-container w3-display-middle" style="width:50%">
				<a href="<?php echo '../View.php?proj=' . $_GET['prid'] ?>"><button class="w3-button w3-red" type="cancel" name="cancel">Cancel</button></a>
				<div class="w3-border w3-padding">
				<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] 
											. "?prid=" . $_GET['prid']); ?>" autocomplete="off">
											
					<label>Name:</label>
					<input class="w3-input w3-border" type="text" name="Name" required></br>
					
					<label>Description:</label>
					<textarea class="w3-input w3-border" rows="5" cols="50" maxlength="2000" placeholder="Type here" name="Description" required></textarea>
							</br>

					Users can be assigned by editing this phase once it has been created.</p>
					
					
					<button class="w3-button w3-green" type="submit" name="PhaseSubmit">Save</button>
				</form>
				</div>
			</div>
		</div>
			
	</body>
	<footer>
	</footer>
</html>