<?php
    require_once(dirname($_SERVER['DOCUMENT_ROOT']) . "/classes/Session.class.php");

    class User
    {
        private $id = "";
        private $firstname = "";
        private $lastname = "";
        private $username = "";
        private $email = "";
        private $role = "";

        public function Login($userid, $password)
        {
            if ($userid != '' && $password != '')
            {
                $conn = mysqli_connect($_SESSION["SERVER"], $_SESSION["DBUSER"], $_SESSION["DBPASS"], $_SESSION["DATABASE"]);
                if (!$conn)
                {
                    die('Unable to connect.  Error: ' . mysqli_error($conn));
                }
                $userid = mysqli_real_escape_string($conn, $userid);
				$sql = "SELECT * FROM Users WHERE User_Name = '$userid' ";
                $Result = mysqli_query($conn, $sql);
				
				//checking if there is only one row with user_id ONLY
				$count = mysqli_num_rows($Result);
				
				$Row = mysqli_fetch_array($Result, MYSQLI_ASSOC);
				
                if ($count == 1 && password_verify($password, $Row['User_Password']))
                { 
                    Session::SetUserID($Row['User_ID']);
                    $this->id = $Row['User_ID'];
                    $this->firstname = $Row['User_Firstname'];
                    $this->lastname = $Row['User_Lastname'];
                    $this->username = $Row['User_Name'];
                    $this->email = $Row['User_Email'];
                    $this->role = $Row['User_Role'];
                    
                    mysqli_close($conn);
                    return true;
                }
                else 
                {
                    mysqli_close($conn);
                    return false;
                }
            }
        }
        
        public function getFirstName()
        {
            return $this->firstname;
        }

        public function getLastName()
        {
            return $this->lastname;
        }

        public function getUsername()
        {
            return $this->username;
        }

        public function getFullName()
        {
            return $this->firstname . " " . $this->lastname . " (" . $this->username . ")";
        }

        public function getUserID()
        {
            return $this->id;
        }

        public function getEmail()
        {
            return $this->email;
        }

        public function getUserRole()
        {
            return $this->role;
        }

        public static function Logout()
        {
            Session::CloseSession();
            header('Location: /login.php');
        }
    }
?>
