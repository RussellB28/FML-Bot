<?php


Class IRC
{

	// * Initialize
	//	Connects our server to the main network using specific link and information details   
	//

	function Initialize()
	{
			IRCSock :: SendData("USER ".Config :: $_server['IDENT']." ".Config :: $_server['BIND']." * :".Config :: $_server['RNAME']."");
			IRC :: SetNick(Config :: $_server['NICK']);
			sleep(5);
			IRCSock :: SendData("PRIVMSG NickServ :IDENTIFY ".Config :: $_server['PASS']);
			IRCSock :: SendData("MODE ".Config :: $_server['NICK']." ".Config :: $_server['MODES']);

			IRC :: JoinChan(Config :: $_server['LOG_CHAN'], NULL);
			IRC :: JoinChan(Config :: $_server['MAIN_CHAN'], NULL);

			if (function_exists('pcntl_signal')) 
			{
			echo ">> [!] PNCTL Detected in PHP Installation - Enabling Handlers\n";
			if(class_exists('Log') && Config :: $_log['LOG'] == 1)
			{
				IRC :: LogToIRC("5[SYS] PNCTL Detected in PHP Installation - Enabling Handlers");
			}
    			//pcntl_signal(SIGTERM, array("Functions","SigHandle"));
    			//pcntl_signal(SIGKILL, array("Functions","SigHandle"));
   			//pcntl_signal(SIGINT, array("Functions","SigHandle"));
    			//pcntl_signal(SIGQUIT, array("Functions","SigHandle"));
			}
			else
			{
			echo ">> [!] PNCTL has not been Detected in PHP Installation - Disabling Handlers\n";
			if(class_exists('Log') && Config :: $_log['LOG'] == 1)
			{
				IRC :: LogToIRC("5[SYS] PNCTL has not been Detected in PHP Installation - Disabling Handlers");
			}
			}
	}

	
	// * Terminate
	//	Terminate our connection to the main server by delinking with a specific reason
	//
	//   Options:
	//		$reason = Reason for Disconnect (example: This server is being updated)

	function Terminate($reason)
	{
		IRCSock :: SendData('QUIT :'.$reason.'');
		//die();
	}


	// * JoinChan
	//	Joins the bot to a channel with or without a key
	//
	//   Options:
	//		$channel = Channel Name			(example: #test)
	//		$key = Channel Key (OPTIONAL)		(example: keyedopers)

	function JoinChan($channel,$key = false)
	{
		echo ">> [IRC] Joined Channel ".$channel."\n";
		if(class_exists('Log') && Config :: $_log['LOG'] == 1)
		{
			IRC :: LogToIRC("5[IRC] Joined Channel ".$channel);
		}
		IRCSock :: SendData("JOIN ".$channel." ".$key);
	}

	function PartChan($channel,$reason = false)
	{
		echo ">> [IRC] Left Channel ".$channel."\n";
		if(class_exists('Log') && Config :: $_log['LOG'] == 1)
		{
			IRC :: LogToIRC("5[IRC] Left Channel ".$channel);
		}
		IRCSock :: SendData("PART ".$channel." :".$reason);
	}

	
	// * PMsg
	//	Sends a Message to a specific user or channel (PRIVMSG)
	//
	//   Options:
	//		$reciever = Channel or Nickname		(example: TestUser / #test)
	//		$content = Message Data			(example: Test Message)

	function PMsg($reciever,$content)
	{
		echo ">> [IRC] Message Sent to ".$reciever.": ".$content."\n";
		IRCSock :: SendData("PRIVMSG ".$reciever." :".$content);
	}

	
	// * PMode
	//	Sets a mode on a specific user or channel (MODE)
	//
	//   Options:
	//		$reciever = Channel or Nickname		(example: TestUser / #test)
	//		$modes = Mode Data				(example: +v TestUser)	

	function PMode($sender,$reciever,$modes)
	{
		IRCSock :: SendData($sender." MODE ".$reciever." ".$modes);
	}


	// * PNotice
	//	Sends a Notice to a specific user or channel (NOTICE)
	//
	//   Options:
	//		$reciever = Channel or Nickname		(example: TestUser / #test)
	//		$content = Message Data			(example: Test Message)

	function PNotice($sender,$reciever,$content)
	{
		echo ">> [IRC] Notice Sent to ".$reciever.": ".$content."\n";
		IRCSock :: SendData("NOTICE ".$reciever." :".$content);
	}

	// * SetNick
	//	Set the Bots Nickname
	//
	//   Options:
	//		$nick = New Bot Nickname     		(example: NewNick22)

	function SetNick($nick)
	{
		echo ">> [IRC] Nickname Changed To ".$nick."\n";
		if(class_exists('Log') && Config :: $_log['LOG'] == 1)
		{
			IRC :: LogToIRC("5[IRC] Nickname Changed To ".$nick);
		}
		IRCSock :: SendData("NICK ".$nick);
	}

}

?>