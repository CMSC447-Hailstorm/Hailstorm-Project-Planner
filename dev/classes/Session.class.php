<?php
    class Session
    {
        public static function Start()
        {
            session_start();

            $config = fopen(dirname($_SERVER['DOCUMENT_ROOT']) . "/scripts/dbconfig.ini", "r");
            $_SESSION['SERVER'] = trim(explode(" ", fgets($config))[2]);
            $_SESSION['DBUSER'] = trim(explode(" ", fgets($config))[2]);
            $_SESSION['DBPASS'] = trim(explode(" ", fgets($config))[2]);
            $_SESSION['DATABASE'] = trim(explode(" ", fgets($config))[2]);
            fclose($config);
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
