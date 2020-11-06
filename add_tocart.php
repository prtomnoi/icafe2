<?php
session_start();

if ($_POST)
{
	 if (!isset($_SESSION['fname']))
    {
        $_SESSION['fname'] = array();
        $_SESSION['surname'] = array();
        $_SESSION['id_card'] = array();
        $_SESSION['ingame_name'] = array();
        $_SESSION['garena_id'] = array();
        $_SESSION['uid'] = array();
        $_SESSION['email'] = array();
        $_SESSION['tel'] = array();
        $_SESSION['facebook'] = array();
    }
	$fname = isset($_POST['fname']) ? $_POST['fname'] : "";
    $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : "";
    if (in_array($itemId, $_SESSION['cart']))
    {
        $key = array_search($itemId, $_SESSION['cart']);
        $_SESSION['qty'][$key] = $quantity;
		$_SESSION['color'][$key] = $_POST['color'];
		 header('location:mycart.php');
      
    } else
    {
        array_push($_SESSION['cart'], $itemId);
        $key = array_search($itemId, $_SESSION['cart']);
		$_SESSION['qty'][$key] = $quantity;
		$_SESSION['color'][$key] = $_POST['color'];
        header('location:mycart.php');
    }
}

?>