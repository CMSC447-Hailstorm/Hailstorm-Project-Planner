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
            if ($username != '' && $password != '')
            {
                ///*
                $SERVER = 'localhost';
                $DBUSER = 'root';
                $DBPASS = 'z';
                $DATABASE = 'managemeng_planner';
                //*/
                $conn = mysqli_connect($SERVER, $DBUSER, $DBPASS, $DATABASE);
                if (!$conn)
                {
                    die('Unable to connect' . mysqli_error());
                }
                $sql = "SELECT * FROM Users WHERE User_ID ='$userid' AND User_Password ='$password' ";
                $Result = mysqli_query($conn, $sql);
                
                if ($Result != NULL && password_verify($password, $Result['User_Password']))
                {
                    Session:SetUserID($Result['User_ID']);
                    $firstname = $Result['First_name'];
                    $lastname = $Result['Last_name'];
                    $username = $Result['User_ID'];
                    $email = $Result['Email'];
                    $role = $Result['User_Role'];
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
