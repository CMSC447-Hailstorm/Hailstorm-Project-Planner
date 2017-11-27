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
	
		<div class="title_bar">
			<img src="">
			<h1>Project Planer</h1>
			<p>Logged in as <a href="/Users/View.php"><?php echo $_SESSION['CURRENT_USER']->GetFullName();?></a></p>
			<a href="logout.php"><button>Sign Out</button></a>
		</div>
		<div class="project_menu">
			<?php 
				$Role = $_SESSION['CURRENT_USER']->getUserRole();
				if($Role == 1){
					echo '<a href="Project/Create.php"><button>Create New Project</button></a>';
				}
			?>
			<?php
				echo "<table>";
				echo "<tr>
						<th>Project ID</th>
						<th>Project Name</th>
						<th>Estimated Hours</th>
						<th>Remaining Budget</th>
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
