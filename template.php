<?php 
	include "rushcms/scripts/library.php";  
	
	$cms = new CMS();
	$display = new Display();
	$members = new Members();
	$products = new Products();
	$blog = new Blog();
	
	$self = $_SERVER['PHP_SELF'];
	
	echo $display->startTemplate();
	
	try{
		
		
		
	}
	
	catch (Exception $m){ 
		echo $m->getMessage();
		echo $display->endTemplate();
		exit; 
	}
	
	echo $display->endTemplate();
	
?>