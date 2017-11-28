<?php
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/classes/Session.class.php");
    Session::Start();
	if(Session::UserLoggedIn())
	{
		header("Location: /home.php");
	}
    if(isset($_POST['Submit']) && !empty($_POST))
    {
        $server = $_POST['Server'];
        $dbuser = $_POST['DBuser'];
        $dbpass = $_POST['DBpass'];
        $database = $_POST['Database'];
        
        $conn = mysqli_connect($server, $dbuser, $dbpass, $database);
        if(!$conn)
        {
            echo "<script type='text/JavaScript'>alert('Could not establish a connection to this database.');";
        }
        else
        {
            $privs = mysqli_fetch_array(mysqli_query($conn, "SHOW GRANTS FOR CURRENT_USER"));
            $privs = $privs[0];
            if(strpos($privs, "ALL") != FALSE || (strpos($privs, "SELECT") != FALSE && strpos($privs, "UPDATE") != FALSE && strpos($privs, "INSERT") != FALSE && strpos($privs, "DELETE") != FALSE && strpos($privs, "CREATE") != FALSE && strpos($privs, "ALTER") != FALSE))
            {
                $config = fopen(dirname($_SERVER['DOCUMENT_ROOT']) . "/scripts/dbconfig.ini", "w");
                fwrite($config, "\"SERVER\" = " . $server . PHP_EOL);
                fwrite($config, "\"DBUSER\" = " . $dbuser . PHP_EOL);
                fwrite($config, "\"DBPASS\" = " . $dbpass . PHP_EOL);
                fwrite($config, "\"DATABASE\" = " . $database . PHP_EOL);
                fclose($config);
                
                $sql = "CREATE TABLE IF NOT EXISTS Clients (
                    Client_ID int(100) NOT NULL,
                    Client_CompanyName varchar(100) NOT NULL,
                    Client_Firstname varchar(100) NOT NULL,
                    Client_Lastname varchar(100) NOT NULL,
                    Client_Industry varchar(100) NOT NULL,
                    Client_Email varchar(100) NOT NULL,
                    Client_Phone varchar(15) NOT NULL,
                    Client_Street varchar(100) NOT NULL,
                    Client_City varchar(100) NOT NULL,
                    Client_State varchar(2) NOT NULL,
                    Client_Zipcode int(5) UNSIGNED NOT NULL,
                    Client_Country varchar(100) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
                $result = mysqli_query($conn, $sql);

                $sql = "CREATE TABLE IF NOT EXISTS Phases (
                    Phase_ID int(100) NOT NULL,
                    User_ID_FK int(100) NOT NULL,
                    Project_ID_FK int(100) NOT NULL,
                    Phase_Name varchar(100) NOT NULL,
                    Phase_Description varchar(500) NOT NULL,
                    Phase_Status varchar(100) NOT NULL,
                    Phase_TotalHours int(100) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
                mysqli_query($conn, $sql);

                $sql = "CREATE TABLE IF NOT EXISTS Projects (
                    Project_ID int(100) NOT NULL,
                    Client_ID_FK int(100) DEFAULT NULL,
                    Project_Name varchar(100) NOT NULL,
                    Project_Description varchar(2000) NOT NULL,
                    Project_Status varchar(100) NOT NULL,
                    Project_StartDate date NOT NULL,
                    Project_EstimatedBudget float NOT NULL,
                    Project_RemainedBudget float NOT NULL,
                    Project_TotalHours int(100) NOT NULL,
                    Project_MaxHours int(100) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
                mysqli_query($conn, $sql);

                $sql = "CREATE TABLE IF NOT EXISTS Tasks (
                    Task_ID int(100) NOT NULL,
                    Project_ID_FK int(100) NOT NULL,
                    User_ID_FK int(100) NOT NULL,
                    Phase_ID_FK int(100) NOT NULL,
                    Task_Name varchar(100) NOT NULL,
                    Task_Description varchar(500) NOT NULL,
                    Task_EstimatedHours int(100) NOT NULL,
                    Task_EstimatedCost float NOT NULL,
                    Task_WorkedHours int(100) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
                mysqli_query($conn, $sql);

                $sql = "CREATE TABLE IF NOT EXISTS Users (
                    User_ID int(100) NOT NULL,
                    User_Name varchar(100) NOT NULL,
                    User_Password varchar(255) NOT NULL,
                    User_Firstname varchar(100) NOT NULL,
                    User_Lastname varchar(100) NOT NULL,
                    User_Role int(1) UNSIGNED NOT NULL,
                    User_Phone varchar(9) NOT NULL,
                    User_Email varchar(100) NOT NULL,
                    User_Street varchar(100) NOT NULL,
                    User_City varchar(100) NOT NULL,
                    User_State varchar(2) NOT NULL,
                    User_Zipcode int(5) UNSIGNED NOT NULL,
                    User_Birthdate date NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
                mysqli_query($conn, $sql);

                $sql = "CREATE TABLE IF NOT EXISTS User_Assignments (
                    Assignment_ID int(100) NOT NULL,
                    Project_ID_FK int(100) NOT NULL,
                    Phase_ID_FK int(100) NOT NULL,
                    User_ID_FK int(100) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
                mysqli_query($conn, $sql);

                $sql = "ALTER TABLE Clients
                            ADD PRIMARY KEY (Client_ID);";
                mysqli_query($conn, $sql);

                $sql = "ALTER TABLE Phases
                            ADD PRIMARY KEY (Phase_ID),
                            ADD KEY Project_ID (Project_ID_FK),
                            ADD KEY User_ID (User_ID_FK);";
                mysqli_query($conn, $sql);

                $sql = "ALTER TABLE Projects
                            ADD PRIMARY KEY (Project_ID);";
                mysqli_query($conn, $sql);

                $sql = "ALTER TABLE Tasks
                            ADD PRIMARY KEY (Task_ID),
                            ADD KEY Phase_ID (Phase_ID_FK),
                            ADD KEY Project_ID (Project_ID_FK),
                            ADD KEY User_ID (User_ID_FK);";
                mysqli_query($conn, $sql);

                $sql = "ALTER TABLE Users
                            ADD PRIMARY KEY (User_ID),
                            ADD UNIQUE KEY User_Name (User_Name);";
                mysqli_query($conn, $sql);

                $sql = "ALTER TABLE User_Assignments
                            ADD PRIMARY KEY (Assignment_ID),
                            ADD KEY Phase_ID (Phase_ID_FK),
                            ADD KEY User_ID (User_ID_FK),
                            ADD KEY Project_ID (Project_ID_FK);";
                mysqli_query($conn, $sql);

                $sql = "ALTER TABLE Clients
                            MODIFY Client_ID int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;";
                mysqli_query($conn, $sql);

                $sql = "ALTER TABLE Phases
                            MODIFY Phase_ID int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;";
                mysqli_query($conn, $sql);

                $sql = "ALTER TABLE Projects
                            MODIFY Project_ID int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;";
                mysqli_query($conn, $sql);

                $sql = "ALTER TABLE Tasks
                            MODIFY Task_ID int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;";
                mysqli_query($conn, $sql);

                $sql = "ALTER TABLE Users
                            MODIFY User_ID int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;";
                mysqli_query($conn, $sql);

                $sql = "ALTER TABLE User_Assignments
                            MODIFY Assignment_ID int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;";
                mysqli_query($conn, $sql);

                $sql = "ALTER TABLE Phases
                            ADD CONSTRAINT Project_ID FOREIGN KEY (Project_ID_FK) REFERENCES Projects (Project_ID),
                            ADD CONSTRAINT User_ID FOREIGN KEY (User_ID_FK) REFERENCES Users (User_ID);";
                mysqli_query($conn, $sql);

                $sql = "ALTER TABLE Tasks
                            ADD CONSTRAINT Phase_ID FOREIGN KEY (Phase_ID_FK) REFERENCES Phases (Phase_ID),
                            ADD CONSTRAINT Project_ID FOREIGN KEY (Project_ID_FK) REFERENCES Projects (Project_ID),
                            ADD CONSTRAINT User_ID FOREIGN KEY (User_ID_FK) REFERENCES Users (User_ID);";
                mysqli_query($conn, $sql);

                $sql = "ALTER TABLE User_Assignments
                            ADD CONSTRAINT Phase_ID FOREIGN KEY (Phase_ID_FK) REFERENCES Phases (Phase_ID),
                            ADD CONSTRAINT Project_ID FOREIGN KEY (Project_ID_FK) REFERENCES Projects (Project_ID),
                            ADD CONSTRAINT User_ID FOREIGN KEY (User_ID_FK) REFERENCES Users (User_ID);";
                mysqli_query($conn, $sql);

                $sql = "INSERT INTO Users (User_Name, User_Password, User_Role) VALUES ('User-Deleted', 'No-Access', 0)";
                mysqli_query($conn, $sql);
                $sql = "UPDATE Users SET User_ID = 0 WHERE User_ID = 1";
                mysqli_query($conn, $sql);
                $sql = "ALTER TABLE Users
                            MODIFY User_ID int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;";
                mysqli_query($conn, $sql);

                $sql = "COMMIT;";
                mysqli_query($conn, $sql);

                header("Location: ./FTS2.php");
            }
            else
            {
                echo "<script type='text/JavaScript'>alert('The specified user account does not have required permissions.  The account requires at least SELECT, UPDATE, INSERT, DELETE, CREATE, ALTER permissions.');";
            }
        }
    }
?>

<html>
    <head>
    </head>
    <body>
        <h1>First-time Setup: Establish Database Connection</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
            <p>Database Server Host: <input type="text" name="Server" required /></p>
            <p>Database Username: <input type="text" name="DBuser" required /></p>
            <p>Database Password: <input type="password" name="DBpass" required /></p>
            <p>Connect to Database Name: <input type="text" name="Database" required /></p>

            <button type="Submit" name="Submit">Submit</button>
        </form>
    </body>
</html>