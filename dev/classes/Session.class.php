<?php
    class Session
    {
        public static function Start()
        {
            return session_start();
        }

        public static function UserLoggedIn($userid)
        {
            return ( $_SESSION['user_id'] == $userid );
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
