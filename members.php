<?php 
	include "rushcms/scripts/library.php";  
	
	$cms = new CMS();
	$display = new Display();
	$members = new Members();
	$products = new Products();
	$blog = new Blog();
	
	$self = $_SERVER['PHP_SELF'];
	
	try{
		
		if($_POST['login']){
		//If the user just submitted login form...
		
			
			
			$username = $_POST['username'];
			$password = $_POST['password'];
						
			if ($members->login($username, $password)){
			//If login passed

				if($cms->check_user()){
				//Check to see if user is logged in
				
					if ($cms->check_admin()){
					//If user is logged in and user is an admin...	
						//echo $display->showAdminNav();
					} else {						 
						//echo $display->showMembersNav();
					}
					
					echo $display->startMembersPage();
					
					echo "<br /><br /><div id='mp_content'>";
					echo "<h3> Members Page </h3>"; 		
					echo '	 
							Welcome '.$_SESSION['valid_user'].'! <br /><br />
								
							You are now logged into the members only page.';
					echo "<div class='post_min_height'></div>";		
							
	
					if(isset($_GET['action'])){
						$action = $_GET['action'];
						$do = $_GET['do'];
						
						switch ($action) {
							case 'chgPass';
								echo $display->showChgPass();
								break;
							case 'chgEmail';
								echo $display->showChgEmail();
								break;
							case 'manageProducts';
								echo $products->showProductsManager();
								break;	
						}
						 
						if ($cms->check_admin()){
						//If user is an administrator								
							switch ($action) {
								case 'listMembers';
									echo $members->showMembersList();
									break;
								case 'sendEmail';
									echo $display->showSendEmail();
									break;
								case 'managePosts';
									echo $blog->showBlogManager();
									break;
								/*case 'manageProducts';
									echo $products->showProductsManager();
									break;*/
								case 'manageCategories';
									echo $products->showCategoriesManager();
									break;		
							}
						} 
					}		 	
	
					if(isset($_POST['chgpass'])){
						if($members->change_password($_SESSION['valid_user'], $_POST['old_password'], $_POST['new_password'])){
							echo 'Password Changed';
						}
					} else if(isset($_POST['subchgemail'])){
						if($members->change_email($_SESSION['valid_user'], $_POST['old_email'], $_POST['new_email'])){
							echo 'E-Mail Changed';
						}
					} else if($var == true){
						echo 'E-Mail Sent Successfully!';
					}
					echo "<div class='post_min_height'></div>";
					echo "</div>";
	
				} else {
						
					echo 'Please login to enter the members section. <br />';
					
					echo $display->showLogin();
				}	
			} 
		
		} else if($cms->check_user()){
		//Check if user is logged in
			
			
				
			if ($cms->check_admin()){
				//echo $display->showAdminNav();
			} else {					
				//echo $display->showMembersNav();
			}
			
			echo $display->startMembersPage();
			
			echo "<br /><br /><div id='mp_content'>";
			echo "<h3> Members Page </h3>";
			 					
			echo '	 
					
					Welcome '.$_SESSION['valid_user'].'!<br /><br /> 
					
					You are now logged into the members only page.';
			//echo "<div class='post_min_height'></div>";		
					
												
			if(isset($_GET['action'])){
				$action = $_GET['action'];
				$do = $_GET['do'];
															
				switch ($action) {
					case 'chgPass';
						echo $display->showChgPass();
						break;
					case 'chgEmail';
						echo $display->showChgEmail();
						break;
					case 'manageProducts';
						echo $products->showProductsManager();
						break;	
				}
						 
				if ($cms->check_admin()){
				//If user is an administrator								
					switch ($action) {
						case 'listMembers';
							echo $members->showMembersList();
							break;
						case 'sendEmail';
							echo $display->showSendEmail();
							break;
						case 'managePosts';
							echo $blog->showBlogManager();
							break;
						/*case 'manageProducts';
							echo $products->showProductsManager();
							break;*/
						case 'manageCategories';
							echo $products->showCategoriesManager();
							break;		
					}
				} 
			}		 	

			if(isset($_POST['chgpass'])){
				if($members->change_password($_SESSION['valid_user'], $_POST['old_password'], $_POST['new_password']))
					echo 'Password Changed';
			}  
			
			if(isset($_POST['subchgemail'])){
				if($members->change_email($_SESSION['valid_user'], $_POST['old_email'], $_POST['new_email']))
					echo 'E-Mail Changed';
			} 
			
			if($var == true)
				echo 'E-Mail Sent Successfully!';
			echo "<div class='post_min_height'></div>";
			echo "</div>";				
		} 
		
		if(($cms->check_user() == false) && !isset($_POST['sublogin'])){
			//If user is not logged in and did not try to log in
			echo $display->startTemplate();
			throw new Exception ($display->showLogin());
		} 
		
		if(isset($_POST['email'])){
			echo $display->startTemplate();
			
			$subject = $_POST['subject'];
			$message = $_POST['message'];
			$to = $_POST['recipient'];
				
			if(empty($to)){
				if($cms->send_email($subject, $message)){
					$var = true;
				} else {
					throw new Exception ("An error occured when trying to send your email. <br /> The message was not sent. <br /> Please try again.");
				}
			} else {
				if($cms->send_email($subject, $message, $to)){
					$var = true;
				} else {
					throw new Exception ("An error occured when trying to send your email. <br /> The message was not sent. <br /> Please try again.");
				}
			}
		}
		
	}
	
	catch (Exception $m){ 
		echo $m->getMessage();
		echo $display->endTemplate();
		exit; 
	}
	
	echo $display->endTemplate();
	
?>