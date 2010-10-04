<?php

class Error
{

	function SetErrorHandler()
	{
		set_error_handler(array('Error', 'HandleError'));
	}

	function HandleError($errno, $errmsg, $filename, $linenum, $vars) 
	{
		date_default_timezone_set('Europe/London');
    		$dt = date("[Y-m-d] H:i:s (T)");

		$errortype = array (
                	E_ERROR              => 'Error',
                	E_WARNING            => 'Warning',
               	E_PARSE              => 'Parsing Error',
               	E_NOTICE             => 'Notice',

                	E_CORE_ERROR         => 'Core Error',
                	E_CORE_WARNING       => 'Core Warning',

                	E_COMPILE_ERROR      => 'Compile Error',
                	E_COMPILE_WARNING    => 'Compile Warning',

               	E_USER_ERROR         => 'User Error',
               	E_USER_WARNING       => 'User Warning',
                	E_USER_NOTICE        => 'User Notice',

                	E_STRICT             => 'Runtime Notice',
                	E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
              );

    			$user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
    
    			$err = "[Error] ";
    			$err .= "(".$dt.")";
    			$err .= "- ".$errno;
    			$err .= " ".$errortype[$errno];
    			$err .= " ".$errmsg;
    			$err .= " [".$filename;
    			$err .= " (".$linenum . ")]";
			$err .= "\n";
    
			if($errno != 2048)
			{
			//error_log($err, 3, "/home/norek/ctextserv/error.log");
			}

    			$err = "".$errortype[$errno]."(".$errno.")";
    			$err .= " - ".$errmsg;
    			$err .= " [".$filename;
    			$err .= " (".$linenum . ")]";

			if($errno != 2048 && Config :: $_opts['EHANDLE'] == 1)
			{
 			IRC :: PMsg(Config :: $_server['DEB_CHAN'],"4[PHP] ".trim($err)."");
			}

	}

	function SendError($message,$type)
	{
		trigger_error($message, $type);
	}


}