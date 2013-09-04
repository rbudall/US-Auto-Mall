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
				
				try{

					if(isset($_POST['email'])){
						$email = $_POST['email'];
						$password = $members->reset_password($email);
						$members->notify_password($email, $password);
						echo 'Password Reset! <br /><br />Your new password has been sent to your email.';
					} else {
						echo $display->showResetPass(); 
					}	
				}
				
				catch (Exception $e){
					echo 'Error'.$e->getMessage();
				}
				
			echo $display->endWrap();	

		echo $display->endWrapper();
	
	echo $display->showFooter(); 

?>