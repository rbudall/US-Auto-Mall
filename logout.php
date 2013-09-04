<?php 
	include "rushcms/scripts/library.php";  
	
	$cms = new CMS();
	$display = new Display();
	$members = new Members();
	$products = new Products();
	
	echo $display->startTemplate();
	
	try{		
		if($cms->check_user()){
			$members->logout();								
			echo "<div align='center'>You have been logged out.</div><br /><br />";			
			echo $display->showLogin();	
		}  else 
			echo $display->showLogin();
		
	}

	catch (Exception $m){ 
		echo "<br /><br />".$m->getMessage();
		echo $display->endTemplate();
		exit; 
	}
		
	catch (Exception $e){	
		echo "Error ".$e->getMessage();
		echo $display->endTemplate();
		exit;
	}
	
	echo $display->endTemplate();
?> 