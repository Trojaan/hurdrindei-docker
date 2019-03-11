<?php

	$sql = "SELECT 
						`userID`
					FROM
						`[PREFIX]_users`
					WHERE
						`session` = '" . addslashes ( session_id () ) . "'";
					
	if ( mysql_num_rows ( $res = Xmysql_query ( $sql ) ) == 1 )
	{
		define ( 'USERID', (int)mysql_result ( $res, 0, 0 ) );
		$sql = "UPDATE
						`[PREFIX]_users`
					SET
						`sessionTimeout` = 0
					WHERE
						`userID` = " . USERID . ";";
		Xmysql_query ( $sql );

		header ( 'Location: ' . ADMIN_PATH . 'index.php' );

		return true;
	}

?>