<?php
    class Session
    {
        public static function Start()
        {
            session_start();

            // replace with your DB info here
            $_SESSION["SERVER"] = "studentdb-maria.gl.umbc.edu";
            $_SESSION["DBUSER"] = "chensel1";
            $_SESSION["DBPASS"] = "chensel1";
            $_SESSION["DATABASE"] = "chensel1";
            return;
        }

        public static function UserLoggedIn()
        {
            return (Session::GetUserID() != NULL);
        }   

        public static function GetUserID()
        {
            if (isset($_SESSION))
            {
                if (isset($_SESSION['user_id']))
                {
                    return $_SESSION['user_id'];
                }
            }
            else return NULL;
        }

        public static function SetUserID($userid)
        {
            if (isset($_SESSION))
            {
                $_SESSION['user_id'] = $userid;
            }
        }

        public static function CloseSession()
        {
            $_SESSION = NULL;
            return session_destroy();
        }
    }
?>
