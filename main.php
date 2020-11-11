<?php
session_start();
mb_internal_encoding("UTF-8");
include "inc/common.initial.php";
include_once("common.user.php");
include_once("check.php");
$SETPAGE = array(
    
    'css' => 'dist/css.html',
	'menu' => 'dist/menu2.html',
	'main' => 'dist/main.html',
	'footer' => 'dist/footer.html',
	'js' => 'dist/js.html',
);

$template->set_filenames($SETPAGE);
$template->assign_var_from_handle('CSS', 'css');
$template->assign_var_from_handle('MENU', 'menu');
$template->assign_var_from_handle('FOOTER', 'footer');
$template->assign_var_from_handle('JS', 'js');
$template->pparse('main');
$template->destroy();
?>
