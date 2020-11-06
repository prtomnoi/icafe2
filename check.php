<?php
$user = Db::queryOne('SELECT * FROM account WHERE aid=? ',$_SESSION['member-login']);

if($_SESSION['member-login'] == "") {
  $li = '<li><a href="#" data-toggle="modal" data-target="#myModal"><span>Login</span></a></li>';
  $create_team = '<a href="javascript:(void);" onclick="check_login();"><img src="images/create.png" alt="" width="60%"> </a>';

} else {

    $li ='
    <li>
    <a href="#other"><span> <i class="fa fa-user" aria-hidden="true"></i> '.substr($user["email"],0,13).'...</span></a>
    <ul>
        <li><a href="profile.php"><span>Profile</span></a></li>
        <li><a href="logout.php"><span>Logout</span></a></li>
    </ul>
  </li>';

  $create_team = '<a href="create-team.php"><img src="images/create.png" alt="" width="60%"> </a>';
 	
}



       $template->assign_vars(array(
		"login" => $li,
		"create_team" => $create_team,
	));

?>
 