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
	if(empty($_GET))
	{
		header("Location: ../home.php");
	}
	$proj = $_GET['proj'];
	$sql = "SELECT * FROM Projects WHERE PROJECT_ID = '$proj'";
?>
<html>
	<head>
		<meta charset=utf-8 />
		<link href ="/style.css" rel="stylesheet">
		<script type="text/JavaScript">
			function confirm_delete(pid)
			{
				if (confirm("Once you delete this project, it cannot be recovered.  Are you absolutely sure?"))
				{
					window.location.href="/Project/Delete.php?p=" + pid + "&d=" 
											+ "<?php echo password_hash($_GET['proj'] . "delete" . $_GET['proj'], PASSWORD_BCRYPT); ?>";
				}
			}
		</script>
	</head>
	<body>
	
		<!--Title Bar-->
		<div class="w3-top w3-card w3-white" style="height:10%">
			<div class="w3-bar w3-padding">
				<a class="w3-bar-item"><h1>Project Planner</h1></a>
				<div class="w3-right">
					<a class="w3-bar-item">Logged in as <?php echo $_SESSION['CURRENT_USER']->GetUsername();?></a>
					<a href="/logout.php"><button class="w3-bar-item w3-button w3-red">Sign Out</button></a>
				</div>
			</div>
		</div>
		
		<div class="w3-container" style="margin-top:10%">
		<div class="w3-sidebar w3-bar-block w3-white w3-border-right" style="width:25%">
			<div class="w3-panel w3-padding">
			
			<!--List of Projects, Phases, and Tasks displays here-->
			<?php
				if($result = mysqli_query($conn, $sql))
				{
					$count = mysqli_num_rows($result);
					$project = mysqli_fetch_array($result);
					if ($count == 1)
					{
						echo "<h3>" . $project['Project_Name'] . "</h3>";
						echo "<ul id='project_list'>";
						$phaseSql = "SELECT * FROM Phases WHERE Project_ID_FK = '$proj'";
						if($result = mysqli_query($conn, $phaseSql))
						{
							$phaseCount = mysqli_num_rows($result);
							while ($phase = mysqli_fetch_array($result))
							{
								echo "<li><a href='/Project/Phases/View.php?prid=" . $project['Project_ID'] . "&phid=" . $phase['Phase_ID'] . "'>" . $phase['Phase_Name'];
								$taskSql = "SELECT * FROM Tasks WHERE Phase_ID_FK = " . $phase['Phase_ID'] 
								. " AND Project_ID_FK = '$proj'";
								if ($taskResult = mysqli_query($conn, $taskSql))
								{
									echo "<ul id='tasks_phase_" . $phase['Phase_ID'] . "'>";
									while($task = mysqli_fetch_array($taskResult))
									{
										echo "<li><a href='/Project/Tasks/View.php?prid=" . $project['Project_ID'] . "&tid=" . $task['Task_ID'] . "'>" . $task['Task_Name'] . "</a></li>";
									}
									echo "<li><a href='/Project/Tasks/Create.php?prid=" . $project['Project_ID'] 
											. "&phid=" . $phase['Phase_ID'] . "'><button>Create Task</button></a></li>";
									echo "</ul>";
								}
								echo "</a></li>";
							}
						}
						echo "<li><a href='/Project/Phases/Create.php?prid=" . $project['Project_ID'] 
								. "'><button>Create Phase</button></a></li>";
						echo "</ul>";
					}
				}
			?>
			
			</div>
		</div>
		
		<div class="w3-container" style="margin-left:25%">
			<?php
				if ($count == 1)
				{
					echo "<h1>Project Name: " . $project['Project_Name'] . "</h1></br>";
					echo "<p>Project ID#: ". $project['Project_ID'] . "</p>";
					echo "<p>Estimated Hours " . $project['Project_TotalHours'] . "</p>";
					echo "<p>Total Budget: " . $project['Project_EstimatedBudget'] . "</p>";
					echo "<p>Remaining Budget: " . $project['Project_RemainedBudget'] . "<p>";					
					echo "<p>Description: " . $project['Project_Description'] . "</p>";
				}
			?>
			
			<button>Edit Project</button>
			<button onclick="confirm_delete(<?php echo $project['Project_ID']; ?>)">Delete Project</button>
		</div>
		</div>
		
		<script>
			<!--Scripts for updating the display-->
		</script>
	</body>
	<footer>
	</footer>
</html>
