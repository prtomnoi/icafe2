<?php
session_start();
ob_start();
include "inc/common.initial.php";
$id = date("U");
$today = date("Y-m-d");

if ($_POST["email-login"]){
 $user = Db::queryOne('SELECT aid as id FROM account WHERE email=? AND password=SHA1(?)',$_POST['email-login'],$_POST['password-login']."A^jblgfdr#Z");
 	
	 if(!$user){ 
		 echo SHA1($_POST['password-login']."A^jblgfdr#Z");
			 echo '2';
        } else {
			echo '1';
            $_SESSION['member-login'] = $user[id];
          
        }	
	
}
if($_POST[mode]){
	$row = Db::queryOne('SELECT * FROM account where email=? or fname=?',$_POST['email'],$_POST['fname']);	
	  if ($row[email] != "" || $row[fname] != "" )
		{
			$_SESSION['mmid'] = $row[user_id];
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

		   $_SESSION['mmid'] = $id;
		
		}
	}

?>
