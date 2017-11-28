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
    $tid = $_GET['tid'];
    $sql = "SELECT * FROM Tasks WHERE Task_ID = '$tid'";
?>

<html>
    <head>
        <meta charset=utf-8 />
        <script type="text/JavaScript">
			function confirm_delete(tid)
			{
				if (confirm("Once you delete this task, it cannot be recovered.  Are you absolutely sure?"))
				{
					window.location.href="/Project/Tasks/Delete.php?prid=<?php echo $_GET['prid']; ?>&t=" + tid + "&d=" 
											+ "<?php echo password_hash($_GET['tid'] . "delete" . $_GET['tid'], PASSWORD_BCRYPT); ?>";
				}
			}
		</script>
    </head>
    <body>
        <div class="Task_Details">
            <?php
                if($result = mysqli_query($conn, $sql))
                {
                    $count = mysqli_num_rows($result);
                    $task = mysqli_fetch_array($result);
                    if($count == 1)
                    {
                        echo "<h1>Task Name: " . $task['Task_Name'] . "</h1>";
                        echo "<p>Task ID#: " . $task['Task_ID'] . "</p>";

                        $userSql = "SELECT * FROM Users WHERE User_ID = " . $task['User_ID_FK'];
                        if($user = mysqli_query($conn, $userSql))
                        {
                            if(mysqli_num_rows($user) == 1)
                            {
                                $user = mysqli_fetch_array($user);
                                echo "<p>Created by: " . $user['User_Firstname'] . " " . $user['User_Lastname'] . " (" . $user['User_Name'] . ")</p>";
                            }
                        }

                        echo "<p>Estimated Hours to complete: " . $task['Task_EstimatedHours'] . "</p>";
                        echo "<p>Estimated Cost: " . $task['Task_EstimatedCost'] . "</p>";
                        echo "<p>Task Description: " . $task['Task_Description'] . "</p>";
                    }
                }
            
                if($user['User_ID'] == $_SESSION['CURRENT_USER']->GetUserID())
                {
                    echo "<a href='/Project/Tasks/Edit.php?prid=" . $_GET['prid'] . "&tid=" . $_GET['tid'] . "'><button>Edit Task</button></a>";
                    echo " <button onclick='confirm_delete(" . $_GET['tid'] . ")'>Delete Task</button>";
                }
            ?>
            <a href="/Project/View.php?proj=<?php echo $_GET['prid'] ?>"><button>Return to Project</button></a>
        </div>
    </body>
</html>