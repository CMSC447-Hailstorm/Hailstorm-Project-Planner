<?php
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/classes/Session.class.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/classes/User.class.php");
	Session::Start();
	if(!Session::UserLoggedIn())
	{
		header("Location: /login.php");
	}
	$conn = mysqli_connect($_SESSION["SERVER"], $_SESSION["DBUSER"], $_SESSION["DBPASS"], $_SESSION["DATABASE"]);
	if (!$conn)
	{
		die('Unable to connect.  Error: ' . mysqli_error($conn));
	}
?>
<html>
	<head>
		<meta charset=utf-8 />
		<link href ="Style.css" rel="stylesheet">
	</head>
	<body>
	

		<div class="nav_bar">
			
			<!--List of Projects, Phases, and Tasks displays here-->
			<ul id="project_list">
				<li>Project 1</li>
				<li>Project 2</li>
				<li>Project 3</li>
			</ul>
		
			<div class="nav_buttons">
			
				<button>Create Task</button>
				<button>Create Phase</button>
				
			</div>
		</div>
		
		<div class="display">
			<?php
				$proj = $_GET['proj'];
				$sql = "SELECT * FROM PROJECTS WHERE PROJECT_ID = '$proj'";
				if($Result = mysqli_query($conn, $sql))
				{
					$count = mysqli_num_rows($Result);
					$proj = mysqli_fetch_array($Result);

					if ($count == 1)
					{
						echo "<h1>Project Name: " . $proj['Project_Name'] . "</h1></br>";
						echo "<p>Project ID#: ". $proj['Project_ID'] . "</p>";
						echo "<p>Estimated Hours " . $proj['Project_TotalHours'] . "</p>";
						echo "<p>Total Budget: " . $proj['Project_EstimatedBudget'] . "</p>";
						echo "<p>Remaining Budget: " . $proj['Project_RemainedBudget'] . "<p>";					
						echo "<p>Description: " . $proj['Project_Description'] . "</p>";
					}
				}
			?>
			<!--<h1>Project Name: <a id="Project_Name">Project 1</a></h1></br>
			Project ID#: <a id="Project_ID">12345</a></p>
			Next Milestone: <a id="Next_Milestone"></a></p>
			Estimated Hours: <a id="Hours"></a></p>
			Total Budget: <a id="Total_Budget"></a></br>
			Remaining Budget: <a id="Remaining_Budget"></a><p>
			Description: <a id="Description"></a></p>-->
			
			<button>Edit Project</button>
		</div>
		
		
		<script>
			<!--Scripts for updating the display-->
		</script>
	</body>
	<footer>
	</footer>
</html>