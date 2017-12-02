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

    $command = $_REQUEST['com'];
    $uid = mysqli_real_escape_string($conn, $_REQUEST['uid']);
    
    if ($command == 1)
    {
        $sql = "UPDATE Users SET User_Role = 1 WHERE User_ID = '$uid'";
        mysqli_query($conn, $sql);
    }
    else
    {
        $sql = "UPDATE Users SET User_Role = 0 WHERE User_ID = '$uid'";
        mysqli_query($conn, $sql);
    }
    mysqli_close($conn);
?>