<?php
    class User
    {
        private $firstname = "";
        private $lastname = "";
        private $username = "";
        private $email = "";

        public static function Login($username, $password)
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
                $sql = "SELECT * FROM Users WHERE User_ID ='$username' AND User_Password ='$password' ";
                $Result = mysqli_query($conn, $sql);
                
                if ($Result != NULL && password_verify($password, $Result['User_Password']))
                {
                    Session:SetUserID($Result['User_ID']);
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
