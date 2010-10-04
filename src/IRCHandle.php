<?php


class IRCHandle
{


	// * Initialize
	//	Description Here   
	//

	function Initialize()
	{
		// maybe will be used in the future
	}

	// * ProcessCMD
	//	Processes Raw Data from the IRC Server to Our Bot  
	//
	//   Options:
	//		$data = Raw IRC Data						(example: :nick!user@host PRIVMSG reciever :message)

	function ProcessCMD($data)
	{
		$Bits = explode( " " , $data );
		echo ">> [IRC] Recieved: ".$data."\n";


		// PRIVMSG Handler (Format: nick!user@host PRIVMSG reciever :message)
		if(isset($Bits[1]))
		{
			if($Bits[1] == "PRIVMSG")
			{
				IRCHandle :: OnPrivMsg($Bits[0],$Bits[2],implode( ' ' , array_slice( $Bits , 3 ) ));
			}
			// NOTICE Handler (Format: nick!user@host NOTICE reciever :message)
			elseif($Bits[1] == "NOTICE")
			{
				//IRCHandle :: OnNotice($Bits[0],$Bits[2],str_replace(":","",implode( ' ' , array_slice( $Bits , 3 ) )));
			}
		}
		elseif($Bits[0] == "PING")
		{
			IRCHandle :: OnPing($Bits[1]);
		}
		else
		{
			// Unhandled Stuff!
		}

	}

	// * OnPing
	//	Process Server Pings (Called from ProcessCMD Normally)
	//
	//   Options:
	//		$server = Server Address Sent in Requesting Ping		(example: server.bladebla.com)

	function OnPing($server)
	{
		IRCSock :: SendData("PONG ".$server);
	}

	function OnPrivMsg($who,$where,$what)
	{
		$guser = explode("!",trim($who));
		$bx = explode(" ", $what);

		// **********************
		// Server Commands
		// **********************
		if(trim($bx[0]) == ":!sping")
		{
			IRCHandle :: OnPing(Config :: $_server['HOST']);
			IRC :: PMsg(trim($where),'4[SERVER-CMD] Ping Sent to IRC Server');
			echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SPING (Server Ping)\n";
			IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SPING (Server Ping)");
			return 1;
		}
		if(trim($bx[0]) == ":!stest")
		{
			IRC :: PMsg(trim($where),'4[SERVER-CMD] Connection to '.Config :: $_server['NAME'].' is still established');
			echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": STEST (Server Test)\n";
			IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": STEST (Server Test)");
			return 1;
		}

		if(trim($bx[0]) == ":!info")
		{
			IRC :: PMsg(trim($where),'2[INFO] FML (Fuck My Life) Open Source Bot v1.0');
			IRC :: PMsg(trim($where),'2[INFO] Created by Russell M Bradford (06/08/2010)');
			IRC :: PMsg(trim($where),'2[INFO] Connected to '.Config :: $_server['NAME'].' ('.Config :: $_server['HOST'].')');
			echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": INFO (Bot Information)\n";
			IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": INFO (Bot Information)");
			return 1;
		}

		if(trim($bx[0]) == ":!uptime")
		{
			if(!isset($bx[1]) || isset($bx[2]))
			{
				IRC :: PMsg(trim($where),'2[SYNTAX] !uptime [SYSTEM | BOT]');
				echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": UPTIME (Uptime) [Invalid Syntax]\n";
				IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": UPTIME (Uptime) [Invalid Syntax]");
			}
			else
			{
				if(trim($bx[1]) == "SYSTEM")
				{
					IRC :: PMsg(trim($where),'2[UPTIME] System: '.shell_exec('uptime'));
					echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": UPTIME (Uptime) [Type: SYSTEM]\n";
					IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": UPTIME (Uptime) [Type: SYSTEM]");
				}
				else
				{
					IRC :: PMsg(trim($where),'2[UPTIME] FML: UNAVAILABLE');
					echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": UPTIME (Uptime) [Type: BOT]\n";
					IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": UPTIME (Uptime) [Type: BOT]");
				}
			}
			return 1;
		}

		if(trim($bx[0]) == ":!sjoin")
		{
			if(!isset($bx[2]) || isset($bx[3]))
			{
				IRC :: PMsg(trim($where),'2[SYNTAX] !sjoin [channel] [key (optional)]');
				echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SJOIN (Channel Join) [Invalid Syntax]\n";
				IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SJOIN (Channel Join) [Invalid Syntax]");
			}
			else
			{
				if(isset($bx[1]))
				{
				IRC :: JoinChan($bx[1],$bx[2]);
				IRC :: PMsg(trim($where),'4[SERVER-CMD] Joined '.$bx[1].' (Key: '.$bx[2].')');
				echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SJOIN (Channel Join) [Channel: ".$bx[1]." - Key: ".$bx[2]."]\n";
				IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SJOIN (Channel Join) [Channel: ".$bx[1]." - Key: ".$bx[2]."]");
				}
				else
				{
				IRC :: JoinChan($bx[1]);
				IRC :: PMsg(trim($where),'4[SERVER-CMD] Joined '.$bx[1]);
				echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SJOIN (Channel Join) [Channel: ".$bx[1]." - Key: N/A]\n";
				IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SJOIN (Channel Join) [Channel: ".$bx[1]." - Key: N/A]");
				}
			}
			return 1;
		}
		if(trim($bx[0]) == ":!spart")
		{
			if(!isset($bx[2]))
			{
				IRC :: PMsg(trim($where),'2[SYNTAX] !spart [channel] [reason (optional)]');
				echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SPART (Channel Part) [Invalid Syntax]\n";
				IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SPART (Channel Part) [Invalid Syntax]");
			}
			else
			{
				if(isset($bx[1]))
				{
				IRC :: PartChan($bx[1],implode( ' ' , array_slice( $bx , 2 ) ));
				IRC :: PMsg(trim($where),'4[SERVER-CMD] Left '.$bx[1].' (Reason: '.implode( ' ' , array_slice( $bx , 2 ) ).')');
				echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SPART (Channel Part) [Channel: ".$bx[1]." - Reason: ".implode( ' ' , array_slice( $bx , 2 ) )."]\n";
				IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SPART (Channel Part) [Channel: ".$bx[1]." - Reason: ".implode( ' ' , array_slice( $bx , 2 ) )."]");
				}
				else
				{
				IRC :: PartChan($bx[1]);
				IRC :: PMsg(trim($where),'4[SERVER-CMD] Left '.$bx[1]);
				echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SPART (Channel Part) [Channel: ".$bx[1]." - Reason: N/A]\n";
				IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SPART (Channel Part) [Channel: ".$bx[1]." - Reason: N/A]");
				}
			}
			return 1;
		}

		// **********************
		// Evaluation Commands
		// **********************

		if(trim($bx[0]) == ":!phpeval")
		{

			if(str_replace(":","",$guser[0]) == "Russell")
			{
			if(!isset($bx[1]))
			{
				IRC :: PMsg(trim($where),'2[SYNTAX] !phpeval [raw php code]');
				echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": PHPEVAL (PHP Evaluation) [Invalid Syntax]\n";
				IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": PHPEVAL (PHP Evaluation) [Invalid Syntax]");
			}
			else
			{
				Functions :: Evaluate(trim(implode( ' ' , array_slice( $bx , 1 ) )));
				IRC :: PMsg(trim($where),'4[EVAL-CMD] Executed Code');
				echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": PHPEVAL (PHP Evaluation) [Code: ".implode( ' ' , array_slice( $bx , 1 ) )."]\n";
				IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": PHPEVAL (PHP Evaluation) [Code: ".implode( ' ' , array_slice( $bx , 1 ) )."]");
			}
			}
			else
			{
				IRC :: PMsg(trim($where),'4[ERROR] Access Denied');
				echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": PHPEVAL (PHP Evaluation) [No Access]\n";
				IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": PHPEVAL (PHP Evaluation) [No Access]");
			}
			return 1;
		}

		if(trim($bx[0]) == ":!sqleval")
		{
			if(str_replace(":","",$guser[0]) == "Russell")
			{
			if(!isset($bx[1]))
			{
				IRC :: PMsg(trim($where),'2[SYNTAX] !sqleval [raw mysql query]');
				echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SQLEVAL (MySQL Evaluation) [Invalid Syntax]\n";
				IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SQLEVAL (MySQL Evaluation) [Invalid Syntax]");
			}
			else
			{
				if (class_exists('MySQL')) 
				{
				$eval = str_replace("!sqleval","",$what);
				MySQL :: Evaluate(trim(implode( ' ' , array_slice( $bx , 1 ) )));
				IRC :: PMsg(trim($where),'4[EVAL-CMD] Executed Code');
				echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SQLEVAL (MySQL Evaluation) [Code: ".trim(implode( ' ' , array_slice( $bx , 1 ) ))."]\n";
				IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SQLEVAL (MySQL Evaluation) [Code: ".trim(implode( ' ' , array_slice( $bx , 1 ) ))."]");
				}
				else
				{
				IRC :: PMsg(trim($where),'4[EVAL-CMD] This command has been disabled (The MySQL Module is not loaded)');
				echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SQLEVAL (MySQL Evaluation) [Module not Loaded]\n";
				IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SQLEVAL (MySQL Evaluation) [Module not Loaded]");
				}
			}
			}
			else
			{
				IRC :: PMsg(trim($where),'4[ERROR] Access Denied');
				echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SQLEVAL (MySQL Evaluation) [No Access]\n";
				IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": SQLEVAL (MySQL Evaluation) [No Access]");
			}
			return 1;
		}


		// **********************
		// Debug Commands
		// **********************
		if(trim($bx[0]) == ":!dwho")
		{
			IRC :: PMsg(trim($where),'4[DEBUG-CMD] Detected WHO Value: '.$who);
			echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": DWHO (Who Detection)\n";
				IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": DWHO (Who Detection)");
			return 1;
		}

		if(trim($bx[0]) == ":!dwhere")
		{
			IRC :: PMsg(trim($where),'4[DEBUG-CMD] Detected WHERE Value: '.$where);
			echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": DWHERE (Where Detection)\n";
				IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": DWHERE (Where Detection)");
			return 1;
		}

		if(trim($bx[0]) == ":!dwhat")
		{
			IRC :: PMsg(trim($where),'4[DEBUG-CMD] Detected WHAT Value: '.$what.' (SPLIT: '.implode( ' ' , array_slice( $bx , 1 )).')');
			echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": DWHAT (What Detection)\n";
				IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": DWHAT (What Detection)");
			return 1;
		}

		if(trim($bx[0]) == ":!dnick")
		{
			IRC :: PMsg(trim($where),'4[DEBUG-CMD] Detected NICK Value: '.str_replace(":","",$guser[0]));
			echo ">> [COMMAND] Recieved from ".str_replace(":","",$guser[0]).": DNICK (Nick Detection)\n";
				IRCHandle :: IRCLogger("[COMMAND] Recieved from ".str_replace(":","",$guser[0]).": DNICK (Nick Detection)");
			return 1;
		}


		// **********************
		// CTCP Commands
		// **********************
		if(trim($what) == "VERSION" && trim($where) == Config :: $_server['NICK'])
		{
			IRC :: PNotice($guser[0],"\001VERSION FML - Open Source IRC Bot v1.0 (Created by Russell M Bradford)\001");
			echo ">> [CTCP] Recieved from ".str_replace(":","",$guser[0]).": VERSION (CTCP Version)\n";
		}
		if(trim($what) == "TIME" && trim($where) == Config :: $_server['NICK'])
		{
			IRC :: PNotice(str_replace(":","",$guser[0]),"\001TIME ".shell_exec('date')."\001");
			echo ">> [CTCP] Recieved from ".str_replace(":","",$guser[0]).": VERSION (CTCP Time)\n";
		}
	}


	function IRCLogger($text)
	{
		if(Config :: $_opts['IRCLOG'] == 1)
		{
			IRC :: PMsg(Config :: $_server['LOG_CHAN'],$text);
		}
	}

}

?>