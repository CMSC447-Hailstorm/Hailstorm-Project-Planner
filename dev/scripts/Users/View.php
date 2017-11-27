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
        $sql = "SELECT * FROM Users WHERE User_ID = " . $_GET['uid'];
    }
    else
    {
        $sql = "SELECT * FROM Users WHERE User_ID = " . $_SESSION['CURRENT_USER']->getUserID();
    }
?>

<html>
    <head>
        <meta charset=utf-8 />
        <link href ="/style.css" rel="stylesheet">
        <!--<script type="text/JavaScript">
			function confirm_delete(uid)
			{
				if (confirm("Once you delete this task, it cannot be recovered.  Are you absolutely sure?"))
				{
					window.location.href="/Project/Tasks/Delete.php?prid=<?php //echo $_GET['prid']; ?>&t=" + tid + "&d=" 
											+ "<?php //echo password_hash($_GET['tid'] . "delete" . $_GET['tid'], PASSWORD_BCRYPT); ?>";
				}
			}
		</script>-->
    </head>
    <body>
        <div class="User_Details">
            <?php
                if($result = mysqli_query($conn, $sql))
                {
                    $count = mysqli_num_rows($result);
                    $user = mysqli_fetch_array($result);
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
                }
            ?>
            <a href="/Users/Edit.php<?php echo (isset($_GET['uid']) ? "?uid=" . $_GET['uid'] : ""); ?>"><button>Edit User Account</button></a>
            <!--<button onclick="confirm_delete(<?php //echo $_GET['tid']; ?>)">Delete User Task</button>-->
            <a href="/home.php"><button>Return to Home</button></a>
        </div>

         <?php
            if($_SESSION['CURRENT_USER']->GetUserRole() == 1)
            {
                echo "<div class='Manager_Functions'>";
                echo "<h3>Manage Users</h3>";

                echo "<nav><ul>";
                
                $sql = "SELECT * FROM Users";
                if($result = mysqli_query($conn, $sql))
                {
                    while($user = mysqli_fetch_array($result))
                    {
                        echo "<li><a href='/Users/View.php?uid=" . $user['User_ID'] . "'>" . $user['User_Firstname'] . " " . $user['User_Lastname'] . " (" . $user['User_Name'] . ")</a></li>";
                    }
                }
                echo "</ul></nav>";
                echo "<a href='/Users/Create.php" . (isset($_GET['uid']) ? "?ret=" . $_GET['uid'] : "") . "'><button>Create New User</button></a>";
                echo "</div>";
            }
        ?>
    </body>
</html>