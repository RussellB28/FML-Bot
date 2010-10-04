<?php
// FML-Bot - MySQL Class
// Creator: Russell M Bradford
// File: m_sql.php

class MySQL
{

	public static $resource;


	function Connect($host,$user,$pass,$db)
	{
	self :: $resource = mysql_connect($host,$user,$pass,$db);
	echo mysql_error(MySQL :: $resource);
	self :: Database($db);
	echo mysql_error(MySQL :: $resource);
	}
	
	function Disconnect()
	{
	mysql_close(self :: $resource);
	}
	
	function Database($db)
	{
	mysql_select_db($db, self :: $resource);
	}
	
	function Ping()
	{
	mysql_ping(self :: $resource);
	}

	function GetOwnerByID($ownerid)
	{
		$result = mysql_query('SELECT * FROM `nicknames` WHERE ID='.$ownerid.'',MySQL :: $resource);
			echo mysql_error(MySQL :: $resource);
			if (!$result) {
    				echo "Error: Database Unreachable (GetOwnerByID Function)\r\n";
				return 0;
			}

			$num_rows = mysql_num_rows($result);

			if($num_rows == 0)
			{
    				echo "Error: Invalid Nickname ID (GetOwnerByID Function)\r\n";
				return 1;
			}

			while ($row = mysql_fetch_assoc($result))
			{
				return $row['NICK'];
			}
			mysql_free_result($result);

	}

	function IsUserIdentified($uid)
	{
		$result = mysql_query('SELECT * FROM `nicknames` WHERE ID='.$uid.'',MySQL :: $resource);
			echo mysql_error(MySQL :: $resource);
			if (!$result) {
    				echo "Error: Database Unreachable (IsUserIdentified Function)\r\n";
				return 0;
			}

			$num_rows = mysql_num_rows($result);

			if($num_rows == 0)
			{
    				echo "Error: Invalid Nickname ID (IsUserIdentified Function)\r\n";
				return 1;
			}

			while ($row = mysql_fetch_assoc($result))
			{
				if($row['IDENTIFIED'] == 1)
				{
				return 1;
				}
				else
				{
				return 0;
				}
			}
			mysql_free_result($result);

	}

	
}

?>