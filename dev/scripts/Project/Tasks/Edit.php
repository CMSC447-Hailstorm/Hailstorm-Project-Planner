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

	$prid = mysqli_real_escape_string($conn, $_GET['prid']);
	$tid = mysqli_real_escape_string($conn, $_GET['tid']);
	$sql = "SELECT * FROM Tasks WHERE Task_ID = '$tid'";
	if($result = mysqli_query($conn, $sql))
	{
		$count = mysqli_num_rows($result);
		$task = mysqli_fetch_array($result);
	}

	if(isset($_POST['TaskSubmit']) && !empty($_POST))
	{
		$taskName = mysqli_real_escape_string($conn, $_POST['Name']);
		$taskHours = $_POST['Hours'];
		$taskCost = $_POST['Cost'];
		$description = mysqli_real_escape_string($conn, $_POST['Description']);

		$budgetUpdate = $taskCost - $task['Task_EstimatedCost'];
		$hoursUpdate = $taskHours - $task['Task_EstimatedHours'];
		
		$sql = "UPDATE Projects SET Project_TotalHours = Project_TotalHours + '$hoursUpdate', Project_RemainedBudget = Project_RemainedBudget - '$budgetUpdate' WHERE Project_ID = '$prid'";
		mysqli_query($conn, $sql);

		$sql = "UPDATE Tasks SET Task_Name = '$taskName', Task_Description = '$description', Task_EstimatedHours = '$taskHours', Task_EstimatedCost = '$taskCost' WHERE Task_ID='$tid'";
		mysqli_query($conn, $sql);

		mysqli_close($conn);
		header("Location: ./View.php?prid=" . $prid . "&tid=" . $tid);
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
				<a href="View.php?prid=<?php echo $_GET['prid'] . '&tid=' . $_GET['tid'] ?>"><button class="w3-button w3-red" type="cancel" name="cancel">Cancel</button></a>
				<div class="w3-border w3-padding">
					<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] 
												. "?prid=" . $_GET['prid'] . "&tid=" . $_GET['tid']); ?>" autocomplete="off">
												
						<?php
							if ($count == 1)
							{
								echo "<label>Task Name:</label> <input class='w3-input w3-border' type='text' name='Name' value='" . $task['Task_Name'] . "' required /></p>";
								echo "<label>Estimated Hours:</label> <input class='w3-input w3-border' type='number' min='0' name='Hours' value='" . $task['Task_EstimatedHours'] . "' required />";
								echo "<label>Estimated Cost $:</label> <input class='w3-input w3-border' type='number' min='0' name='Cost' value='" . $task['Task_EstimatedCost'] . "' required />";						
								echo "<label>Description:<label> <textarea class='w3-input w3-border' rows='5' cols='50' maxlength='2000' placeholder='Type here' name='Description' required>" . $task['Task_Description'] . "</textarea>";
							}
						?>
						
						<button class="w3-button w3-green" type="submit" name="TaskSubmit">Save</button>
					</form>
				</div>
			</div>
		</div>
		
	</body>
	<footer>
	</footer>
</html>