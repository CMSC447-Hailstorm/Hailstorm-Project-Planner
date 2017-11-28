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

        $firstName = $_POST['Firstname'];
        $lastName = $_POST['Lastname'];
        $username = $_POST['Username'];
        $password = password_hash($_POST['Password'], PASSWORD_BCRYPT);
        $birthDate = $_POST['Birthdate'];
        $street = $_POST['Street'];
        $city = $_POST['City'];
        $state = $_POST['State'];
        $zipCode = $_POST['Zipcode'];
        $email = $_POST['Email'];
        $phone = $_POST['Phone'];

        $sql = "INSERT INTO Users (User_Firstname, User_Lastname, User_Name, User_Role, User_Password, 
                User_Birthdate, User_Street, User_City, User_State, User_Zipcode, User_Email, User_Phone)
                VALUES ('$firstName', '$lastName', '$username', 1, '$password', '$birthDate', '$street', '$city', '$state', '$zipCode', '$email', '$phone')";
        mysqli_query($conn, $sql);

        mysqli_close($conn);
        header("Location: ../login.php");
    }
?>

<html>
    <head>
        <meta charset=utf-8 />
        <link href ="/style.css" rel="stylesheet">
    </head>
    <body>
        <h1>First-time Setup: Create Initial Manager Account</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
            <p>User: <input type='text' name='Firstname' required />   <input type='text' name='Lastname' required /></p>
            <p>Username: <input type='text' name='Username' required /></p>
                
            <p>Password: <input type='password' name='Password' placeholder='Input password...' required /></p></br>

            <p>Date of Birth: <input type='date' name='Birthdate' required /></p>
            <p>Address: <input type='text' name='Street' required />, <input type='text' name='City' required />, <input type='text' name='State' required /> <input type='number' name='Zipcode' required /></p>
            <p>Email Address: <input type='email' name='Email' required /></p>
            <p>Phone Number: <input type='tel' name='Phone' required /></p>
            
            <button type="submit" name="Submit">Save</button>
        </form>
    </body>
</html>