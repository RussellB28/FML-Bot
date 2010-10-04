<?php

class Config
{

	// MySQL Configuration - Used for databases!
       public static $_mysql = Array(
						'HOST' => 'some.mysql-server.com',
						'USER' => 'mysql-user',
						'PASS' => 'mysql-pass',
						'DB' => 'mysql-db'
					  );

	// Server Configuration - Used for linking the servers!
       public static $_server = Array(
						'NAME' => 'NetworkName',
						'HOST' => 'irc.server.com',
						'PORT' => '6667',
						'BIND' => '1.2.3.4',
						'NICK' => 'FML',
						'IDENT' => 'openbot',
						'RNAME' => 'FML (Open Source Bot Created by YourName)',
						'MODES' => '+iwBI-x',
						'PASS' => 'somenickservpass',
						'SSL' => '0',
						'MAIN_CHAN' => '#fml',
						'LOG_CHAN' => '#fml.log',
						'DEB_CHAN' => '#fml.debug'
					  );

	// SSL Configuration - SSL Certificate Information
	public static $_ssl = Array (
						'SSLCERT' => '~/FML-Bot/data/fml.pem',
						'SSLPASS' => 'fudgemylife'
					);

	// General Configuration - Dynamic Options that are set at runtime!
       public static $_opts = Array(
						'RAWOUT' => 0,
						'SSL' => 0,
						'EHANDLE' => 1,
						'IRCLOG' => 1
					);

}

?>