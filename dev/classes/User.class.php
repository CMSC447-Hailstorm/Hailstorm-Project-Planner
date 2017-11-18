<?php
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/classes/Session.class.php");

    class User
    {
        private $firstname = "";
        private $lastname = "";
        private $username = "";
        private $email = "";
        private $role = "";

        public static function Login($userid, $password)
        {
            if ($userid != '' && $password != '')
            {
                ///*
                $SERVER = 'localhost';
                $DBUSER = 'root';
                $DBPASS = 'z';
                $DATABASE = 'management_planner';
                //*/
                $conn = mysqli_connect($SERVER, $DBUSER, $DBPASS, $DATABASE);
                if (!$conn)
                {
                    die('Unable to connect' . mysqli_error());
                }
                //$sql = "SELECT * FROM Users WHERE User_ID ='$userid' AND User_Password ='$password' ";
				$sql = "SELECT * FROM Users WHERE User_ID ='$userid' ";
                $Result = mysqli_query($conn, $sql);
				
				//checking if there is only one row with user_id ONLY
				$count = mysqli_num_rows($Result);
				
				$Row = mysqli_fetch_array($Result, MYSQLI_ASSOC);
				
                if ($count == 1 && password_verify($password, $Row['User_Password']))
                { 
                    Session::SetUserID($Row['User_ID']);
                    $firstname = $Row['User_Firstname'];
                    $lastname = $Row['User_Lastname'];
                    $username = $Row['User_ID'];
                    $email = $Row['User_Email'];
                    $role = $Row['User_Role'];
					
                    return true;
                }
                else return false;
            }
        }
        
        public static function getFirstName()
        {
            return $firstname;
        }

        public static function getLastName()
        {
            return $lastname;
        }

        public static function getUsername()
        {
            return $username;
        }

        public static function getEmail()
        {
            return $email;
        }

        public static function LogOut()
        {
            Session::CloseSession();
            header('Location: /login.php');
        }
    }
?>
