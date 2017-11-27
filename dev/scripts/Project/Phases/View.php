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
	if(empty($_GET))
	{
		header("Location: ../../home.php");
    }
    $phid = $_GET['phid'];
    $sql = "SELECT * FROM Phases WHERE Phase_ID = '$phid'";
?>

<html>
    <head>
        <meta charset=utf-8 />
        <script type="text/JavaScript">
			function ConfirmDelete(pid)
			{
				if (confirm("Once you delete this phase, it cannot be recovered.  Additionally, all associated tasks will be deleted.  Are you absolutely sure?"))
				{
					window.location.href="/Project/Phases/Delete.php?prid=<?php echo $_GET['prid']; ?>&p=" + pid + "&d=" 
											+ "<?php echo password_hash($_GET['phid'] . "delete" . $_GET['phid'], PASSWORD_BCRYPT); ?>";
				}
			}
		</script>
    </head>
    <body>
        <div class="Phase_Details">
            <?php
                if($result = mysqli_query($conn, $sql))
                {
                    $count = mysqli_num_rows($result);
                    $phase = mysqli_fetch_array($result);
                    if($count == 1)
                    {
                        echo "<h1>Phase Name: " . $phase['Phase_Name'] . "</h1>";
                        echo "<p>Phase ID#: " . $phase['Phase_ID'] . "</p>";

                        $userSql = "SELECT * FROM Users WHERE User_ID = " . $phase['User_ID_FK'];
                        if($user = mysqli_query($conn, $userSql))
                        {
                            if(mysqli_num_rows($user) == 1)
                            {
                                $user = mysqli_fetch_array($user);
                                echo "<p>Created by: " . $user['User_Firstname'] . " " . $user['User_Lastname'] . " (" . $user['User_Name'] . ")</p>";
                            }
                        }

                        echo "<p>Phase Description: " . $phase['Phase_Description'] . "</p>";

                        if($_SESSION['CURRENT_USER']->getUserRole() == 1){
                            echo "<a href='/Project/Phases/Edit.php?prid=" . $_GET["prid"] . "&phid=" . $_GET["phid"] . "'><button>Edit Phase</button></a>";
                            echo " <button onclick='ConfirmDelete(" . $_GET["phid"] . ")'>Delete Phase</button>";
                        }
                        
                        echo "<h3>Assigned Users: </h3>";
                        echo "<nav><ul>";
                        $sql = "SELECT * FROM User_Assignments WHERE Phase_ID_FK = " . $phase['Phase_ID'];
                        if($result = mysqli_query($conn, $sql))
                        {
                            while($assign = mysqli_fetch_array($result))
                            {
                                $userSql = "SELECT * FROM Users WHERE User_ID = " . $assign['User_ID_FK'];
                                if ($userResult = mysqli_query($conn, $userSql))
                                {
                                    $user = mysqli_fetch_array($userResult);
                                    echo "<li>" . $user['User_Firstname'] . " " . $user['User_Lastname'] . " (" . $user['User_Name'] . ")</li>";
                                }
                            }
                        }
                        echo "</ul></nav>";
                    }
                }
            ?>
            <a href="/Project/View.php?proj=<?php echo $_GET['prid'] ?>"><button>Return to Project</button></a>
        </div>
    </body>
</html>