<?php
// Thaisigmaweb version 2.5
// Last Check : 11/09/2552 02:11

// Upload picture from input form
/*
$return : (var) variable to store value
$form : (string) name of file input
$base_dir : (string:relative path) target upload directory (default pattern)
$sub_dir : (string:relative path) append target upload directory (specific pattern)
$target_file : (string) target upload filename (rename file , exclude extension) , if use : in string , filename using instead.
$limit_width : (int) limit picture width
$limit_height : (int) limit picture height
$limit_size : (int:byte) limit picture filesize
// return value
false : cannot upload
true : upload successful
// &return (array)
[error] : (string) error
[name] : (string) original filename
[filename] : (string) renamed filename
[filepath] : (string) mini filepath (sub_dir + target_file)
[fullpath] : (string) full filepath (base_dir + sub_dir + target_file)
[filesize] : (int:byte) filesize
[width] : (int) image width
[height] : (int) image height
*/
function upload_picture(&$return,$form,$base_dir,$sub_dir = "",$target_file = "",$limit_size = 0,$limit_width = 0,$limit_height = 0){
	if ($_FILES[$form]['name'] != ""){
		$filename = stripslashes($_FILES[$form]['name']);
		$filesize = $_FILES[$form]['size'];
		if ($filesize < 5){
			$return['error'] = "คุณไม่ได้อัพโหลดไฟล์ใดๆเข้ามา";
			return false;
		}
		$pic_d = getimagesize($_FILES[$form]['tmp_name']);

		// Check available files
		switch($pic_d[2]){
			case 1 : $ft = ".gif"; break;
			case 2 : $ft = ".jpg"; break;
			case 3 : $ft = ".png"; break;
			default : $return['error'] = "ไม่รองรับชนิดของรูปที่อัพโหลดมา"; return false;
		}

		if ($target_file != ""){
			$a = basename($filename,$ft);
			$filepath = str_replace(":", $a , $target_file).$ft;
		} else {
			$filepath = $filename;
		}
		
		// catch exception
		if (substr($base_dir, -1) != "/"){	$base_dir .= "/"; }
		if (substr($sub_dir, -1) != "/" && $sub_dir != ""){	$sub_dir .= "/"; }

		// Non-space picture name
		$space = array("     ","    ","   ","  "," ");
		$nonspace = array("_","_","_","_","_");
		$filename = str_replace($space,$nonspace,$filename);
		$filepath = str_replace($space,$nonspace,$filepath);

		// Stored return value
		$return = array (
			'name' => $filename,
			'filename' => $filepath,
			'filepath' => $sub_dir.$filepath,
			'fullpath' => $base_dir.$sub_dir.$filepath,
			'size' => $filesize,
			'width' => $pic_d[0],
			'height' => $pic_d[1],
			'type' => $pic_d[2],
		);

		// Limit image size
		if ($limit_width > 0 && $pic_d[0] > $limit_width){
			$return['error'] = "ความกว้างของรูปมากกว่า $limit_width px";
			return false;
		}
		if ($limit_height > 0 && $pic_d[1] > $limit_height){
			$return['error'] = "ความสูงของรูปมากกว่า $limit_height px";
			return false;
		}
		if ($limit_size > 0 && $filesize > $limit_size){
			$return['error'] = "ขนาดไฟล์มากกว่า ". number_format($limit_size) ." byte";
			return false;
		}

		$uploadfile = $base_dir.$sub_dir.$filepath;
		if (!(is_dir($base_dir.$sub_dir))){	mkdir($base_dir.$sub_dir); } // Create directory if not existed
		if(!empty($_FILES[$form]) && $pic_d) {
			if (move_uploaded_file($_FILES[$form]['tmp_name'] ,$uploadfile)){
				$return['error'] = "[complete] อัพโหลดรูป $filename -> $filepath ( ".$pic_d[0]."x".$pic_d[1]." ) ขนาดไฟล์ ". number_format($filesize) ." byte";
				return true;
			}
		}
	} else {
		return false;
	}
	
}

// Thumbnail
/*
$filename : (string) path of filename
$savedname : (string) generated filename
$limit_width : (int) picture width limit (least than 100px)
$limit_height : (int) picture height limit (least than 100px)
// &return (array)
[filesize] : (int:byte) filesize
[width] : (int) image width
[height] : (int) image height
*/
function img_thumbnail(&$result,$filename, $savedname = "" , $limit_width = 0 , $limit_height = 0){

	$im = @imagecreatefromjpeg($filename);
	$width = imagesx($im);
	$height = imagesy($im);

	// Thumbnail size
	// มาตรฐาน ความสูงไม่เกิน 100 ความกว้างไม่เกิน 100

	if ($limit_width < 100){ $limit_width = 100; }
	if ($limit_height < 100){ $limit_height = 100; }

	$ratio_width = $width / $limit_width;
	$ratio_height = $height / $limit_height;
	if ($ratio_width > $ratio_height){
		$new_width = $width / $ratio_width;
		$new_height = $height / $ratio_width;
	} else {
		$new_width = $width / $ratio_height;
		$new_height = $height / $ratio_height;
	}

	$thumb = imagecreatetruecolor($new_width, $new_height);

	// Resize
	imagecopyresampled($thumb, $im, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	if ($savedname != ""){
		$res = imagejpeg($thumb,$savedname,90);
		$result[size] = filesize($savedname);
	} else {
		$res = imagejpeg($thumb);
	}

		$result[width] = $new_width;
		$result[height] = $new_height;
	//echo "Ratio width = $ratio_width<br>Ratio height = $ratio_height";
	return $res;
}

// Watermark
/*
$filename : (string) path of filename
$savedname : (string) generated filename
$type : (int) watermark style
$position : (int) position (refer NUMPAD)
$trim : (int) margin of watermark
*/
function img_watermark(&$result , $filename , $savedname = "" , $type = 0 , $position = 0,$trim = 10){
	$trim = ($trim > 10 || $trim < 30 ? (int)($trim) : 10);

	$im = @imagecreatefromjpeg($filename);

	$position = (int)($position);
	if ($position < 1 || $position > 9){
		$pos = true;
	}
	switch ($type){
		case 1 : $bg = "../gallery/watermark_bg_big.png"; if ($pos){ $position = 5; } break;
		case 2 : $bg = "../gallery/watermark_colorlogo.png"; if ($pos){ $position = 3; } break;
		case 3 : $bg = "../gallery/watermark_whitelogo.png"; if ($pos){ $position = 3; } break;
		default : $bg = "../gallery/watermark_whitelogo.png"; if ($pos){ $position = 3; } break;
	}

	$wm = @imagecreatefrompng($bg);
	$a[1] = imageAlphaBlending($wm, true);
	$a[2] = imageSaveAlpha($wm, true);
	//$a[3] = imageantialias($wm,true);
	$width = imagesx($im);
	$height = imagesy($im);

	$wm_width = imagesx($wm);
	$wm_height = imagesy($wm);

	$new_width = $width;
	$new_height = $height;
	$image_base = imagecreatetruecolor($new_width, $new_height);
	imagecopymerge($image_base, $im, 0, 0, 0, 0, $new_width, $new_height, 100);

	if ($width < $wm_width || $height < $wm_height){		
	} else {
		// Watermark position (x)
		switch($position){
			case 1 :
			case 4 :
			case 7 : $c_x = $trim; break;
			case 2 :
			case 5 :
			case 8 : $c_x = (int)(($width - $wm_width) / 2); break;
			case 3 :
			case 6 :
			case 9 : $c_x = ($width - $wm_width - $trim); break;
		}

		// Watermark position y
		switch($position){
			case 1 :
			case 2 :
			case 3 : $c_y = ($height - $wm_height - $trim); break;
			case 4 :
			case 5 :
			case 6 : $c_y = (int)(($height - $wm_height) / 2); break;
			case 7 :
			case 8 :
			case 9 : $c_y = $trim; break;
		}

		//imagecopymerge($image_base, $wm, $c_x, $c_y, 0, 0, $wm_width, $wm_height, 50);
		imagecopy($image_base, $wm, $c_x, $c_y, 0, 0, $wm_width, $wm_height);
	}

	//print_r($a);
	
	//echo "c_x = $c_x , c_y = $c_y , width = $width , height = $height , wm_width = $wm_width , wm_height = $wm_height";
	if ($savedname != ""){
		$res = imagejpeg($image_base,$savedname,90);
		$result[size] = filesize($savedname);
	} else {
		$res = imagejpeg($image_base);
	}

	$result[width] = $new_width;
	$result[height] = $new_height;

	return $res;
}

?>