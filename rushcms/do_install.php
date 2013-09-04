<?php
	include "scripts/library.php";
	
	$cms = new CMS();
	$display = new Display();
	
	echo $display->showHeader();
			
		echo $display->showBanner();
				
			echo $display->startWrapper();
					
				echo $display->startWrap();
					echo $display->showGreeting("<h3 align='center'>Welcome to the RUSH Content Management System (RCMS)</h3>");
				echo $display->endWrap();
				
				echo $display->startWrap();	
	
				echo "Installation Successfull! <br /> Thanks for using the RUSH Content Management System. <br /><br /> 
						At this point, you may want to delete the 'install.php' file from your server directory. <br />";

				echo $display->endWrap();	

		echo $display->endWrapper();
	
	echo $display->showFooter(); 


?> 
