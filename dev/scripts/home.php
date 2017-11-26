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
		<link href ="style.css" rel="stylesheet">
		<script type="text/JavaScript">
			function viewProject(projectid)
			{
				window.location.href="/Project/view.php?proj=" + projectid;
			}
		</script>
	</head>
	<body>
		<!--Title Bar-->
		<div class="w3-top w3-card">
			<div class="w3-bar w3-padding">
				<a class="w3-bar-item"><h1>Project Planner</h1></a>
				<div class="w3-right">
					<a class="w3-bar-item">Logged in as <?php echo $_SESSION['CURRENT_USER']->GetUsername();?></p>
					<a href="logout.php"><button class="w3-bar-item w3-button w3-red">Sign Out</button></a>
				</div>
			</div>
		</div>
		
		<!--Project Menu-->
		<div class="w3-container">
			<a href="Project/Create.php"><button>Create New Project</button></a>
			<?php
				echo "<table>";
				echo "<tr class='w3-light-grey'>
					  <thead>
						<th>Project ID</th>
						<th>Project Name</th>
						<th>Estimated Hours</th>
						<th>Remaining Budget</th>
					  </thead>
					</tr>";
					
				$sql = "SELECT * FROM PROJECTS";
				if($Result = mysqli_query($conn, $sql))
				{
					while ($row = mysqli_fetch_array($Result))
					{
						echo "<tr class='project_link' onclick='viewProject(" . $row['Project_ID'] . ")'>";
						echo "<td>" . $row['Project_ID'] . "</td>";
						echo "<td>" . $row['Project_Name'] . "</td>";
						echo "<td>" . $row['Project_TotalHours'] . "</td>";
						echo "<td>" . $row['Project_RemainedBudget'] . "</td>";
						echo "</tr>";
					}
				}
				echo "</table>";
			?>
		</div>	
	
	</body>
	<footer>
	</footer>
</html>

<?php mysqli_close($conn); ?>
