<?php
session_start();
mb_internal_encoding("UTF-8");
include "inc/common.initial.php";
include_once("common.user.php");
include_once("check.php");
$SETPAGE = array(
    'css' => 'dist/css.html',
	'menu' => 'dist/menu.html',
	'main' => 'dist/create-team.html',
	'footer' => 'dist/footer.html',
	'js' => 'dist/js.html',
);

$row1 = Db::queryOne('SELECT * FROM main_team,account Where uid = aid and aid=? ',$_SESSION['member-login']);
$con = Db::queryOne('SELECT * FROM teams WHERE st_team = 1 and team_id=? ',$row1['mt_id']);
$countd = count($con);
if($row1['mt_id'] == ''){
    $mode = "add";
}else{
    $mode = "edit";
}
        $form2 = '   <div class="row">
        <div class="col-md-12">
            <div class="item">
                <label>
                    <span >Team Name <i>*</i></span>
                    <input type="hidden" name="mode" id="mode"  value="'.$mode.'" placeholder="" > 
                    <input type="text" name="teamname" id="teamname" value="'.$row1['name_team'].'" placeholder="" required> 
                    <input type="hidden" name="picdir2"  value="'.$row1['m_picdir'].'" placeholder="" > 
                    <input type="hidden" name="pic2"  value="'.$row1['m_pic'].'" placeholder="" > 
                    <input type="hidden" name="team_id"  value="'.$row1['mt_id'].'" placeholder="" > 
                    <input type="hidden" name="id_member"  value="'.$con['tid'].'" placeholder="" > 
                </label>
            </div>	
        </div>
        <div class="col-md-6">
            <div class="item">
                <label>
                    <span >Name <i>*</i></span>
                    <input type="text" name="name1" id="name1" value="'.$con['first_name'].'" placeholder="" required>
                </label>
            </div>	
        </div>
        <div class="col-md-6">
            <div class="item">
                <label>
                    <span>Sername <i>*</i></span>
                    <input type="text" name="surname1" id="surname1" value="'.$con['last_name'].'" placeholder="">
                </label>
            </div>	
        </div>
        <div class="col-md-6">
            <div class="item">
                <label>
                    <span>ID Card Code <i>*</i></span>
                    <input type="text" name="id_card1" value="'.$con['id_card'].'" id="id_card1">
                </label>
            </div>	
        </div>
        <div class="col-md-6">
            <div class="item">
                <label>
                    <span>Email  <i>*</i></span>
                    <input type="email" name="email1" value="'.$con['email'].'" id="email1">
                </label>
            </div>	
        </div>
        <div class="col-md-6">
            <div class="item">
                <label>
                    <span>Tel  <i>*</i></span>
                    <input type="text" name="tel1" value="'.$con['tel'].'" id="tel1">
                </label>
            </div>	
        </div>
        <div class="col-md-6">
            <div class="item">
                <label>
                    <span>Ingame Name <i>*</i></span>
                    <input type="text" name="ingamename1" value="'.$con['ingame_name'].'" id="ingamename1">
                </label>
            </div>	
        </div>
        <div class="col-md-6">
            <div class="item">
                <label>
                    <span>Garana ID <i>*</i></span>
                    <input type="text" name="garena_id1" value="'.$con['garena_id'].'" id="garena_id1">
                </label>
            </div>	
        </div>
        <div class="col-md-6">
            <div class="item">
                <label>
                    <span>UID  <i>*</i></span>
                    <input type="text" name="uid1" value="'.$con['uid'].'" id="uid1">
                </label>
            </div>	
        </div>

        <div class="col-md-12">
            <div class="item">
                <label>
                    <span>Facebook URL  <i>*</i></span>
                    <input type="text" name="facebook1" id="facebook1" value="'.$con['facebook'].'">
                    <input type="file" name="pic1" id="pic1" style="display: none;"> 
                </label>
            </div>	
        </div>
        <div class="col-md-12 text-right">
            <a href="javascript:void(0);" ><img src="images/edit.png" alt="" width="120"> </a>
            <a href="javascript:void(0);" id="submit-mainteam"><img src="images/save.png" alt="" width="120"> </a>
        </div>
    </div>';
    $form3 = '   
    <div class="row">
            <div class="col-md-12">
                <div class="item">
                    <label>
                        <span >Team Name <i>*</i></span>
                        <input type="text" name="teamname"  placeholder=""  value="'.$row1['name_team'].'" readonly> 
                    </label>
                </div>	
            </div>
            <div class="col-md-12">
                <div class="item">
                    <label>
                        <span>Email  <i>*</i></span>
                        <input type="email" name="email1" value="'.$con['email'].'" readonly>
                    </label>
                </div>	
            </div>
            <div class="col-md-12">
                <div class="item">
                    <label>
                        <span>Tel  <i>*</i></span>
                        <input type="text" name="tel1" value="'.$con['tel'].'" readonly>
                    </label>
                </div>	
            </div>

            <div class="col-md-12">
                <div class="item">
                    <label>
                        <span>Facebook URL  <i>*</i></span>
                        <input type="text" name="facebook1" value="'.$row1['facebook'].'" readonly>
                    </label>
                </div>	
            </div>
            <div class="col-md-12 text-right">
                <a href="?edit=1" ><img src="images/edit.png" alt="" width="120"> </a>
                <a href="javascript:void(0);" ><img src="images/check.png" alt="" width="40"> </a>
            </div>
        </div>';

if($row1['mt_id'] == ""){
                $form = $form2;
                $images = 'images/frame.png';
}else{
     $form = $form3;

    $images = $row1['m_picdir'].'/'.$row1['m_pic'];
}
if($_GET['edit'] == 1){
    $form = $form2;
}

$con2 = Db::queryOne('SELECT * FROM teams WHERE team_id=? order by tid ASC limit 1,1',$row1['mt_id']);
$con3 = Db::queryOne('SELECT * FROM teams WHERE team_id=? order by tid ASC limit 2,1',$row1['mt_id']);
$con4 = Db::queryOne('SELECT * FROM teams WHERE team_id=? order by tid ASC limit 3,1',$row1['mt_id']);
$con5 = Db::queryOne('SELECT * FROM teams WHERE team_id=? order by tid ASC limit 4,1',$row1['mt_id']);

if($con2['first_name'] == ''){
   $head1 = '<img src="images/add.png" alt="add" width="35"> ADD MEMBER';
   $headr1 = '';
   $position1 = '';
   $btn1 = '<button type="submit" class="btn-hidden"><img src="images/save.png" alt="" width="120"> </button>';
}else{
    $head1 = $con2['first_name'].' '.$con2['last_name'];
    $headr1 = '<img src="images/edit.png" alt="" width="120"> <a href="javascript:void(0);" id="submit-member1" class=""><img src="images/check.png" alt="" width="30"> </a></span>';
    $position1 = 2;
    $btn1 = '<button type="button" data-id="1"  class="btn-hidden edit-form"><img src="images/save.png" alt="" width="120"> </button>';
}
if($con3['first_name'] == ''){
    $head2 = '<img src="images/add.png" alt="add" width="35"> ADD MEMBER';
    $headr2 = '';
    $position2 = '';
    $btn2 = '<button type="submit" class="btn-hidden"><img src="images/save.png" alt="" width="120"> </button>';
 }else{
     $head2 = $con3['first_name'].' '.$con3['last_name'];
     $headr2 = '<img src="images/edit.png" alt="" width="120"> <a href="javascript:void(0);" id="submit-member1" class=""><img src="images/check.png" alt="" width="30"> </a></span>';
     $position2 = 3;
     $btn2 = '<button type="button" data-id="2"  class="btn-hidden edit-form"><img src="images/save.png" alt="" width="120"> </button>';
 }
 if($con4['first_name'] == ''){
    $head3 = '<img src="images/add.png" alt="add" width="35"> ADD MEMBER';
    $headr3 = '';
    $position3 = '';
    $btn3 = '<button type="submit" class="btn-hidden"><img src="images/save.png" alt="" width="120"> </button>';

 }else{
     $head3 = $con4['first_name'].' '.$con4['last_name'];
     $headr3 = '<img src="images/edit.png" alt="" width="120"> <a href="javascript:void(0);" id="submit-member1" class=""><img src="images/check.png" alt="" width="30"> </a></span>';
     $position3 = 4;
     $btn3 = '<button type="button" data-id="3" class="btn-hidden edit-form"><img src="images/save.png" alt="" width="120"> </button>';
 }
 if($con5['first_name'] == ''){
    $head4 = '<img src="images/add.png" alt="add" width="35"> ADD MEMBER';
    $headr4 = '';
    $position4 = '';
    $btn4 = '<button type="submit" class="btn-hidden"><img src="images/save.png" alt="" width="120"> </button>';
 }else{
     $head4 = $con5['first_name'].' '.$con5['last_name'];
     $headr4 = '<img src="images/edit.png" alt="" width="120"> <a href="javascript:void(0);" id="submit-member1" class=""><img src="images/check.png" alt="" width="30"> </a></span>';
     $position4 = 5;
     $btn4 = '<button type="button"  data-id="4" class="btn-hidden edit-form"><img src="images/save.png" alt="" width="120"> </button>';

 }
$template->assign_vars(array(

    "images" => $images,
    "form_create_team" => $form,
    "team_id" => $row1['mt_id'],
    "name2" => $con2['first_name'],
    "surname2" => $con2['last_name'],
    "surname2" => $con2['last_name'],
    "id_card2" => $con2['id_card'],
    "ingame_name2" => $con2['ingame_name'],
    "garena_id2" => $con2['garena_id'],
    "uid2" => $con2['uid'],
    "memberid1" => $con2['tid'],
    "email2" => $con2['email'],
    "tel2" => $con2['tel'],
    "facebook2" => $con2['facebook'],
    "headname1" => $head1,
    "position1" => $position1,
    "headright1" => $headr1,
    "btn1" => $btn1,
    "name3" => $con3['first_name'],
    "surname3" => $con3['last_name'],
    "surname3" => $con3['last_name'],
    "id_card3" => $con3['id_card'],
    "ingame_name3" => $con3['ingame_name'],
    "garena_id3" => $con3['garena_id'],
    "uid3" => $con3['uid'],
    "memberid3" => $con3['tid'],
    "email3" => $con3['email'],
    "tel3" => $con3['tel'],
    "facebook3" => $con3['facebook'],
    "headname2" => $head2,
    "position2" => $position2,
    "headright2" => $headr2,
    "btn2" => $btn2,
    "name4" => $con4['first_name'],
    "surname4" => $con4['last_name'],
    "surname4" => $con4['last_name'],
    "id_card4" => $con4['id_card'],
    "ingame_name4" => $con4['ingame_name'],
    "garena_id4" => $con4['garena_id'],
    "uid4" => $con4['uid'],
    "memberid4" => $con4['tid'],
    "email4" => $con4['email'],
    "tel4" => $con4['tel'],
    "facebook4" => $con4['facebook'],
    "headname3" => $head3,
    "position3" => $position3,
    "headright3" => $headr3,
    "btn3" => $btn3,
    "name5" => $con5['first_name'],
    "surname5" => $con5['last_name'],
    "surname5" => $con5['last_name'],
    "id_card5" => $con5['id_card'],
    "ingame_name5" => $con5['ingame_name'],
    "garena_id5" => $con5['garena_id'],
    "uid5" => $con5['uid'],
    "memberid5" => $con5['tid'],
    "email5" => $con5['email'],
    "tel5" => $con5['tel'],
    "facebook5" => $con5['facebook'],
    "headname4" => $head4,
    "position4" => $position4,
    "headright4" => $headr4,
    "btn4" => $btn4,
    
));


$template->set_filenames($SETPAGE);
$template->assign_var_from_handle('CSS', 'css');
$template->assign_var_from_handle('MENU', 'menu');
$template->assign_var_from_handle('FOOTER', 'footer');
$template->assign_var_from_handle('JS', 'js');
$template->pparse('main');
$template->destroy();
?>
