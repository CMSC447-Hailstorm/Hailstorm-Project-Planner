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

<!DOCTYPE html>
<html>
	<head>
		<meta charset=utf-8 />
		<link href ="/style.css" rel="stylesheet">
		<script type="text/JavaScript">
			function viewProject(projectid)
			{
				window.location.href="/Project/view.php?proj=" + projectid;
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
		
		<!--Project Menu-->
		<div class="w3-container" style="margin-top:20%">
		<div class="w3-panel w3-display-middle w3-padding">
			<div>
				<?php 
					if($_SESSION['CURRENT_USER']->getUserRole() == 1){
						echo '<a href="Project/Create.php"><button class="w3-button w3-green">Create New Project</button></a>';
					}
				?>
			</div>
			<div>
			<?php
				echo "<table class='w3-table-all'>";
				echo "<tr>
						<th>Project ID</th>
						<th>Project Name</th>
						<th>Total Hours</th>
						<th>Estimated Budget</th>
						<th>Remaining Budget</th>
					</tr>";
					
				$sql = "SELECT * FROM Projects";
				if($Result = mysqli_query($conn, $sql))
				{
					while ($row = mysqli_fetch_array($Result))
					{
						if($_SESSION['CURRENT_USER']->GetUserRole() == 0)
						{
							$assignSql = "SELECT * FROM User_Assignments WHERE Project_ID_FK = " . $row['Project_ID'] . " AND User_ID_FK = " . $_SESSION['CURRENT_USER']->GetUserID();
							if($result = mysqli_query($conn, $assignSql))
							{
								if (mysqli_num_rows($result) > 0)
								{
									echo "<tr class='project_link' onclick='viewProject(" . $row['Project_ID'] . ")'>";
									echo "<td>" . $row['Project_ID'] . "</td>";
									echo "<td>" . $row['Project_Name'] . "</td>";
									echo "<td>" . $row['Project_TotalHours'] . "</td>";
									echo "<td>" . $row['Project_EstimatedBudget'] . "</td>";
									echo "<td>" . $row['Project_RemainedBudget'] . "</td>";
									echo "</tr>";
								}
							}
						}
						else
						{
							echo "<tr class='project_link' onclick='viewProject(" . $row['Project_ID'] . ")'>";
							echo "<td>" . $row['Project_ID'] . "</td>";
							echo "<td>" . $row['Project_Name'] . "</td>";
							echo "<td>" . $row['Project_TotalHours'] . "</td>";
							echo "<td>$" . $row['Project_EstimatedBudget'] . "</td>";
							echo "<td>$" . $row['Project_RemainedBudget'] . "</td>";
							echo "</tr>";
						}
					}
				}
				echo "</table>";
			?>
			</div>
		</div>	
	
	</body>
	<footer>
	</footer>
</html>

<?php mysqli_close($conn); ?>
