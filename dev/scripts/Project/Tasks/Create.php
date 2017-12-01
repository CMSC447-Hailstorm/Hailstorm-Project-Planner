<?php
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/classes/Session.class.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/classes/User.class.php");
	Session::Start();
	if(!Session::UserLoggedIn())
	{
		header("Location: /login.php");
	}
	if(empty($_GET))
	{
		header("Location: ../../home.php");
	}
	$conn = mysqli_connect($_SESSION["SERVER"], $_SESSION["DBUSER"], $_SESSION["DBPASS"], $_SESSION["DATABASE"]);
	if (!$conn)
	{
		die('Unable to connect' . mysqli_connect_error());
	}

	if(isset($_POST['TaskSubmit']) && !empty($_POST))
	{
		$taskName = $_POST['Name'];
		$hours = $_POST['Hours'];
		$budget = $_POST['Budget'];
		$description = $_POST['Description'];
		$creator = $_SESSION['CURRENT_USER']->getUserID();
		$project = $_GET['prid'];
		$phase = $_GET['phid'];

		$sql = "INSERT INTO Tasks (Project_ID_FK, Phase_ID_FK, User_ID_FK, 
									Task_Name, Task_Description, Task_EstimatedHours, 
									Task_EstimatedCost) 
									VALUES
									('$project', '$phase', '$creator', '$taskName', 
									'$description', '$hours', '$budget')";

		mysqli_query($conn, $sql);

		$sql = "UPDATE Projects SET Project_TotalHours = Project_TotalHours + '$hours', Project_RemainedBudget = Project_RemainedBudget - '$budget' WHERE Project_ID = '$project'";
		mysqli_query($conn, $sql);

		header("Location: ../View.php?proj=" . $project);
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
				<a class="w3-bar-item"><h1>Project Planner</h1></a>
				<div class="w3-right">
					<a class="w3-bar-item" href="/Users/View.php">Logged in as <?php echo $_SESSION['CURRENT_USER']->GetUsername();?></a>
					<a href="/logout.php"><button class="w3-bar-item w3-button w3-red">Sign Out</button></a>
				</div>
			</div>
		</div>
		
		<!--Form-->
		<div class="w3-container" style="margin-top:10%">
			<div class="w3-container w3-display-middle" style="width:50%">
				<a href="<?php echo '../View.php?proj='.$_GET['prid'] .'&'. $_GET['phid']; ?>"><button class="w3-button w3-red" name="cancel">Cancel</button></a>
				<div class="w3-border w3-padding">
				<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] 
											. "?prid=" . $_GET['prid'] . "&phid=" . $_GET['phid']); ?>" autocomplete="off">
											
					<label>Task Name:</label>
					<input class="w3-input w3-border" type="text" name="Name" required></br>
					
					<label>Estimated Hours:</label>
					<input class="w3-input w3-border" type="number" min="0" name="Hours" required></br>
					
					<label>Estimated Budget:</label>
					<input class="w3-input w3-border" type="number" min="0" name="Budget" required></br>
					
					<label>Description:</label>
					<input class="w3-input w3-border" type="text" name="Description" required></br>
					
					<button class="w3-button w3-green" type="submit" name="TaskSubmit">Save</button>
					
				</form>
			</div>
		</div>
	
	</body>
	<footer>
	</footer>
</html>