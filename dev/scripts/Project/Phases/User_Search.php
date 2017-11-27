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

    $search = $_REQUEST['u'];
    $name = explode(" ", $search);

    if(count($name) > 1)
    {
        $firstName = $name[0];
        $lastName = $name[1];
    }
    else
    {
        $firstName = "";
        $lastName = "";
    }

    $ret = Array();
    $sql = "SELECT * FROM Users WHERE User_Name = '$search' OR User_Firstname = '$firstName' OR User_Firstname = '$search' OR User_LastName = '$lastName' OR User_Lastname = '$search'";

    if($result = mysqli_query($conn, $sql))
    {
        while ($user = mysqli_fetch_array($result))
        {
            if ($user["User_ID"] != 0)
            {
                array_push($ret, $user['User_Firstname'] . " " . $user['User_Lastname'] . " (" . $user['User_Name'] . ")");
                array_push($ret, $user['User_ID']);
            }
        }
    }

    if (!(count($ret) >= 1))
    {
        array_push($ret, "No results found!");
    }
    echo json_encode($ret);
?>