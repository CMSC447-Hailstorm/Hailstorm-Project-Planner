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
	if(isset($_POST['ClientSubmit']) && !empty($_POST)) 
	{
		$Client_CompanyName = $_POST['Client_CompanyName'];
		$Client_Firstname = $_POST['Client_Firstname'];
		$Client_Lastname = $_POST['Client_Lastname'];
		$Client_Industry = $_POST['Client_Industry'];
		$Client_Email = $_POST['Client_Email'];
		$Client_Phone = $_POST['Client_Phone'];
		$Client_Street = $_POST['Client_Street'];
		$Client_City = $_POST['Client_City'];
		$Client_State = $_POST['Client_State'];
		$Client_Zipcode = $_POST['Client_Zipcode'];
		$Client_Country = $_POST['Client_Country'];
		
		
		$sql = "INSERT INTO Clients(Client_CompanyName, Client_Firstname, Client_Lastname, 
									Client_Industry, Client_Email, Client_Phone, 
									Client_Street, Client_City, Client_State, 
									Client_Zipcode, Client_Country) 
									VALUES 
									('$Client_CompanyName', '$Client_Firstname', '$Client_Lastname', 
									'$Client_Industry', '$Client_Email', '$Client_Phone', 
									'$Client_Street', '$Client_City', '$Client_State', 
									'$Client_Zipcode', '$Client_Country') ";
									
		if (mysqli_query($conn, $sql)) {				
			header("Location: Create.php");
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		
			
		
	}
	mysqli_close($conn);
?>

<html>
	<head>
		<meta charset=utf-8 />
		<link href ="../style.css" rel="stylesheet">
	</head>
	<body>
	
		<!--Title Bar-->
		<div class="w3-top w3-card">
			<div class="w3-bar w3-padding">
				<a class="w3-bar-item" ><h1>Project Planner</h1></a>
			</div>
		</div>	
		
		<div class="w3-container">
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
			<div class="w3-panel w3-display-middle w3-border w3-padding AddClient">
			
				<label>Company Name:</label>
				<input class="w3-input w3-border" type="text" placeholder="Company Name" name="Client_CompanyName" required></br>
				
				<label>Contact Firstname: </label>
				<input class="w3-input w3-border" type="text" placeholder="Firstname" name="Client_Firstname" required></br>
				
				<label>Contact Lastname: </label>
				<input class="w3-input w3-border" type="text"  placeholder="Lastname" name="Client_Lastname" required></br>
				
				<label>Contact Email: </label>
				<input class="w3-input w3-border" type="text"  placeholder="Email" name="Client_Email" required></br>
								
				<label>Phone: </label>
				<input class="w3-input w3-border" type="number" min="0" placeholder="Phone Number" name="Client_Phone" required></br>
				
				<label>Specialized Industry/Field: </label>
				<input class="w3-input w3-border" type="text"  placeholder="Industry" name="Client_Industry" required></br>
				
				<label>Street Address: </label>
				<input class="w3-input w3-border" type="text"  placeholder="Street Address" name="Client_Street" required></br>
				
				<label>City: </label>
				<input class="w3-input w3-border" type="text" placeholder="City" name="Client_City" required></br>
				
				<label>State: </label>
				<input class="w3-input w3-border" type="text"  maxlength="2" placeholder="State" name="Client_State" required></br>
				
				<label>Zipcode: </label>
				<input class="w3-input w3-border" type="number" min="0" max="99999" placeholder="Zipcode" name="Client_Zipcode" required></br>
				
				<label>Country: </label>
				<input class="w3-input w3-border" type="text"  placeholder="Country" name="Client_Country" required></br>
				
				</br>
				<button class="w3-button w3-green" type="submit" name="ClientSubmit">Submit</button>
				
			</div>
			
		</form>
		</div>

	</body>
	<footer>
	</footer>
</html>