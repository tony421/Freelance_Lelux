<?php
	session_start();

	class Session
	{
		public static function destroy()
		{
			session_destroy();
		}
		
		public static function userExists()
		{
			return isset($_SESSION['user']);
		}
		
		public static function setUser($therapist)
		{
			$_SESSION['user'] = serialize($therapist);
		}
		
		public static function getUser()
		{
			if(Session::userExists())
			{
				return unserialize($_SESSION['user']);
			}
			else 
			{
				throw new Exception("Session['user'] is not found.");
			}
		}
	}
?>