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
        $sql = "SELECT * FROM Users WHERE User_ID = " . mysqli_real_escape_string($conn, $_GET['uid']);
    }
    else
    {
        $sql = "SELECT * FROM Users WHERE User_ID = " . $_SESSION['CURRENT_USER']->getUserID();
    }
    if($result = mysqli_query($conn, $sql))
    {
        $count = mysqli_num_rows($result);
        $user = mysqli_fetch_array($result);
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset=utf-8 />
        <link href ="/style.css" rel="stylesheet">
        <script type="text/JavaScript">
			function confirm_delete(uid)
			{
				if (confirm("Are you sure you want to delete this user?"))
				{
                    if(confirm("This will seriously delete all this user's information.  This cannot be undone.  Are you absolutely sure?"))
                    {
                        <?php
                            $sql = "SELECT * FROM Users WHERE User_Role = 1";
                            if($result = mysqli_query($conn, $sql))
                            {
                                if(mysqli_num_rows($result) == 1 && $user['User_ID'] == mysqli_fetch_array($result)['User_ID'])
                                {
                                    echo "alert('You cannot delete the only existing manager account!');";
                                }
                                else
                                {
                                    echo "window.location.href='/Users/Delete.php?uid=" . $user['User_ID'] . "&d=" . password_hash($user['User_ID'] . "delete" . $user['User_ID'], PASSWORD_BCRYPT) . "';";
                                }
                            }
                        ?>
                    }
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
        <div class="w3-container" style="top:80%">
			<div class="w3-container w3-display-topmiddle" style="width:50%;top:12%">
            <a href="/home.php"><button class="w3-button w3-green">Return to Home</button></a>
            <div class="w3-border w3-padding">
            <?php
                if($count == 1)
                {
                    echo "<h2>User: " . $user['User_Firstname'] . " " . $user['User_Lastname'] . "</h2>";
                    echo "<p>Username: " . $user["User_Name"] . "</p>";
                    echo "<p>Role: " . ($user['User_Role'] == 1 ? "Manager" : "Employee") . "</p></br>";
                    
                    echo "<p>User ID#: " . $user['User_ID'] . "</p>";
                    echo "<p>Password: *********</p></br>";

                    //Date of birth
                    echo "<p>Date of Birth: " . $user['User_Birthdate'] . "</p>";
                    //address
                    echo "<p>Address: " . $user['User_Street'] . ", " . $user['User_City'] . ", " . $user['User_State'] . " " . $user['User_Zipcode'] . "</p>";
                    //email
                    echo "<p>Email Address: " . $user['User_Email'] . "</p>";
                    //phone
                    echo "<p>Phone Number: " . $user['User_Phone'] . "</p>";
                }

                if($_SESSION['CURRENT_USER']->GetUserRole() == 1 || $user['User_ID'] == $_SESSION['CURRENT_USER']->GetUserID())
                {
                    echo "<a href='/Users/Edit.php" . (isset($_GET['uid']) ? "?uid=" . $_GET['uid'] : "") . "'><button class='w3-button w3-green'>Edit User Account</button></a> ";
                }
                if($user['User_ID'] == $_SESSION['CURRENT_USER']->GetUserID())
                {
                    echo "<button class='w3-button w3-red' onclick='confirm_delete(" . $user['User_ID'] . ")'>Delete User</button>";
                }
            ?>
            </div>
        
        </br>
        <div class="w3-border w3-padding">
         <?php
            if($_SESSION['CURRENT_USER']->GetUserRole() == 1)
            {
                echo "<h3>Manage Users</h3>";

                echo "<nav><ul class='w3-ul w3-hoverable'>";
                
                $sql = "SELECT * FROM Users";
                if($result = mysqli_query($conn, $sql))
                {
                    while($user = mysqli_fetch_array($result))
                    {
                        if ($user['User_ID'] != 0)
                        {
                            echo "<li style='cursor:pointer' onclick='window.location.href=\"/Users/View.php?uid=" . $user['User_ID'] . "\";'>" . $user['User_Firstname'] . " " . $user['User_Lastname'] . " (" . $user['User_Name'] . ")</a></li>";
                        }
                    }
                }
                echo "</ul></nav>";
                echo "<a href='/Users/Create.php" . (isset($_GET['uid']) ? "?ret=" . $_GET['uid'] : "") . "'><button class='w3-button w3-green'>Create New User</button></a>";
            }
        ?>
        </div>
    </div>
    </div>
    </body>
</html>