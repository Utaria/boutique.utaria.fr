<?php
 
$id = $_POST["id"];
 
if(is_numeric($id)){
	session_start();
 	if(!isset($_SESSION["cart"])) $_SESSION["cart"] = array();
 	$_SESSION["cart"][] = $id;
}
 
?>