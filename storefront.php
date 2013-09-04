<?php 
	include "rushcms/scripts/library.php";  
	
	$cms = new CMS();
	$db = new Database();
	$display = new Display();
	$members = new Members();
	$products = new Products();
	
	$conn = $db->connect();
	
	//Get the ID of the current user...
	$valid_user = $_SESSION['valid_user'];
	$userID_query = $conn->query("SELECT * FROM ".$cms->getDBTable("members")." WHERE username = '$valid_user' LIMIT 1");		
	$userID_row = $userID_query->fetch_array();
	$userID = $userID_row[0];
	
	echo $display->startTemplate();
	
	try{
		if($userID)
			echo $products->showProducts(null,null, $userID);
		else
			echo $display->showLogin();
	}

	catch (Exception $m){ 
		echo "<br /><br />".$m->getMessage();
		echo $display->endTemplate();
		exit; 
	}
	
	echo $display->endTemplate();
?>