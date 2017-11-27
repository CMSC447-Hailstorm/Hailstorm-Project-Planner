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

		$sql = "UPDATE Projects SET Project_RemainedBudget = Project_RemainedBudget - '$budget' WHERE Project_ID = '$project'";
		mysqli_query($conn, $sql);

		header("Location: ../View.php?proj=" . $project);
	}
?>
<html>
	<head>
		<meta charset=utf-8 />
		<link href ="Style.css" rel="stylesheet">
	</head>
	<body>
	
		<h2>Create New Task</h2>
		
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] 
									. "?prid=" . $_GET['prid'] . "&phid=" . $_GET['phid']); ?>" autocomplete="off">
			Name: <input type="text" name="Name" required></br>
			Estimated Hours: <input type="number" name="Hours" required></br>
			Estimated Budget: <input type="number" name="Budget" required></br>
			Description: <input type="text" name="Description" required></br>
			
			<button type="submit" name="TaskSubmit">Save</button>
			<a href="<?php echo '../View.php?proj='.$_GET['prid'] .'&'. $_GET['phid']; ?>"><button name="cancel">Cancel</button>
		</form>
	
	</body>
	<footer>
	</footer>
</html>