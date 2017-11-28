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
	$sql = "SELECT * FROM Projects WHERE Project_ID = '$proj'";
	if($result = mysqli_query($conn, $sql))
	{
		$count = mysqli_num_rows($result);
		$project = mysqli_fetch_array($result);
	}
?>
<html>
	<head>
		<meta charset=utf-8 />
		<link href ="Style.css" rel="stylesheet">
		<script type="text/JavaScript">
			function confirm_delete(pid)
			{
				if (confirm("Once you delete this project, it cannot be recovered.  Additionally, all associated phases and tasks will be deleted.  Are you absolutely sure?"))
				{
					window.location.href="/Project/Delete.php?p=" + pid + "&d=" 
											+ "<?php echo password_hash($_GET['proj'] . "delete" . $_GET['proj'], PASSWORD_BCRYPT); ?>";
				}
			}
		</script>
	</head>
	<body>
	

		<div class="nav_bar">
			
			<!--List of Projects, Phases, and Tasks displays here-->
			<?php
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
					if($_SESSION['CURRENT_USER']->GetUserRole() == 1)
					{
						echo "<li><a href='/Project/Phases/Create.php?prid=" . $project['Project_ID'] 
								. "'><button>Create Phase</button></a></li>";
						echo "</ul>";
					}
				}
			?>
		</div>
		
		<div class="display">
			<?php
				if ($count == 1)
				{
					echo "<h2>Project Name: " . $project['Project_Name'] . "</h2></br>";
					echo "<p>Project ID#: ". $project['Project_ID'] . "</p>";

					$clientSql = "SELECT * FROM Clients WHERE Client_ID = " . $project['Client_ID_FK'];
					if ($result = mysqli_query($conn, $clientSql))
					{
						if (mysqli_num_rows($result) == 1)
						{
							$client = mysqli_fetch_array($result);
							
							if($client['Client_Firstname'] != 'NA' && $client['Client_Lastname'] != 'NA' && $client['Client_CompanyName'] != 'NA'){
								echo "<p>Client: " . $client['Client_Firstname'] . " " . $client['Client_Lastname'] . "</p>";
								echo "<p>Client Company: " . $client['Client_CompanyName'] . "</p>";
							}
		
						}
					}

					echo "<p>Project Status: " . $project['Project_Status'] . "</p>";
					echo "<p>Start Date: " . $project['Project_StartDate'] . "</p>";
					echo "<p>Estimated Hours to complete: " . $project['Project_TotalHours'] . "</p>";
					echo "<p>Total Budget: " . $project['Project_EstimatedBudget'] . "</p>";
					echo "<p>Remaining Budget: " . $project['Project_RemainedBudget'] . "<p>";					
					echo "<p>Description: " . $project['Project_Description'] . "</p>";
				}
			?>
			<?php

				if($_SESSION['CURRENT_USER']->GetUserRole() == 1)
				{
					echo '<a href="/Project/Edit.php?proj='.$project["Project_ID"].'"><button>Edit Project</button></a>';
					echo '<button onclick="confirm_delete('.$project["Project_ID"].')">Delete Project</button>';
				}
			?>

			<a href="/home.php"><button>Return to Home</button></a>
		</div>
	</body>
	<footer>
	</footer>
</html>
