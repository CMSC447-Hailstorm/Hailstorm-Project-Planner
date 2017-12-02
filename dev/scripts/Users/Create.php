<?php
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/classes/Session.class.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/classes/User.class.php");
	Session::Start();
	$conn = mysqli_connect($_SESSION["SERVER"], $_SESSION["DBUSER"], $_SESSION["DBPASS"], $_SESSION["DATABASE"]);
	if (!$conn)
	{
		die('Unable to connect.  Error: ' . mysqli_error($conn));
    }

    if(isset($_POST['UserSubmit']) && !empty($_POST))
    {
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

        $sql = "INSERT INTO Users (User_Firstname, User_Lastname, User_Name, User_Password, 
                User_Birthdate, User_Street, User_City, User_State, User_Zipcode, User_Email, User_Phone)
                VALUES ('$firstName', '$lastName', '$username', '$password', '$birthDate', '$street', '$city', '$state', '$zipCode', '$email', '$phone')";
        mysqli_query($conn, $sql);
        
        if(Session::UserLoggedIn())
        {
            mysqli_close($conn);
            header("Location: ./View.php" . (isset($_GET['ret']) ? "?uid=" . $_GET['ret'] : ""));
        }
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
        <div class="User_Details">
            <h2>Create User Account</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . (isset($_GET['ret']) ? "?ret=" . $_GET['ret'] : "")); ?>" autocomplete="off">
                <p>User: <input type='text' name='Firstname' required />   <input type='text' name='Lastname' required /></p>
                <p>Username: <input type='text' name='Username' required /></p>
                <p>Role: <?php 
                    if(Session::UserLoggedIn())
                    {
                        echo "If this account is for a manager, please grant manager functions to this account after it has been created.";
                    }
                    else
                    {
                        echo "If you are a manager, please request to receive manager functions once your account is created.";
                    }
                ?></p></br>
                    
                <p>Password: <input type='password' name='Password' placeholder='Input password...' required /></p></br>

                <p>Date of Birth: <input type='date' name='Birthdate' required /></p>
                <p>Address: <input type='text' name='Street' required />, <input type='text' name='City' required />, <input type='text' maxlength = "2" name='State' required /> <input type='number' maxlength = "5" name='Zipcode' required /></p>
                <p>Email Address: <input type='email' name='Email' required /></p>
                <p>Phone Number: <input type='tel' maxlength="10" name='Phone' required /></p>

                <button type="submit" name="UserSubmit">Save</button>
                <button type="cancel" name="cancel">Cancel</button>
            </form>
        </div>
    </body>
</html>