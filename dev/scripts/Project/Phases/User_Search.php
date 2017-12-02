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

    $ret = Array();
    $sql = "SELECT * FROM Users";

    if($result = mysqli_query($conn, $sql))
    {
        while ($user = mysqli_fetch_array($result))
        {
            $searchText = strtolower($user['User_Firstname'] . " " . $user['User_Lastname'] . " (" . $user['User_Name'] . ")");
            if ($user["User_ID"] != 0 && strpos($searchText,$search) !== FALSE)
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