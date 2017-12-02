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
		echo "Unable to connect.  Error: " . mysqli_error($conn);
	}
	if(empty($_GET))
	{
		header("Location: ../../home.php");
    }

    $uid = mysqli_real_escape_string($conn, $_REQUEST['uid']);
    $phid = mysqli_real_escape_string($conn, $_REQUEST['phid']);
    $prid = mysqli_real_escape_string($conn, $_REQUEST['prid']);

    $command = $_REQUEST['com'];

    if($command == 1)
    {   
        $sql = "SELECT * FROM User_Assignments WHERE User_ID_FK = '$uid' AND Phase_ID_FK = '$phid' AND Project_ID_FK = '$prid'";
        if($result = mysqli_query($conn, $sql))
        {
            if(mysqli_num_rows($result) >= 1)
            {
                echo "This user is already assigned to this project.";
                exit;
            }
        }
        
        $sql = "INSERT INTO User_Assignments (Phase_ID_FK, User_ID_FK, Project_ID_FK) VALUES ('$phid', '$uid', '$prid')";
    }
    else
    {
        $sql = "SELECT * FROM User_Assignments WHERE User_ID_FK = '$uid'";
        if($Result = mysqli_query($conn, $sql))
        {
            $count = mysqli_num_rows($Result);
            if ($count == 1)
            {
                $sql = "DELETE FROM User_Assignments WHERE User_ID_FK = '$uid'";
            }
        }
    }

    mysqli_query($conn, $sql);
    mysqli_close($conn);
?>