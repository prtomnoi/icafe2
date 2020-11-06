<?PHP
session_start();
include "inc/common.initial.php";

 $today = date("Y-m-d H:i:s");
 $getDateRegis= date('U');
	$id = date("U");

		  $row = Db::queryOne('SELECT * FROM account where email=? ',$_POST['email-register']);	
			if ($row['email'] == $_POST['email-register'])
			{
				echo 2;
			}
			else
			{

			$password = $_POST['password-register']."A^jblgfdr#Z";

			Db::query('INSERT INTO account SET user_id=?,email=?,date_regis=?,phone=?,password=SHA1(?)'
			,$id,$_POST['email-register'],$today,$_POST['mobile-register'],$password); 
			$lastId = Db::getLastId();
			$_SESSION['member-login'] = $lastId;
			
		}
			

	
	


?>
