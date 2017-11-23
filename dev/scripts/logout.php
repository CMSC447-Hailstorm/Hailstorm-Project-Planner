<?php
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/classes/Session.class.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/classes/User.class.php");
    Session::Start();
    if(!Session::UserLoggedIn())
    {
        header("Location: /login.php");
    }
	$_SESSION['CURRENT_USER']::Logout();
?>