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

 $row1 = Db::queryOne('SELECT * FROM main_team Where name_team = ? ',$_POST['team_id']);

if($_POST['menber-position'] == ''){
	Db::query('INSERT INTO teams SET team_id=?,first_name=?,last_name=?,id_card=?,email=?,tel=?,ingame_name=?,garena_id=?,uid=?,facebook=?,date_team=?,st_team=?'
	  ,$_POST['team_id'],$_POST['name2'],$_POST['surname2'],$_POST['id_card2'],$_POST['email2'],$_POST['tel2'],$_POST['ingamename2'],$_POST['garena_id2'],$_POST['uid2'],$_POST['facebook2'],$today2,0);   
}else{
	
    Db::query('UPDATE teams SET first_name=?,last_name=?,id_card=?,email=?,tel=?,ingame_name=?,garena_id=?,uid=?,facebook=? WHERE tid=?'
	  ,$_POST['name2'],$_POST['surname2'],$_POST['id_card2'],$_POST['email2'],$_POST['tel2'],$_POST['ingamename2'],$_POST['garena_id2'],$_POST['uid2'],$_POST['facebook2'],$_POST['menberid']);   
}
    
Header('Location:create-team.php')

?>

