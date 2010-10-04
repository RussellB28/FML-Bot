<?php

class Functions
{

	public static $_Timers	= Array( );

	function AddTimer( $Name , $Times , $Repeat , $Action )
	{
		self :: $_Timers[ $Name ] = Array( );
		self :: $_Timers[ $Name ][ 'time' ] = time( ) + $Repeat;
		self :: $_Timers[ $Name ][ 'times' ] = $Times;
		self :: $_Timers[ $Name ][ 'repeat' ] = $Repeat;
		self :: $_Timers[ $Name ][ 'action' ] = $Action;
	}
	
	function DelTimer( $Name )
	{
		if ( isset( self :: $_Timers[ $Name ] ) )
		{
			unset( self :: $_Timers[ $Name ] );
		}
	}

	function Check_Timers( )
	{
		if ( count( self :: $_Timers ) > 0 )
		{
			foreach ( self :: $_Timers as $key => $tmr )
			{
				if ( time( ) >= $tmr['time'] )
				{
					eval( $tmr['action'] );
					$times = ( isset( self :: $_Timers[$key]['times'] ) ) ? self :: $_Timers[$key]['times'] : 0;
					if ( $times != '-1' )
					{
						$times--;
						if ( $times > 0 )
						{
							self :: $_Timers[$key]['time'] = time( ) + self :: $_Timers[$key]['repeat'];
							self :: $_Timers[$key]['times'] = $times;
						} else
						{
							unset(self :: $_Timers[$key]);
						}
					} else
					{
						self :: $_Timers[$key]['time'] = time() + self :: $_Timers[$key]['repeat'];
					}
				}
			}
		}
	}

	function Evaluate( $String )
	{
		ob_start(); 
		eval( "$String" ); 
		$ret = ob_get_contents(); 
		ob_end_clean();
		
		return $ret;
	}

	function SigHandle($signo)
	{

     		switch ($signo) {
         		case SIGTERM:
             			IRCSock :: DestroySocket("FML Open Source Bot v1.0 (Terminated via SIGTERM)");
	      			exit;
            			break;
         		case SIGKILL:
            			IRCSock :: DestroySocket("FML Open Source Bot v1.0 (Killed)");
             			exit;
             			break;
         		case SIGINT:
             			IRCSock :: DestroySocket("FML Open Source Bot v1.0 (Restarting)");
             			exit;
             			break;
         		case SIGQUIT:
             			IRCSock :: DestroySocket("FML Open Source Bot v1.0 (Quit)");
             			break;
         		default:
            			IRCSock :: DestroySocket("Quit: ".$signo." Recieved");
     		}

	}

}


?>