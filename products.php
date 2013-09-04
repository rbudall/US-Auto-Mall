<?php 
	include "rushcms/scripts/library.php";  
	
	$cms = new CMS();
	$display = new Display();
	$members = new Members();
	$products = new Products();
	
	echo $display->startTemplate();
	
	try{
		echo $products->showProducts();
	}

	catch (Exception $m){ 
		echo "<br /><br />".$m->getMessage();
		echo $display->endTemplate();
		exit; 
	}
	
	echo $display->endTemplate();
?>