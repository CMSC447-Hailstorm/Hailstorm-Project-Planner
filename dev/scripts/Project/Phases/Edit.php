<?php
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/classes/Session.class.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/classes/User.class.php");
	Session::Start();
	if(!Session::UserLoggedIn())
	{
		header("Location: /login.php");
	}
	if(!isset($_GET))
	{
		header("Location: ../../home.php");
	}
	$conn = mysqli_connect($_SESSION["SERVER"], $_SESSION["DBUSER"], $_SESSION["DBPASS"], $_SESSION["DATABASE"]);
	if (!$conn)
	{
		die('Unable to connect' . mysqli_connect_error());
	}

	$prid = $_GET['prid'];
	$phid = $_GET['phid'];
	$sql = "SELECT * FROM Phases WHERE Phase_ID = '$phid'";

	if(isset($_POST['PhaseSubmit']) && !empty($_POST))
	{
		$phaseName = $_POST['Name'];
		$description = $_POST['Description'];

		$sql = "UPDATE Phases SET Phase_Name = '$phaseName', Phase_Description = '$description' WHERE Phase_ID='$phid'";
		mysqli_query($conn, $sql);
		mysqli_close($conn);
		header("Location: ./View.php?prid=" . $prid . "&phid=" . $phid);
	}
?>
<html>
	<head>
		<meta charset=utf-8 />
		<link href ="Style.css" rel="stylesheet">
	</head>
	<body>
	
		<h2>Edit Phase</h2>
		
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] 
									. "?prid=" . $_GET['prid'] . "&phid=" . $_GET['phid']); ?>" autocomplete="off">
			<?php
				if($result = mysqli_query($conn, $sql))
				{
					$count = mysqli_num_rows($result);
					$phase = mysqli_fetch_array($result);
					if ($count == 1)
					{
						echo "<p>Name: <input type='text' name='Name' value='" . $phase['Phase_Name'] . "' required /></p>";
						echo "<p>Description: <input type='text' name='Description' value='" . $phase['Phase_Description'] . "' required /></p>";
					}
				}
			?>
			
			<button type="submit" name="PhaseSubmit">Save</button>
		</form>
		<a href="View.php?prid=<?php echo $_GET['prid'] . '&phid=' . $_GET['phid'] ?>"><button type="cancel" name="cancel">Cancel</button>
	</body>
	<footer>
	</footer>
</html>