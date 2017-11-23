<?php
    class Session
    {
        public static function Start()
        {
            session_start();
            //$_SESSION["SERVER"] = "studentdb-maria.gl.umbc.edu";
            //$_SESSION["DBUSER"] = "jlutz1";
            //$_SESSION["DBPASS"] = "jlutz1";
            //$_SESSION["DATABASE"] = "jlutz1";
            $_SESSION['SERVER'] = "localhost";
            $_SESSION['DBUSER'] = "jlutz1";
            $_SESSION['DBPASS'] = "gmbvOv6o4j79fLLO";
            $_SESSION['DATABASE'] = "projectplanner";
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

        public static function CLoseSession()
        {
            $_SESSION = NULL;
            return session_destroy();
        }
    }
?>
