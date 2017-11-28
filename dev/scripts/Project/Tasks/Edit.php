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

	$prid = $_GET['prid'];
	$tid = $_GET['tid'];
	$sql = "SELECT * FROM Tasks WHERE Task_ID = '$tid'";
	if($result = mysqli_query($conn, $sql))
	{
		$count = mysqli_num_rows($result);
		$task = mysqli_fetch_array($result);
	}

	if(isset($_POST['TaskSubmit']) && !empty($_POST))
	{
		$taskName = $_POST['Name'];
		$taskHours = $_POST['Hours'];
		$taskCost = $_POST['Cost'];
		$description = $_POST['Description'];

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
<html>
	<head>
		<meta charset=utf-8 />
		<link href ="Style.css" rel="stylesheet">
	</head>
	<body>
	
		<h2>Edit Task</h2>
		
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] 
									. "?prid=" . $_GET['prid'] . "&tid=" . $_GET['tid']); ?>" autocomplete="off">
			<?php
				if ($count == 1)
				{
					echo "<p>Task Name: <input type='text' name='Name' value='" . $task['Task_Name'] . "' required /></p>";
					echo "<p>Estimated Hours to complete: <input type='number' name='Hours' value='" . $task['Task_EstimatedHours'] . "' required /></p>";
					echo "<p>Estimated Cost: <input type='number' name='Cost' value='" . $task['Task_EstimatedCost'] . "' required /></p>";						
					echo "<p>Task Description: <input type='text' name='Description' value='" . $task['Task_Description'] . "' required/></p>";
				}
			?>
			
			<button type="submit" name="TaskSubmit">Save</button>
		</form>
		<a href="View.php?prid=<?php echo $_GET['prid'] . '&tid=' . $_GET['tid'] ?>"><button type="cancel" name="cancel">Cancel</button>
	</body>
	<footer>
	</footer>
</html>