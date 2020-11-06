<?php
// Thaisigmaweb version 2.5
// Last Check : 18/06/2553 02:11

class user {
	// Cookie
	var $cookie_name = "play";
	var $cookie_domain = ".play.co.th/";

	var $user_session = null;
	var $name = null;
	var $no = 0;
	var $rank = 0;
	var $guild = -1;
	var $position = -1;
	
	var $guest = true;
	var $memclass = "Guest";
	var $guildname = "-";
	var $avatar = "images_please_login.gif";
	var $iden = "คุณคือ Guest";

	var $canvote = false;
	var $ip = "";

	// Thaisigmaweb 2.2 Extension
	var $sp_report = false; // $user[special]{0}
	var $sp_forum = false; // $user[special]{1}
	var $sp_mod = false; // $user[special]{2}
	var $sp_gamerdb = false; // $user[special]{3}
	

	function user() {
		global $db,$REMOTE_ADDR;
		//session_start(); // Extension session
		//echo "session=".$_SESSION[$this->cookie_name]."<br> cookie=".$_COOKIE[$this->cookie_name];
		if ($_COOKIE[$this->cookie_name] != ""){
			$this->user_session = $_COOKIE[$this->cookie_name];
			//echo "<br>use cookie";
		} /*else {
			$this->user_session = $_SESSION[$this->cookie_name];
			//echo "<br>user session";
		}*/
		//$this->user_session = $_COOKIE[$this->cookie_name];
		$this->ip = $REMOTE_ADDR;
		$rc = explode("::",$this->user_session);
		if (count($rc) == 9){
			$this->retrieve($rc);
		}

	}

	function retrieve($rc){
		global $db;
		//$sql = "SELECT a.`no` , a.`id` , a.`displayname` , a.`guild` , a.`position` , a.`join`, a.`lastview`, a.`lastvote` , a.`rank` , a.`post` , a.`message` , a.`avatar`, a.`option` , a.`special` , r.`r_name` , r.`r_color` , g.`name` , g.`emblem` FROM `mem_account` a LEFT JOIN `mem_rank` r ON r.`r_id` = a.`rank` LEFT JOIN `guild_general` g ON g.`id` = a.`guild` WHERE a.`no` = '$rc[1]' LIMIT 1";

		$sql = "SELECT a.`no` , a.`id`  , a.`tl_user` , a.`tl_pass` , a.`displayname` , a.`guild` , a.`position` , a.`join`, a.`lastview`, a.`lastvote` , a.`rank` , a.`post` , a.`message` , a.`avatar`, a.`option` , a.`special` ,  r.`r_name` , r.`r_color` , g.`name` , g.`emblem` FROM `member` a LEFT JOIN `mem_rank` r ON r.`r_id` = a.`rank` LEFT JOIN `guild_general` g ON g.`id` = a.`guild` WHERE a.`no` = '$rc[1]' LIMIT 1";

		$mem = $db->fetch_query($sql,"ข้อมูลสมาชิก $rc[1]");
		if ($mem['no'] > 0 && $mem['id'] == $rc[0] && $mem['tl_cid'] == $rc[6] && $rc[8] == $mem['tl_pass']){
			$this->name = $mem['displayname'];
			$this->no = $mem['no'];
			$this->id = $mem['id'];
			$this->guild = $mem['guild'];
			$this->position = $mem['position'];
			$this->rank = $mem['rank'];
			$this->message = $mem['message'];
			$this->avatar = $mem['avatar'];
			$this->option = $mem['option'];
			$this->guest = false;
			$this->memclass = $mem['r_name'];
			$this->guildname = $mem['name'];
			$this->iden = "<a href=\"".ROOT."member/avatar.php\" target=\"_parent\">Edit avatar</a>";
			$this->canvote = ($mem['lastvote'] + 300 < time() ? true : false);

		

			$this->sp_report = ($mem['special']{0} > 0 ? $mem['special']{0} : false);
			$this->sp_forum = ($mem['special']{1} > 0 ? $mem['special']{1} : false);
			$this->sp_mod = ($mem['special']{2} > 0 ? $mem['special']{2} : false);
			$this->sp_gamerdb = ($mem['special']{3} > 0 ? $mem['special']{3} : false);
		} else {
			$this->logout();
		}

	}

	function showavatar(){
		$no = $this->no;
		$img = $this->avatar;
		$sub_dir = ceil($no / 500); // Thaisigmaweb
		if ($img != ""){
			if ($no == 0){
				$image = "<img src=\"".ROOT."images/".$img."\" alt=\"[ Guest ]\" border=\"0\">";
			} else {
				$image = "<img src=\"../_avatar/".$sub_dir."/".$img."\" alt=\"[ Member No : $no ]\" border=\"0\">";
			}
		} else { 
			$image = "&nbsp;";
		}
		return $image;
	}

	function login($username,$password,$remember = false,$original = false) {
		global $db;

		if (trim($username) == "" || trim($password) == ""){			return false;		}
		$password = md5($password);

		$sql = "SELECT `no`,`id`,`join`,`displayname` , `tl_user`, `tl_pass` FROM `member` WHERE `tl_user` = '$username' AND `tl_pass` = '$password' LIMIT 1";
		//$sql = "SELECT `no`,`id`, `pass` ,`join`,`displayname` FROM `mem_account` WHERE `id` = '$username' AND `pass` = '$password' LIMIT 1";

		$row1 = $db->fetch_query($sql,"Login");
		if ($row1){
			$rc = array ($row1[id],$row1[no],md5($row1[join]),$row1[displayname],$lock,time(),$row1[tl_cid],$row1[tl_user],$row1[tl_pass]);
			$this->user_session = implode("::",$rc);
			$this->retrieve($rc);
			$cookie = setcookie($this->cookie_name,$this->user_session,time()+2560000,'/',$this->cookie_domain);
			$_SESSION[$this->cookie_name] = $this->user_session;
			//echo "session=".$_SESSION[$this->cookie_name]."<br> cookie=".$_COOKIE[$this->cookie_name];
			if ($cookie){ return true; } else { return false; }
		} else {
			$this->logout();
			return false;
		}
	}

	function logout() {
		$cookie = setcookie($this->cookie_name,'',time()-25000,'/',$this->cookie_domain);
		//$cookie2 = session_destroy();
			$this->name = "Guest";
			$this->no = 0;
			$this->id = "";
			$this->guild = 0;
			$this->position = 0;
			$this->rank = 0;
			$this->message = null;
			$this->avatar = "images_please_login.gif";
			$this->option = null;
			$this->guest = true;

			$this->truelife = true;
			$this->tl_user = "";
			$this->tl_pass = "";
			$this->sp_forum = false;
			$this->sp_mod = false;
			$this->sp_report = false;
			$this->sp_gamerdb = false;
		return ($cookie || $cookie2);
	}

	function update($field,$value,$finalize = true,$increment = 0) {
		global $db;
		static $update;
		// throw exception
		if ($this->no == 0){
		} elseif ($finalize){
			$this->update($field,$value,false,$increment);
			$db->query("UPDATE `mem_account` SET ".implode(" , ",$update)." WHERE `no` = '".$this->no."' LIMIT 1","update สถิติของสมาชิก");
			unset($update);
		} else {
			if ($increment == 0){				$update[$field] = "`$field` = '$value'";
			} elseif ($increment > 0){				$update[$field] = "`$field` = `$field`+$value";
			} else {				$update[$field] = "`$field` = `$field`-$value";			}
		}
	}

	function check_permission($type,$id){
		global $db;
		if ($this->rank > 1){
			$row1 = $db->fetch_query("SELECT * FROM `mem_permission` WHERE `mem_no` = '$this->no' LIMIT 1","ข้อมูลสิทธิ์");
			if ($row1[$type]){
				$rw = array_count_values(explode(",",$row1[$type]));
				return ($rw[$id] > 0 || $rw[all] > 0 ? true : false);
			} else {
				return false;
			}
			
		} else {
			return false;
		}
	}

	function access($min_rank){
		global $db,$template;

		if ($this->rank < $min_rank){
			$SETPAGE = array(
				'body' => '../template/member_denied.tpl.html',
				'main' => '../template/template_main.tpl.html',
			);

			add_general_info();
			$template->set_filenames($SETPAGE);
			$template->assign_var_from_handle('BODY', 'body');
			$template->pparse('main');
			$template->destroy();
			exit;
		}
	}

}

?>
