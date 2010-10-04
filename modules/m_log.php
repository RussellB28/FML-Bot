<?php

Class Log
{

	public function LogToFile($data)
	{
	}

	public function LogToIRC($data)
	{
		IRCSock :: SendData("PRIVMSG ".Config :: $_server['LOG_CHAN']." :".$data);
	}
}

?>