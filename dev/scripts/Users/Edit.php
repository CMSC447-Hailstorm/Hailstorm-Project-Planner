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

    if(isset($_GET['uid']) && !empty($_GET) && $_SESSION['CURRENT_USER']->getUserRole() == 1)
    {
        $sql = "SELECT * 
                FROM Users 
                WHERE User_ID = " . $_GET['uid'];
    }
    else
    {
        $sql = "SELECT * 
                FROM Users 
                WHERE User_ID = " . $_SESSION['CURRENT_USER']->getUserID();
    }

    if($result = mysqli_query($conn, $sql))
    {
        $count = mysqli_num_rows($result);
        $user = mysqli_fetch_array($result);
    }

    if(isset($_POST['UserSubmit']) && !empty($_POST))
    {
        $passConfirm = $_POST['ConfirmPassword'];
        if($_SESSION['CURRENT_USER']->getUserID() == $user['User_ID'] && password_verify($passConfirm, $user['User_Password']))
        {
            $firstName = $_POST['Firstname'];
            $lastName = $_POST['Lastname'];
            $username = $_POST['Username'];
            $birthDate = $_POST['Birthdate'];
            $street = $_POST['Street'];
            $city = $_POST['City'];
            $state = $_POST['State'];
            $zipCode = $_POST['Zipcode'];
            $email = $_POST['Email'];
            $phone = $_POST['Phone'];
            
            if(!empty($_POST['Password']))
            {
                $password = password_hash($_POST['Password'], PASSWORD_BCRYPT);
                $sql = "UPDATE Users 
                SET User_Firstname = '$firstName', User_Lastname = '$lastName', User_Name = '$username', 
                    User_Password = '$password', User_Birthdate = '$birthDate', User_Street = '$street', 
                    User_City = '$city', User_State = '$state', User_Zipcode = '$zipCode', User_Email = '$email', User_Phone = '$phone'
                WHERE User_ID = " . $user['User_ID'];
            }
            else
            {
                $sql = "UPDATE Users 
                SET User_Firstname = '$firstName', User_Lastname = '$lastName', User_Name = '$username', 
                    User_Birthdate = '$birthDate', User_Street = '$street', 
                    User_City = '$city', User_State = '$state', User_Zipcode = '$zipCode', User_Email = '$email', User_Phone = '$phone'
                WHERE User_ID = " . $user['User_ID'];
            }
        
            mysqli_query($conn, $sql);
        
            if($_SESSION['CURRENT_USER']->getUserID() == $user['User_ID'])
            {
                $_SESSION['CURRENT_USER']->Login($username, $passConfirm);
            }
            mysqli_close($conn);
            header("Location: ./View.php" . (isset($_GET['uid']) ? "?uid=" . $_GET['uid'] : ""));
        }
        else
        {
            echo "<script>alert('Your password was incorrect.  Please try again.');</script>";
        }
    }
    ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset=utf-8 />
        <link href ="/style.css" rel="stylesheet">
        <?php
            if(isset($_GET['uid']) && !empty($_GET) && $_SESSION['CURRENT_USER']->GetUserRole() == 1)
            {
                echo "<script type='text/JavaScript'>
                    function promote(uid)
                    {
                        if(confirm('Are you sure you want to grant manager access to this user?  Your other changes to this account will not be saved.'))
                        {
                            var handler = new XMLHttpRequest();
                            handler.onreadystatechange = function()
                            {
                                if(this.readyState == 4 && this.status == 200)
                                {
                                    if(String(this.responseText).length > 0)
                                    {
                                        alert(this.responseText);
                                    }
                                    else
                                    {
                                        location.reload();
                                    }
                                }
                            }
                            handler.open('GET', 'Change_Role.php?uid=" . $_GET['uid'] . "&com=1', false);
                            handler.send();
                        }
                    }
                    function demote(uid)
                    {
                        if(confirm('Are you sure you want to revoke manager access from this user?  Your other changes to this account will not be saved.'))
                        {
                            var handler = new XMLHttpRequest();
                            handler.onreadystatechange = function()
                            {
                                if(this.readyState == 4 && this.status == 200)
                                {
                                    if(String(this.responseText).length > 0)
                                    {
                                        alert(this.responseText);
                                    }
                                    else
                                    {
                                        location.reload();
                                    }
                                }
                            }
                            handler.open('GET', 'Change_Role.php?uid=" . $_GET['uid'] . "&com=0', false);
                            handler.send();
                        }
                    }
                </script>";
            }
        ?>
    </head>
    <body>
        <div class="User_Details">
            <h2>Edit User Account</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . (isset($_GET['uid']) ? "?uid=" . $_GET['uid'] : "")); ?>" autocomplete="off">
                <?php
                    if($count == 1)
                    {
                        if($_SESSION['CURRENT_USER']->getUserID() == $user['User_ID'])
                        {
                            echo "<p>User: <input type='text' name='Firstname' value='" . $user['User_Firstname'] . "' required />   <input type='text' name='Lastname' value='" . $user['User_Lastname'] . "' required /></p>";
                            echo "<p>Username: <input type='text' name='Username' value='" . $user["User_Name"] . "' required /></p>";
                        }
                        else
                        {
                            echo "<p>User: " . $user['User_Firstname'] . " " . $user['User_Lastname'] . "</p>";
                            echo "<p>Username: " . $user["User_Name"] . "</p>";
                        }

                        echo "<p>Role: ";
                        if ($user['User_Role'] == 0)
                        {
                            echo "Employee";
                            if($_SESSION['CURRENT_USER']->GetUserRole() == 1)
                            {
                                echo "   <button type='button' onclick='promote(" . $_GET['uid'] . ")'>Grant Manager Status</button>";
                            }
                        }
                        else
                        {
                            echo "Manager";
                            if($_SESSION['CURRENT_USER']->GetUserRole() == 1 && $_SESSION['CURRENT_USER']->GetUserID() != $user['User_ID'])
                            {
                                echo "   <button type='button' onclick='demote(" . $_GET['uid'] . ")'>Revoke Manager Status</button>";
                            }
                        }
                        echo "</p></br>";
                        
                        echo "<p>User ID#: " . $user['User_ID'] . "</p>";

                        if($_SESSION['CURRENT_USER']->getUserID() == $user['User_ID'])
                        {
                            echo "<p>Password: <input type='password' name='Password' placeholder='Change password...' /></p></br>";
                            echo "<p>Date of Birth: <input type='date' name='Birthdate' value='" . $user['User_Birthdate'] . "' required /></p>";
                            echo "<p>Address: <input type='text' name='Street' value='" . $user['User_Street'] . "' required />, <input type='text' name='City' value='" . $user['User_City'] . "' required />, <input type='text' maxlength='2' name='State' value='" . $user['User_State'] . "' required /> <input type='number' maxlength='5' name='Zipcode' value='" . $user['User_Zipcode'] . "' required /></p>";
                            echo "<p>Email Address: <input type='email' name='Email' value='" . $user['User_Email'] . "' required /></p>";
                            echo "<p>Phone Number: <input type='number' maxlength='10' name='Phone' value='" . $user['User_Phone'] . "' required /></p>";
                        }
                        else
                        {
                            echo "<p>Password: *********</p></br>";
                            echo "<p>Date of Birth: " . $user['User_Birthdate'] . "</p>";
                            echo "<p>Address: " . $user['User_Street'] . ", " . $user['User_City'] . ", " . $user['User_State'] . " " . $user['User_Zipcode'] . "</p>";
                            echo "<p>Email Address: " . $user['User_Email'] . "</p>";
                            echo "<p>Phone Number: " . $user['User_Phone'] . "</p>";
                        }
                    }
                ?>
                </br>
                <p>Current Password: <input type="password" name="ConfirmPassword" placeholder="Confirm password..." required /></p>
                <button type="submit" name="UserSubmit">Save</button>
                <button type="cancel" name="cancel">Cancel</button>
            </form>
        </div>
    </body>
</html>