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
	$proj = mysqli_real_escape_string($conn, $_GET['proj']);
	$sql = "SELECT * FROM Projects WHERE Project_ID = '$proj'";
	if($result = mysqli_query($conn, $sql))
	{
		$count = mysqli_num_rows($result);
		$project = mysqli_fetch_array($result);
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset=utf-8 />
		<link href ="/style.css" rel="stylesheet">
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
	
		<!--Title Bar-->
		<div class="w3-top w3-card w3-white" style="height:10%">
			<div class="w3-bar w3-padding">
				<a class="w3-bar-item" href="/home.php"><h1>Project Planner</h1></a>
				<div class="w3-right">
					<a class="w3-bar-item" href="/Users/View.php">Logged in as <?php echo $_SESSION['CURRENT_USER']->GetUsername();?></a>
					<a href="/logout.php"><button class="w3-bar-item w3-button w3-red">Sign Out</button></a>
				</div>
			</div>
		</div>

		<div class="w3-container" style="margin-top:10%">
			<div class="w3-sidebar w3-bar-block w3-white w3-border-right" style="width:25%; height:80%">
				<div class="w3-panel w3-padding">
				
					<!--List of Projects, Phases, and Tasks displays here-->
					<?php
						if ($count == 1)
						{
							echo "<h3 class='w3-border-bottom'>" . $project['Project_Name'] . "</h3>";
							echo "<ul class='w3-ul' id='project_list'>";
							$phaseSql = "SELECT * FROM Phases WHERE Project_ID_FK = '$proj'";
							if($result = mysqli_query($conn, $phaseSql))
							{
								$phaseCount = mysqli_num_rows($result);
								while ($phase = mysqli_fetch_array($result))
								{
									echo "<li><a href='/Project/Phases/View.php?prid=" . $project['Project_ID'] . "&phid=" . $phase['Phase_ID'] . "'><button class='w3-button w3-blue'>" . $phase['Phase_Name'] . "</button></a>";
									$taskSql = "SELECT * FROM Tasks WHERE Phase_ID_FK = " . $phase['Phase_ID'] . " AND Project_ID_FK = '$proj'";
									if ($taskResult = mysqli_query($conn, $taskSql))
									{
										echo "<ul class='w3-ul' id='tasks_phase_" . $phase['Phase_ID'] . "'>";
										while($task = mysqli_fetch_array($taskResult))
										{
											echo "<li><a href='/Project/Tasks/View.php?prid=" . $project['Project_ID'] . "&tid=" . $task['Task_ID'] . "'><button class='w3-button w3-light-blue'>" . $task['Task_Name'] . "</button></a></li>";
										}
										echo "<li><a href='/Project/Tasks/Create.php?prid=" . $project['Project_ID'] 
												. "&phid=" . $phase['Phase_ID'] . "'><button class='w3-button w3-green'>Create Task</button></a></li>";
										echo "</ul>";
									}
									echo "</li>";
								}
							}
							if($_SESSION['CURRENT_USER']->GetUserRole() == 1)
							{
								echo "<li><a href='/Project/Phases/Create.php?prid=" . $project['Project_ID'] 
										. "'><button class='w3-button w3-green'>Create Phase</button></a></li>";
								echo "</ul>";
							}
						}
					?>
				</div>
			</div>
			
			<div class="w3-container" style="margin-left:25%">
				<div class="w3-border w3-padding">
					<?php
						if ($count == 1)
						{
							echo "<h2 class='w3-border-bottom'>Project Name: " . $project['Project_Name'] . "</h2>";
							echo "<h4>Project ID#: ". $project['Project_ID'] . "</h4>";

							$clientSql = "SELECT * FROM Clients WHERE Client_ID = " . $project['Client_ID_FK'];
							if ($result = mysqli_query($conn, $clientSql))
							{
								if (mysqli_num_rows($result) == 1)
								{
									$client = mysqli_fetch_array($result);
									
									if($client['Client_Firstname'] != 'NA' && $client['Client_Lastname'] != 'NA' && $client['Client_CompanyName'] != 'NA'){
										echo "<h4>Client: " . $client['Client_Firstname'] . " " . $client['Client_Lastname'] . "</h4>";
										echo "<h4>Client Company: " . $client['Client_CompanyName'] . "</h4>";
									}
				
								}
							}

							echo "<h4>Project Status: " . $project['Project_Status'] . "</h4>";
							echo "<h4>Start Date: " . $project['Project_StartDate'] . "</h4>";
							echo "<h4>Estimated Hours to complete: " . $project['Project_TotalHours'] . "</h4>";
							echo "<h4>Total Budget: " . $project['Project_EstimatedBudget'] . "</h4>";
							echo "<h4>Remaining Budget: " . $project['Project_RemainedBudget'] . "</h4>";					
							echo "<h4>Description: " . $project['Project_Description'] . "</h4>";
						}
					?>
					<?php

						if($_SESSION['CURRENT_USER']->GetUserRole() == 1)
						{
							echo '<a href="/Project/Edit.php?proj='.$project["Project_ID"].'"><button class="w3-button w3-green">Edit Project</button></a>';
							echo '<button class="w3-margin w3-button w3-red" onclick="confirm_delete('.$project["Project_ID"].')">Delete Project</button>';
						}
					?>
				</div>
				<a href="/home.php"><button class="w3-button w3-green">Return to Home</button></a>
			</div>
		</div>
	</body>
	<footer>
	</footer>
</html>
