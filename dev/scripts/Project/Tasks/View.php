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
		header("Location: ../../home.php");
    }
    $tid = $_GET['tid'];
    $sql = "SELECT * FROM Tasks WHERE Task_ID = '$tid'";
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset=utf-8 />
		<link href ="/style.css" rel="stylesheet">
        <script type="text/JavaScript">
			function confirm_delete(tid)
			{
				if (confirm("Once you delete this task, it cannot be recovered.  Are you absolutely sure?"))
				{
					window.location.href="/Project/Tasks/Delete.php?prid=<?php echo $_GET['prid']; ?>&t=" + tid + "&d=" 
											+ "<?php echo password_hash($_GET['tid'] . "delete" . $_GET['tid'], PASSWORD_BCRYPT); ?>";
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

		<!--Form-->
        <div class="w3-container" style="margin-top:10%">
			<div class="w3-container w3-display-middle" style="width:50%">
				<a href="/Project/View.php?proj=<?php echo $_GET['prid'] ?>"><button class="w3-button w3-green">Return to Project</button></a>
				<div class="w3-border w3-padding">
					<?php
						if($result = mysqli_query($conn, $sql))
						{
							$count = mysqli_num_rows($result);
							$task = mysqli_fetch_array($result);
							if($count == 1)
							{
								echo "<h1>Task Name: " . $task['Task_Name'] . "</h1>";
								echo "<p>Task ID#: " . $task['Task_ID'] . "</p>";

								$userSql = "SELECT * FROM Users WHERE User_ID = " . $task['User_ID_FK'];
								if($user = mysqli_query($conn, $userSql))
								{
									if(mysqli_num_rows($user) == 1)
									{
										$user = mysqli_fetch_array($user);
										echo "<p>Created by: " . $user['User_Firstname'] . " " . $user['User_Lastname'] . " (" . $user['User_Name'] . ")</p>";
									}
								}

								echo "<p>Estimated Hours to complete: " . $task['Task_EstimatedHours'] . "</p>";
								echo "<p>Estimated Cost: " . $task['Task_EstimatedCost'] . "</p>";
								echo "<p>Task Description: " . $task['Task_Description'] . "</p>";
							}
						}
					
						if($user['User_ID'] == $_SESSION['CURRENT_USER']->GetUserID() || $_SESSION['CURRENT_USER']->GetUserRole() == 1)
						{
							echo "<a href='/Project/Tasks/Edit.php?prid=" . $_GET['prid'] . "&tid=" . $_GET['tid'] . "'><button class='w3-button w3-green'>Edit Task</button></a>";
							echo " <button class='w3-button w3-red' onclick='confirm_delete(" . $_GET['tid'] . ")'>Delete Task</button>";
						}
					?>
				</div>
			</div>
        </div>
    </body>
</html>