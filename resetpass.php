<?php 
	include "rushcms/scripts/library.php"; 
	
	$cms = new CMS();
	$display = new Display();
	$members = new Members();
	$products = new Products();
	
	echo $display->startTemplate();
	
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

	catch (Exception $m){ 
		echo "<br /><br />".$m->getMessage();
		echo $display->endTemplate();
		exit; 
	}
	
	echo $display->endTemplate();

?>