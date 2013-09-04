<?php

	include "scripts/library.php";  
	
	$cms = new CMS();
	$display = new Display();
	$members = new Members();
	$products = new Products();
	$blog = new Blog();
	$store = new Store();
	
	$sid = session_id();
	
	if($_POST['status'] == "submit"){
		header( "Location: $_SERVER[PHP_SELF]"); 
	}
	
	echo $display->startTemplate();

	if(isset($_GET['action']))
		$action = $_GET['action'];
	if(isset($_GET['pid']))
		$pid = $_GET['pid'];
	if(isset($_POST['on0']))
		$size = $_POST['on0'];	
	
	if(isset($_POST['qty']))
		$qty = $_POST['qty'];
	else	
		$qty = 1;
		
	echo $store->showCart($sid);
		
	switch ($action) {
		case "addTo";
			echo $store->addToCart($pid, $size, $qty);
			break;	
	}

	echo $display->endTemplate();

?>