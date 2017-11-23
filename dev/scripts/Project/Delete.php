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
    
    $sql = "SELECT * FROM Projects WHERE Project_ID = '$p'";
    if($Result = mysqli_query($conn, $sql))
    {
        $count = mysqli_num_rows($Result);
        $proj = mysqli_fetch_array($Result);

        echo $p . "delete" . $p;
        if ($count == 1 && password_verify($p . "delete" . $p, $delkey))
        {
            $sql = "DELETE FROM PROJECTS WHERE Project_ID = '$p'";
            mysqli_query($conn, $sql);
            mysqli_close($conn);
        }
    }
    header("Location: ../home.php");
?>