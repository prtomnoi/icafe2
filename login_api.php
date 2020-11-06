<?php
session_start();
ob_start();
include "inc/common.initial.php";
$id = date("U");
$today = date("Y-m-d");
	$row = Db::queryOne('SELECT * FROM account where email=? or fname=?',$_POST['email'],$_POST['fname']);	
	  if ($row[email] != "" || $row[fname] != "" )
		{
			$_SESSION['uid'] = $row[user_id];
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
				Db::query('INSERT INTO account SET user_id=?,fname=?,lname=?,email=?,date_regis=?'
				,$id,$_POST['fname'],$_POST['lname'],$email,$today); 
				$lastId = Db::getLastId();

			     $_SESSION['uid'] = $id;
		
		}


?>
