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
		die('Unable to connect' . mysqli_connect_error());
	}
	$sql = "SELECT Client_CompanyName FROM Clients";
	$Result = mysqli_query($conn, $sql);
	$Client_CompanyName = [];
	
	if(mysqli_num_rows($Result) > 0){
		while($row = mysqli_fetch_assoc($Result)){
			$Client_CompanyName[] = $row;
		}
	}

	//print_r($Client_CompanyName);
	if(isset($_POST['ProjectSubmit']) && !empty($_POST)) 
	{
		$Company_Name = $_POST['Client_CompanyName'];
		//echo $Company_Name;
		if (!$conn)
		{
			die('Unable to connect' . mysqli_connect_error());
		}
		
		$sql2 = "SELECT Client_ID FROM Clients where Client_CompanyName = '$Company_Name'";
		$Result2 = mysqli_query($conn, $sql2);
		$Row2 = mysqli_fetch_array($Result2, MYSQLI_ASSOC);

		$Project_Name = mysqli_real_escape_string($conn, $_POST['Project_Name']);
		$Project_Status = $_POST['Project_Status'];			//Dead, On Hold, Completed, Requested, Approved, Rejected
		$Project_EstimatedBudget = $_POST['Project_EstimatedBudget'];
		$Project_RemainedBudget = $_POST['Project_EstimatedBudget'];
		$Project_TotalHours= $_POST['Project_TotalHours'];
		
		////
		$date = $_POST['Project_StartDate'];
		if($date != ''){
			$newDate = date("Y-m-d", strtotime($date));
		}
		////	echo $newDate;
		
		$Project_StartDate = $date;
		$Project_Description = mysqli_real_escape_string($conn, $_POST['Project_Description']);
		
		if($Company_Name == ''){
			$Client_ID_FK = -1;
		}
		else{
			$Client_ID_FK = $Row2['Client_ID'];
		}
			

		$sql3 = "INSERT INTO Projects (Client_ID_FK, Project_Name, Project_Description,
										Project_Status, Project_StartDate, Project_EstimatedBudget,
										Project_RemainedBudget)
										VALUES
										('$Client_ID_FK', '$Project_Name', '$Project_Description',
										'$Project_Status', '$Project_StartDate', '$Project_EstimatedBudget',
										'$Project_RemainedBudget')";
		

		if (mysqli_query($conn, $sql3)) {
			header("Location: ../home.php");
		} else {
			echo "Error: " . $sql3 . "<br>" . mysqli_error($conn);
		}
	}
	mysqli_close($conn);
	
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset=utf-8 />
		<link href ="/style.css" rel="stylesheet">
		<script type="text/JavaScript">
			function AddClient()
			{
				if(confirm("Your changes to the project will not be saved.  Continue?"))
				{
					window.location.href="/Project/Add_Client.php";
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
					<a class="w3-bar-item" href="/Users/View.php">Logged in as <?php echo $_SESSION['CURRENT_USER']->GetFirstName() . " " . $_SESSION['CURRENT_USER']->GetLastName() . " (" . $_SESSION['CURRENT_USER']->GetUsername() . ")";?></a>
					<a href="/logout.php"><button class="w3-bar-item w3-button w3-red">Sign Out</button></a>
				</div>
			</div>
		</div>
		
		<!--Form-->
		<div class="w3-container" style="margin-top:10%">
			<div class="w3-container w3-display-middle" style="width:50%">
			
				<div class="w3-border w3-padding">
					<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
					
							<label>Project Name:</label>
							<input class="w3-input w3-border" type="text" placeholder="Project Name" name="Project_Name" required></br>
							
							<label>Select Client</label>
							<select class="w3-select w3-border" name="Client_CompanyName">
								<option value="" disabled selected hidden>Select Client</option>
									<?php 
										foreach($Client_CompanyName as $Client_CompanyName)
										{ 
									?>
											<option value="<?php echo $Client_CompanyName['Client_CompanyName'] ?>"><?php echo $Client_CompanyName['Client_CompanyName'] ?> </option>
									<?php
										}
									?>
							</select></br>
							<button class="w3-button w3-green" onclick="AddClient()">Add Client</button></br></br>
							
							<label>Project Status</label>
							<select class="w3-select w3-border" name="Project_Status" required>
								<option value="" disabled selected hidden>Project Status</option>	
									<option value="Requested">Requested</option>
									<option value="Approved">Approved</option>
									<option value="On Hold">On Hold</option>
									<option value="Rejected">Rejected</option>
									<option value="Dead">Dead</option>
									<option value="Completed">Completed</option>
							</select></br></br>
							
							<label>Estimated Budget:</label> 
							<input class="w3-input w3-border" type="number" min="0" placeholder="Enter Estimated Budget" name="Project_EstimatedBudget" required></br>
							
							<label>Start Date: </label>
							<input class="w3-input w3-border" type="date" placeholder="dd/mm/yyyy" name="Project_StartDate" required></br>
							
							<label>Project Description:</label></br>
							<textarea class="w3-input w3-border" rows="5" cols="50" maxlength="2000" placeholder="Type here" name="Project_Description" required></textarea>
							</br>
							<button class="w3-button w3-green" type="submit" name="ProjectSubmit">Submit</button>
					
					</form>
				</div>
			</div>
		</div>

	</body>
	<footer>
	</footer>
</html>