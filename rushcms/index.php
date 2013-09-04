<?php 
	include "scripts/library.php"; 
	
	$cms = new CMS();
	$display = new Display();
	$members = new Members();

	echo $display->showHeader();
		
	echo $display->showBanner();
			
		echo $display->startWrapper();
				
			echo $display->startWrap();
				echo $display->showGreeting();
			echo $display->endWrap();
				
			echo $display->showNav();
			
			echo $display->startWrap();	
 				
				echo $display->startColumn('22%');
					echo "<span class='height_overlay' style='width:22%'></span><div id='content'>";
					echo $display->showLoginSmall();
					echo "</div>";
				echo $display->endColumn();
				
				echo $display->startColumn('78%');
 					echo "<span id='height'>";
						echo $display->showBlog();
					echo "</span>";
 				echo $display->endColumn();
										
			echo $display->endWrap();	

		echo $display->endWrapper();
	
	echo $display->showFooter(); 

?>