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
    
    $sql = "SELECT Client_CompanyName FROM Clients";
	$Result = mysqli_query($conn, $sql);
	$comps = [];
	
	if(mysqli_num_rows($Result) > 0){
		while($row = mysqli_fetch_assoc($Result)){
			$comps[] = $row;
		}
	}

	$proj = $_GET['proj'];
	$sql = "SELECT * FROM PROJECTS WHERE PROJECT_ID = '$proj'";
	
	if($result = mysqli_query($conn, $sql))
	{
		$count = mysqli_num_rows($result);
		$project = mysqli_fetch_array($result);
	}
    
    if(isset($_POST['ProjectSubmit']) && !empty($_POST))
    {
        $Company_Name = $_POST['Client_CompanyName'];
        $sql2 = "SELECT Client_ID FROM Clients where Client_CompanyName = '$Company_Name'";
		$Result2 = mysqli_query($conn, $sql2);
        $Row2 = mysqli_fetch_array($Result2, MYSQLI_ASSOC);
        $clientID = $Row2['Client_ID'];

        $projectName = $_POST['Name'];
		$projectStatus = $_POST['Project_Status'];
		
		$projectBudget = $_POST['Budget'];
		$remainingBudget = $project['Project_EstimatedBudget'] + ($projectBudget - $project['Project_EstimatedBudget']);

        $projectStartDate = $_POST['StartDate'];
        $projectDescription = $_POST['Description'];

        $sql = "UPDATE Projects SET Client_ID_FK = '$clientID', Project_Name = '$projectName', Project_Status = '$projectStatus', Project_EstimatedBudget = '$projectBudget', Project_RemainedBudget = '$remainingBudget', Project_StartDate = '$projectStartDate', Project_Description = '$projectDescription' WHERE Project_ID = '$proj'";
        mysqli_query($conn, $sql);
        mysqli_close($conn);
        header("Location: ./View.php?proj=" . $proj);
    }
?>
<html>
	<head>
		<meta charset=utf-8 />
		<link href ="Style.css" rel="stylesheet">
		<script type="text/JavaScript">
			function AddClient()
			{
				if(confirm("Your changes to the project will not be saved.  Continue?"))
				{
					window.location.href="/Project/Add_Client.php?ret=<?php echo $_GET['proj']; ?>";
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
							echo "<li>" . $phase['Phase_Name'];
							$taskSql = "SELECT * FROM Tasks WHERE Phase_ID_FK = " . $phase['Phase_ID'] 
							. " AND Project_ID_FK = '$proj'";
							if ($taskResult = mysqli_query($conn, $taskSql))
							{
								echo "<ul id='tasks_phase_" . $phase['Phase_ID'] . "'>";
								while($task = mysqli_fetch_array($taskResult))
								{
									echo "<li>" . $task['Task_Name'] . "</li>";
								}
								echo "<li></li>";
								echo "</ul>";
							}
							echo "</li>";
						}
					}
					echo "<li></li>";
					echo "</ul>";
				}
			?>
		</div>
		
		<div class="display">
            <h2>Edit Project</h2>
            <button onclick="AddClient()">Add Client</button>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] 
									. "?proj=" . $_GET['proj']); ?>" autocomplete="off">
                <?php
                    if ($count == 1)
                    {
                        echo "<p>Project Name: <input type='text' name='Name' value='" . $project['Project_Name'] . "' required /></p>";
                        echo "<p>Project ID#: ". $project['Project_ID'] . "</p>";

                        $clientSql = "SELECT * FROM Clients WHERE Client_ID = " . $project['Client_ID_FK'];
                        if ($result = mysqli_query($conn, $clientSql))
                        {
                            if (mysqli_num_rows($result) == 1)
                            {
                                $client = mysqli_fetch_array($result);
                                echo "<p>Client: " . $client['Client_Firstname'] . " " . $client['Client_Lastname'] . "</p>";
                            }
                        }

                        echo "<p>Client Company: ";
                        echo "<select name='Client_CompanyName' required>";
						foreach($comps as $comps)
						{ 
                            echo "<option value='" . $comps['Client_CompanyName'] . "'";
                            if ($client['Client_CompanyName'] == $comps['Client_CompanyName']) 
                            {
                                echo "selected";
                            }
                            echo ">" . $comps['Client_CompanyName'] . "</option>";
                        }
                        echo "</select></p>";

                        echo "<p>Project Status: ";
                        echo "<select name='Project_Status' required>";
                        foreach (array("Requested", "Approved", "On Hold", "Rejected", "Dead", "Completed") as $status)
                        {
                            echo "<option value='" . $status . "' ";
                            if($project['Project_Status'] == $status)
                            {
                                echo "selected";
                            }
                            echo ">" . $status . "</option>";
                        }
                        echo "</select></p>";

                        echo "<p>Start Date: <input type='date' name='StartDate' value='" . $project['Project_StartDate'] . "' required /></p>";
                        echo "<p>Estimated Hours to complete: " . $project['Project_TotalHours'] . "</p>";
                        echo "<p>Total Budget: <input type='number' name='Budget' value='" . $project['Project_EstimatedBudget'] . "' required /></p>";
                        echo "<p>Remaining Budget: " . $project['Project_RemainedBudget'] . "<p>";					
                        echo "<p>Description: <input type='text' name='Description' value='" . $project['Project_Description'] . "' required /></p>";
                    }
                ?>
                
			    <button type="submit" name="ProjectSubmit">Save</button>   
            </form>
			<a href="<?php echo '/Project/View.php?proj=' . $_GET['proj'] ?>"><button type="cancel" name="cancel">Cancel</button></a>
		</div>
	</body>
	<footer>
	</footer>
</html>
