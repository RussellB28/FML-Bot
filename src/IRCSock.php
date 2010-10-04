<?php

class IRCSock
{

       public static $_ConnectionInfo = Array();
       public static $_Connections;

	function CreateSocket()
	{
		echo ">> Connecting to ".Config :: $_server['HOST'].":".Config :: $_server['PORT']."..... ";

		self :: $_ConnectionInfo[0]['Context'] = stream_context_create (array ());
		stream_context_set_option (self :: $_ConnectionInfo[0]['Context'], 'socket', 'bindto', Config :: $_server['BIND'].':0');

		if(Config :: $_opts['SSL'] == 1)
		{
			stream_context_set_option (self :: $_ConnectionInfo[0], array
			(
				'ssl' => array
				(
					'verify_peer'		=> true,
					'allow_self_signed'	=> true,
					'local_cert'		=> Config :: $_ssl['SSLCERT'],
					'passphrase'		=> Config :: $_ssl['SSLPASS']
				)
			));
		}
		//-----------------

		if(Config :: $_opts['SSL'] == 1)
		{
			self :: $_ConnectionInfo[0][ 'socket' ] = stream_socket_client ('ssl://' . Config :: $_server['HOST'] . ':' . Config :: $_server['PORT'], $ErrorNumber, $ErrorString, 2.0, STREAM_CLIENT_CONNECT, self :: $_ConnectionInfo[0]['Context']);
		}
		else
		{
			self :: $_ConnectionInfo[0][ 'socket' ] = stream_socket_client ('tcp://' . Config :: $_server['HOST'] . ':' . Config :: $_server['PORT'], $ErrorNumber, $ErrorString, 2.0, STREAM_CLIENT_CONNECT, self :: $_ConnectionInfo[0]['Context']);
		}

		
		if (self :: $_ConnectionInfo[0][ 'socket' ] !== false)
		{      
			echo "[OK]\n";
		 	stream_set_blocking (self :: $_ConnectionInfo[0][ 'socket' ], 0);
			IRC :: Initialize();
			//Error :: SetErrorHandler();
			//Config :: $_opts['EHANDLE'] = 1;
		} else
		{
			echo "[ERROR] \n>> Could not connect to server " . Config :: $_server['HOST'] . " on port " . Config :: $_server['PORT'] . " (".$ErrorString.")\n";
		}
	}

	function DestroySocket($reason = false)
	{
	if(self :: $_ConnectionInfo[0][ 'socket' ] == false || self :: $_ConnectionInfo[0][ 'socket' ] == null)
	{
		echo ">> Could not terminate connection. It doesnt exist!";
	}
	else
	{
		if(!isset($reason))
		{
			IRCHandle :: SendData('QUIT :FML Open Source Bot v1.0');
		}
		else
		{
			IRCHandle :: SendData('QUIT :'.$reason);
		}
	}
	}

	function Ping()
	{
		IRCSock :: SendData("PING ".Config :: $_server['HOST']);
		IRCSock :: SendData("PONG ".Config :: $_server['HOST']);
	}

	function SendData($Data)
	{
	if(self :: $_ConnectionInfo[0][ 'socket' ] == false || self :: $_ConnectionInfo[0][ 'socket' ] == null)
	{
	echo ">> Could not send data. The connection doesnt exist!\n";
	}
	else
	{
	fwrite (self :: $_ConnectionInfo[0][ 'socket' ], $Data . "\r\n");
	}
	}

	function GetIRCData()
	{
		if ( isset( self :: $_ConnectionInfo[0] ) )
		{


			if(self :: $_ConnectionInfo[0][ 'socket' ] === null || self :: $_ConnectionInfo[0][ 'socket' ] === false)
			{
				echo ">> Lost Connection!\n";
				unset(self :: $_ConnectionInfo[0]);
			}
			else
			{
				$Input = fread (self :: $_ConnectionInfo[0][ 'socket' ], 2048);
				if ( $Input == '' || $Input == "\n") return false; 
				if ($Input !== false)
				{
					$Parts = explode( "\n" , $Input );
					foreach($Parts as $Line)
					{
						IRCHandle :: ProcessCMD($Line);
					}
				}
			}
		}
		return 1;
	}

}

?>