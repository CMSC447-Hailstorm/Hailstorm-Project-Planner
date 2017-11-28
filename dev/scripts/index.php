<?php
    $config = fopen("dbconfig.ini", "r");
    $server = trim(explode(" ", fgets($config))[2]);
    $dbuser = trim(explode(" ", fgets($config))[2]);
    $dbpass = trim(explode(" ", fgets($config))[2]);
    $database = trim(explode(" ", fgets($config))[2]);
    fclose($config);

    $conn = mysqli_connect($server, $dbuser, $dbpass, $database);
    if(!$conn)
    {
        header("Location: ./FTS/FTS1.php");
    }
    else
    {
        $sql = "SELECT * FROM Users WHERE User_Role = 1";
        if($result = mysqli_query($conn, $sql))
        {
            if(mysqli_num_rows($result) >= 1)
            {
                header("Location: /login.php");
            }
            else
            {
                header("Location: ./FTS/FTS2.php");
            }
        } 
    }
?>
