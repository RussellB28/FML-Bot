<?php 

//ini_set('display_errors','0');

echo "FML Open Source Bot v1.0\n";
echo "-----------------------------\n";

echo ">> Loading Sources.... \n\n";

echo ">> Source Loading: m_ircsock                           ";
require("src/IRCSock.php");              		// - IRC Socket Class [REQUIRED]
echo "[OK] \n";
echo ">> Source Loading: m_irchandle                         ";
require("src/IRCHandle.php");              	// - IRC Handle Class [REQUIRED]
echo "[OK] \n\n";

echo ">> Loading Modules.... \n\n";

echo ">> Module Loading: m_irc                               ";
require("modules/m_irc.php");              	// - Provides IRC Functionality [RECOMMENDED]
echo "[OK] \n";
echo ">> Module Loading: m_functions                         ";
require("modules/m_functions.php");              // - Provides Timer Functionality and Additional Handlers [RECOMMENDED]
echo "[OK] \n";
echo ">> Module Loading: m_error                             ";
require("modules/m_error.php");   	              // - Required for Error Reports [REQUIRED]
echo "[OK] \n\n";

echo ">> Loading Configs.... \n\n";

echo ">> Config Loading: config                             ";
require("data/config.php");
echo "[OK] \n\n";

// ---------------------------------------------

Functions :: AddTimer('SERVER_PING',-1,120,'IRCSock :: Ping();');


if (class_exists('Error')) 
{
	Error :: SetErrorHandler();
	echo ">> [!] Error Module Loaded - Setting Error Handler\n";
}
else
{
	echo ">> [!] Error Module Not Loaded - Error Handler Disabled\n";	
}
IRCSock :: CreateSocket();

while ( 1 )
{
	IRCSock :: GetIRCData();
	Functions :: Check_Timers();
}


?>


