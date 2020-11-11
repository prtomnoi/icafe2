<?php
session_start();
ob_start();
include "inc/common.initial.php";
$id = date("U");
$today = date("Y-m-d H:i:s");
	$row = Db::queryOne('SELECT * FROM account where email=? ',$_POST['email']);	
	  if ($row[email] != "" || $row[fname] != "" )
		{
			$_SESSION['member-login'] = $row[aid];
		}
		else
		{
				if(!$_POST['email'])
				{
					$email = '';
				}
				else
				{
					$email=$_POST['email'];
				}
				Db::query('INSERT INTO account SET user_id=?,email=?,date_regis=?'
				,$id,$email,$today); 
				$lastId = Db::getLastId();

				$_SESSION['member-login'] = $lastId;
			
		
		}


?>
