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
    
    if (!isset($_GET))
    {
        header("Location: ../home.php");
    }
    $p = $_GET['p'];
    $delkey = $_GET['d'];

    if (password_verify($p . "delete" . $p, $delkey))
    {
        $sql = "SELECT * FROM Tasks WHERE Phase_ID_FK = '$p'";
        if($Result = mysqli_query($conn, $sql))
        {
            while ($task = mysqli_fetch_array($Result))
            {
                $delSql = "DELETE FROM Tasks WHERE Task_ID = " . $task['Task_ID'];
                mysqli_query($conn, $delSql);
            }
        }
    
        $sql = "SELECT * FROM Phases WHERE Phase_ID = '$p'";
        if($Result = mysqli_query($conn, $sql))
        {
            $count = mysqli_num_rows($Result);
            if ($count == 1)
            {
                $sql = "DELETE FROM Phases WHERE Phase_ID = '$p'";
                mysqli_query($conn, $sql);
            }
        }
    }

    mysqli_close($conn);
    header("Location: ../View.php?proj=" . $_GET['prid']);
?>