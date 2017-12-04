<?php
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/classes/Session.class.php");
    Session::Start();
	if(Session::UserLoggedIn())
	{
		header("Location: /home.php");
	}
    if(isset($_POST['Submit']) && !empty($_POST))
    {
        $config = fopen(dirname($_SERVER['DOCUMENT_ROOT']) . "/scripts/dbconfig.ini", "r");
        $server = trim(explode(" ", fgets($config))[2]);
        $dbuser = trim(explode(" ", fgets($config))[2]);
        $dbpass = trim(explode(" ", fgets($config))[2]);
        $database = trim(explode(" ", fgets($config))[2]);
        fclose($config);

        $conn = mysqli_connect($server, $dbuser, $dbpass, $database);
        if(!$conn)
        {
            die('Unable to connect.  Error: ' . mysqli_error($conn));
        }

        $firstName = mysqli_real_escape_string($conn, $_POST['Firstname']);
        $lastName = mysqli_real_escape_string($conn, $_POST['Lastname']);
        $username = mysqli_real_escape_string($conn, $_POST['Username']);
        $password = password_hash(mysqli_real_escape_string($conn, $_POST['Password']), PASSWORD_BCRYPT);
        $birthDate = $_POST['Birthdate'];
        $street = mysqli_real_escape_string($conn, $_POST['Street']);
        $city = mysqli_real_escape_string($conn, $_POST['City']);
        $state = mysqli_real_escape_string($conn, $_POST['State']);
        $zipCode = $_POST['Zipcode'];
        $email = mysqli_real_escape_string($conn, $_POST['Email']);
        $phone = mysqli_real_escape_string($conn, $_POST['Phone']);

        $sql = "INSERT INTO Users (User_Firstname, User_Lastname, User_Name, User_Role, User_Password, 
                User_Birthdate, User_Street, User_City, User_State, User_Zipcode, User_Email, User_Phone)
                VALUES ('$firstName', '$lastName', '$username', 1, '$password', '$birthDate', '$street', '$city', '$state', '$zipCode', '$email', '$phone')";
        mysqli_query($conn, $sql);

        mysqli_close($conn);
        header("Location: ../login.php");
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset=utf-8 />
        <link href ="/style.css" rel="stylesheet">
    </head>
    <body>
        <!--Title Bar-->
		<div class="w3-top w3-card w3-white" style="height:10%">
			<div class="w3-bar w3-padding">
				<a class="w3-bar-item"><h1>Project Planner</h1></a>
			</div>
		</div>
        <div class="w3-container" style="margin-top:10%">
			<div class="w3-container w3-display-middle" style="width:75%">
        <h1>First-time Setup: Create Initial Manager Account</h1>
        <div class="w3-border w3-padding">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
            <p>User: <input type='text' name='Firstname' placeholder='First Name' required />   <input type='text' name='Lastname' placeholder='Last Name' required /></p>
            <p>Username: <input type='text' name='Username' placeholder='Username' required /></p>
                
            <p>Password: <input type='password' name='Password' placeholder='Input password...' required /></p></br>

            <p>Date of Birth: <input type='date' name='Birthdate' required /></p>
            <p>Address: <input type='text' name='Street' placeholder='Street' required />, <input type='text' name='City' placeholder='City' required />, <input type='text' name='State' required /> <input type='number' name='Zipcode' placeholder='Zipcode' required /></p>
            <p>Email Address: <input type='email' name='Email' placeholder='Email' required /></p>
            <p>Phone Number: <input type='tel' name='Phone' placeholder='Phone Number' required /></p>
            
            <button class="w3-button w3-green" type="submit" name="Submit">Save</button>
        </form>
        </div>
            </div>
        </div>
    </body>
</html>