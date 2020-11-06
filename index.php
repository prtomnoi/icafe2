<?php
session_start();
mb_internal_encoding("UTF-8");
include "inc/common.initial.php";
include_once("common.user.php");
include_once("check.php");
$SETPAGE = array(
    
    'css' => 'dist/css.html',
	'menu' => 'dist/menu.html',
	'main' => 'dist/index.html',
	'footer' => 'dist/footer.html',
	'js' => 'dist/js.html',
);

$con = Db::queryAll('SELECT * FROM main_team order by mt_id  DESC');

for($i=0; $i<count($con); $i++)
{
	
	
	$template->assign_block_vars('team',array(

		"id" => $con[$i]['mt_id'],
		"name" => $con[$i]['name_team'],
		"picdir" => $con[$i]['m_picdir'],
		"pic" => $con[$i]['m_pic'],
		
	));
	
}



$template->set_filenames($SETPAGE);
$template->assign_var_from_handle('CSS', 'css');
$template->assign_var_from_handle('MENU', 'menu');
$template->assign_var_from_handle('FOOTER', 'footer');
$template->assign_var_from_handle('JS', 'js');
$template->pparse('main');
$template->destroy();
?>
