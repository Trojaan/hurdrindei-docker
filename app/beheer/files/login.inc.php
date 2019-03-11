<?php
include_once ( dirname ( __FILE__ ) . '/../init.inc.php' );
if ( is_array ( $_POST [ 'login' ] ) && count ( $p = $_POST [ 'login' ] ) == 2 )
{
	$sql = "SELECT
					`userID`,
					`password`
				FROM
					`[PREFIX]_users`
				WHERE
					`username` = '" . addslashes ( $p [ 'user' ] ) . "'
				AND
					`password` = '" . addslashes ( $p [ 'pass' ] ) . "';";
	if ( mysql_num_rows ( $res = Xmysql_query ( $sql ) ) == 1 )
	{
		$row = mysql_fetch_assoc ( $res );
		if ( $row [ 'password' ] == $p [ 'pass' ] )
		{
			$sql = "UPDATE
							`[PREFIX]_users`
						SET
							`session` = '" . addslashes ( session_id () ) . "',
							`sessionTimeout` = '" . date ( 'Y-m-d H:i:s', mktime () + SESS_TIMEOUT ) . "'
						WHERE
							`userID` = " . (int)$row [ 'userID' ] . ";";
			if ( Xmysql_query ( $sql ) )
			{
				if ( mysql_affected_rows () == 1 )
				{
					header ( 'Location: index.php' );
				}
			}
		}
	}

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Login - <?= SITE_NAME; ?></title>
	<link rel="stylesheet" type="text/css" href="style.css"></link>
    
	<style type="text/css">
	body {
		background: none;
		 }
  </style> 
</head>
 
  <body>

	<div id="login">
	<form action="<?= $_SERVER [ 'REQUEST_URI' ]; ?>" method="post">
		<table>
			<tr>
				<td style="width: 100px;">Gebruikersnaam:</td>
				<td><input type="text" name="login[user]" value="<?= $p [ 'user' ]; ?>" /></td>
			</tr>
			<tr>
				<td>Wachtwoord:</td>
				<td><input type="password" name="login[pass]" value="" /></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" value="aanmelden" style="float: right;"/></td>
			</tr>
		</table>
	</form>
	</div>
  
  </body>
</html>
