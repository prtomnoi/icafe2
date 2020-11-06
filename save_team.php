<?php
session_start();
include "inc/common.initial.php";
 $y =  date('Y');
 $m =  date('m');
 $pic_dir = "upload/logo/$y/$m";
 $TitleDir1 = "$pic_dir/";
 $today = date("Y-m-d");
 $today2 = date("Y-m-d H:i:s");
 $getDateRegis= date('U');
if($_POST['mode'] == 'add'){
 $row1 = Db::queryOne('SELECT * FROM main_team Where name_team = ? ',$_POST['teamname']);

 if($row1['name_team'] == $_POST['teamname']){
	
	echo "<script>alert('The name of this team already exists');window.history.back();</script>";
	exit;

 }else{

if($_FILES['pic1']['name'] != ""){
			$file_type = $_FILES['pic1']['type']; 
			if (  
					($file_type != "image/jpeg") &&
					($file_type != "image/jpg") &&
					($file_type != "image/gif") &&
					($file_type != "image/png")
                 )
				{
					$message = 'Invalid file type. Only  JPG, GIF and PNG types are accepted.'; 
					echo "<script>alert('".$message."');window.history.back();</script>";
					// echo 4;
					exit;
	}    
	$dd = $getDateRegis;
	$TitleType = substr($_FILES['pic1']['name'],-4);	
	$TitleName1 = $dd.$TitleType;
	
$upTitle = move_uploaded_file($_FILES['pic1']['tmp_name'], $TitleDir1 . $TitleName1) or die ("<script>alert('ไม่สามารถอัพโหลด Picture');window.history.back();</script>");
}
else{
	
	echo "<script>alert('Please choose your logo');window.history.back();</script>";
	exit;
}

       Db::query('INSERT INTO main_team SET mt_id=?,uid=?,facebook=?,name_team=?,m_picdir=?,m_pic=?,date_mt=?,type_id=?'
	  ,$getDateRegis,$_SESSION['member-login'],$_POST['facebook1'],$_POST['teamname'],$pic_dir,$TitleName1,$today,1);  
	  $lastId = Db::getLastId();



		Db::query('INSERT INTO teams SET team_id=?,first_name=?,last_name=?,id_card=?,email=?,tel=?,ingame_name=?,garena_id=?,uid=?,facebook=?,date_team=?,st_team=?'
	  ,$getDateRegis,$_POST['name1'],$_POST['surname1'],$_POST['id_card1'],$_POST['email1'],$_POST['tel1'],$_POST['ingamename1'],$_POST['garena_id1'],$_POST['uid1'],$_POST['facebook1'],$today2,1);   

	
	//   header("Location:invite_team.php?teamId=$lastId");
	  
	
}
}else{

	
   if($_FILES['pic1']['name'] != ""){
			   $file_type = $_FILES['pic1']['type']; 
			   if (  
					   ($file_type != "image/jpeg") &&
					   ($file_type != "image/jpg") &&
					   ($file_type != "image/gif") &&
					   ($file_type != "image/png")
					)
				   {
					   $message = 'Invalid file type. Only  JPG, GIF and PNG types are accepted.'; 
					   echo "<script>alert('".$message."');window.history.back();</script>";
					   // echo 4;
					   exit;
	   }    
	   $dd = $getDateRegis;
	   $TitleType = substr($_FILES['pic1']['name'],-4);	
	   $TitleName1 = $dd.$TitleType;
	   
   $upTitle = move_uploaded_file($_FILES['pic1']['tmp_name'], $TitleDir1 . $TitleName1) or die ("<script>alert('ไม่สามารถอัพโหลด Picture');window.history.back();</script>");
   }
   else{
	 $pic_dir = $_POST['picdir2'];
	 $TitleName1 = $_POST['pic2'];
   }
   
		  Db::query('UPDATE main_team SET facebook=?,name_team=?,m_picdir=?,m_pic=? WHERE mt_id=?'
		 ,$_POST['facebook1'],$_POST['teamname'],$pic_dir,$TitleName1,$_POST['team_id']);  
		 $lastId = Db::getLastId();
   
   
   
		   Db::query('UPDATE teams SET first_name=?,last_name=?,id_card=?,email=?,tel=?,ingame_name=?,garena_id=?,uid=?,facebook=? WHERE tid=?'
		 ,$_POST['name1'],$_POST['surname1'],$_POST['id_card1'],$_POST['email1'],$_POST['tel1'],$_POST['ingamename1'],$_POST['garena_id1'],$_POST['uid1'],$_POST['facebook1'],$_POST['id_member']);   
   
	   
	   //   header("Location:invite_team.php?teamId=$lastId");
		 

}

?>

