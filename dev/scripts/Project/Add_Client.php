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
			if(isset($_GET['ret']))
			{
				header("Location: ./Edit.php?proj=" . $_GET['ret']);
			}
			else
			{
				header("Location: Create.php");
			}
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		
			
		
	}
	mysqli_close($conn);
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta charset=utf-8 />
	</head>
	<body>
		
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . (isset($_GET['ret']) ? "?ret=" . $_GET['ret'] : ""); ?>" autocomplete="off">
			<div class="AddClient">
			
				Company Name: </br>
				<input type="text" placeholder="Company Name" name="Client_CompanyName" required></br>
				
				Contact Firstname: </br>
				<input type="text" placeholder="Firstname" name="Client_Firstname" required></br>
				
				Contact Lastname: </br>
				<input type="text"  placeholder="Lastname" name="Client_Lastname" required></br>
				
				Contact Email: </br>
				<input type="text"  placeholder="Email" name="Client_Email" required></br>
								
				Phone: </br>
				<input type="number" maxlength="10" placeholder="Phone Number" name="Client_Phone" required></br>
				
				Specialized Industry/Field: </br>
				<input type="text"  placeholder="Industry" name="Client_Industry" required></br>
				
				Street Address: </br>
				<input type="text"  placeholder="Street Address" name="Client_Street" required></br>
				
				City: </br>
				<input type="text" placeholder="City" name="Client_City" required></br>
				
				State: </br>
				<input type="text"  maxlength="2" placeholder="State" name="Client_State" required></br>
				
				Zipcode: </br>
				<input type="number" min="0" maxlength="5" placeholder="Zipcode" name="Client_Zipcode" required></br>
				
				Country: </br>
				<input type="text"  placeholder="Country" name="Client_Country" required></br>
				
				</br>
				<button type="submit" name="ClientSubmit">Submit</button>
				
			</div>
			
		</form>

	</body>
	<footer>
	</footer>
</html>