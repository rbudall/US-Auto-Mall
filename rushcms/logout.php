<?php

	include "scripts/library.php";	//Load all the functions	
	
	try{
		$members = new Members();
		$display = new Display();
		$cms = new CMS();
		
		if($cms->check_user()){
			$members->logout();
			echo 	$display->startTemplate("index");
													
				echo "<div align='center'>You have been logged out.</div><br /><br />";			
				echo $display->showLogin();
			echo	$display->endTemplate();
			exit;
		}  else {
			echo 	$display->startTemplate("index");							
				echo $display->showLogin();
			echo	$display->endTemplate();
			exit;
		}
	}
	
	catch (Exception $m){
			echo "Error".$m->getMessage();
		echo	$display->endTemplate();
		exit;
	}
	
	catch (Exception $e){
		echo 	$display->startTemplate("index");	
			echo "Error".$e->getMessage();
		echo	$display->endTemplate();
		exit;
	}
?> 