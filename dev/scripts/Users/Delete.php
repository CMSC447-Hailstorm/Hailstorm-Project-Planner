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
    $uid = mysqli_real_escape_string($conn, $_GET['uid']);
    $delkey = $_GET['d'];

    if (password_verify($uid . "delete" . $uid, $delkey))
    {

        $sql = "SELECT * FROM User_Assignments WHERE User_ID_FK = '$uid'";
        if($Result = mysqli_query($conn, $sql))
        {
            while ($assign = mysqli_fetch_array($Result))
            {
                $sql = "DELETE FROM User_Assignments WHERE Assignment_ID = " . $assign['Assignment_ID'];
                mysqli_query($conn, $sql);
            }
        }
        
        $sql = "SELECT * FROM Phases WHERE User_ID_FK = '$uid'";
        if($Result = mysqli_query($conn, $sql))
        {
            while ($phase = mysqli_fetch_array($Result))
            {
                $sql = "UPDATE Phases SET User_ID_FK = 0 WHERE Phase_ID = " . $phase['Phase_ID'];
                mysqli_query($conn, $sql);
            }
        }
        
        $sql = "SELECT * FROM Tasks WHERE User_ID_FK = '$uid'";
        if($Result = mysqli_query($conn, $sql))
        {
            while ($task = mysqli_fetch_array($Result))
            {
                $sql = "UPDATE Tasks SET User_ID_FK = 0 WHERE Task_ID = " . $task['Task_ID'];
                mysqli_query($conn, $sql);
            }
        }

        $sql = "SELECT * FROM Users WHERE User_ID = '$uid'";
        if($Result = mysqli_query($conn, $sql))
        {
            $count = mysqli_num_rows($Result);
            if ($count == 1)
            {
                $sql = "DELETE FROM Users WHERE User_ID = '$uid'";
                mysqli_query($conn, $sql);
            }
        }
    }

    mysqli_close($conn);
    if ($_SESSION['CURRENT_USER']->GetUserID() == $uid)
    {
        $_SESSION['CURRENT_USER']->Logout();
    }
    header("Location: ./View.php");
?>