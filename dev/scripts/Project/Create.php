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
		//print_r($Row2);
		/////
	
		
		$Client_ID_FK = $Row2['Client_ID'];
		$Project_Name = $_POST['Project_Name'];
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
		$Project_Description = $_POST['Project_Description'];
	
		echo $Client_ID_FK . "hot";
		if($Client_ID_FK == ''){
			$sql3 = "INSERT INTO Projects (Project_Name, Project_Description,
								Project_Status, Project_StartDate, Project_EstimatedBudget,
								Project_RemainedBudget, Project_TotalHours)
								VALUES
								('$Project_Name', '$Project_Description',
								'$Project_Status', '$Project_StartDate', '$Project_EstimatedBudget',
								'$Project_RemainedBudget', '$Project_TotalHours')";
}
		else{
			$sql3 = "INSERT INTO Projects (Client_ID_FK, Project_Name, Project_Description,
											Project_Status, Project_StartDate, Project_EstimatedBudget,
											Project_RemainedBudget, Project_TotalHours)
											VALUES
											('$Client_ID_FK', '$Project_Name', '$Project_Description',
											'$Project_Status', '$Project_StartDate', '$Project_EstimatedBudget',
											'$Project_RemainedBudget', '$Project_TotalHours')";
		
		}							 
		if (mysqli_query($conn, $sql3)) {
			header("Location: ../home.php");
		} else {
			echo "Error: " . $sql3 . "<br>" . mysqli_error($conn);
		}
	}
	mysqli_close($conn);
	
?>

<html>
	<head>
		<meta charset=utf-8 />
		<link href ="style.css" rel="stylesheet">
	</head>
	<body>
		
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
			<div class="CreateProject">
				<a href="Add_Client.php"><button>Add Client</button></a>
				<p>Project Name: <input type="text" placeholder="Project Name" name="Project_Name" required>
					<select name="Client_CompanyName">
						<option value="" selected="selected">Select Client</option>
							<?php 
								foreach($Client_CompanyName as $Client_CompanyName)
								{ 
							?>
									<option value="<?php echo $Client_CompanyName['Client_CompanyName'] ?>"><?php echo $Client_CompanyName['Client_CompanyName'] ?> </option>
							<?php
								}
							?>
					</select>
					<select name="Project_Status" required>
						<option value="" selected="selected">Project Status</option>	
							<option value="Requested">Requested</option>
							<option value="Approved">Approved</option>
							<option value="On Hold">On Hold</option>
							<option value="Rejected">Rejected</option>
							<option value="Dead">Dead</option>
							<option value="Completed">Completed</option>
					</select>
				</p>
				Estimated Budget: 
				<input type="number" min="0" placeholder="Enter Estimated Budget" name="Project_EstimatedBudget" required></br></br>
				
				Estimated Total Hours: 
				<input type="number" min="0" placeholder="Enter Estimated Total Hours" name="Project_TotalHours" required></br></br>
				
				Start Date: 
				<input type="date" placeholder="dd/mm/yyyy" name="Project_StartDate" required></br></br>
				
				Project Description: </br>
				<textarea rows="10" cols="50" maxlength="2000" placeholder="Type here" name="Project_Description"></textarea>
				</br>
				<button type="submit" name="ProjectSubmit">Submit</button>
				
			</div>
			
		</form>


	</body>
	<footer>
	</footer>
</html>