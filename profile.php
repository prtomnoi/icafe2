<?php
session_start();
mb_internal_encoding("UTF-8");
include "inc/common.initial.php";
include_once("common.user.php");
include_once("check.php");
$SETPAGE = array(
    
    'css' => 'dist/css.html',
	'menu' => 'dist/menu.html',
	'main' => 'dist/profile.html',
	'footer' => 'dist/footer.html',
	'js' => 'dist/js.html',
);




if($_GET['id']){
    $row1 = Db::queryOne('SELECT * FROM main_team,account Where uid = aid and mt_id=? ',$_GET['id']);
    if($row1['mt_id'] == ''){
        echo '<script>
        setTimeout(function() {
                swal({
                    title: "Error 404",
                    text: "Not found Team!",
                    type: "error"
                }, function() {
                    window.location = "create-team.php";
                });
            }, 1000);
        </script>';
    }
    
    $con = Db::queryAll('SELECT * FROM teams WHERE team_id =? order by tid  ASC',$_GET['id']);
 

}else{
    $row1 = Db::queryOne('SELECT * FROM main_team,account Where uid = aid and aid=? ',$_SESSION['member-login']);
    if($row1['mt_id'] == ''){
        echo '<script>
        setTimeout(function() {
                swal({
                    title: "Error 404",
                    text: "Please Create Team!",
                    type: "error"
                }, function() {
                    window.location = "create-team.php";
                });
            }, 1000);
        </script>';
    }
    $con = Db::queryAll('SELECT * FROM teams WHERE team_id =? order by tid  ASC',$row1['mt_id']);
    

}
if($_SESSION['member-login'] == $row1['uid']){
    $btn = '<a href="create-team"><img src="images/edit.png" alt="" width="120"> </a>';
}else{
    $btn = '';
}

$j = 0;
for($i=0; $i<count($con); $i++)
{
	
	if($j == 0){
        $cap = 'Captain / Team leader';
        $j = '';
    }else{
        $cap = 'Member';
    }
	$template->assign_block_vars('team',array(

		"id" => $j,
		"name1" => $con[$i]['first_name'],
		"surname1" => $con[$i]['last_name'],
        "id_card1" => $con[$i]['id_card'],
        "ingame_name1" => $con[$i]['ingame_name'],
        "garena_id1" => $con[$i]['garena_id'],
        "uid1" => $con[$i]['uid'],
        "memberid1" => $con[$i]['tid'],
        "email1" => $con[$i]['email'],
        "tel1" => $con[$i]['tel'],
        "facebook1" => $con[$i]['facebook'],
        "position" => $cap,
		
    ));
    $j++;
	
}



$template->assign_vars(array(

    "logo_team_profile" => $row1['m_picdir']."/".$row1['m_pic'],
    "team_id" => $row1['mt_id'],
    "name_team" => $row1['name_team'],
    "email" => $row1['email'],
    "tel" => $row1['phone'],
    "name1" => $con1['first_name'],
    "surname1" => $con1['last_name'],
    "id_card1" => $con1['id_card'],
    "ingame_name1" => $con1['ingame_name'],
    "garena_id1" => $con1['garena_id'],
    "uid1" => $con1['uid'],
    "memberid1" => $con1['tid'],
    "email1" => $con1['email'],
    "tel1" => $con1['tel'],
    "facebook1" => $con1['facebook'],
    "btn-edit-profile" => $btn,

    
));



$template->set_filenames($SETPAGE);
$template->assign_var_from_handle('CSS', 'css');
$template->assign_var_from_handle('MENU', 'menu');
$template->assign_var_from_handle('FOOTER', 'footer');
$template->assign_var_from_handle('JS', 'js');
$template->pparse('main');
$template->destroy();
?>
