<?php
class Members extends CMS{
			
			//Define Member variables
			var $fname,
				$lname,
				$username,
				$password,
				$email;		
			
			//Constructor
			function Members(){

			}
			
			public function register($fname, $lname, $username, $password, $email){
			//Register new user into db
				
				//Capitalize first letter of name
				$Fname = ucfirst(strtolower($fname));	
				$Lname = ucfirst(strtolower($lname));
			
				//Connect to db
				$db = new Database();
				$conn = $db->connect();
			
				//Check to see if username is unique
				$result = $conn->query("SELECT * FROM ".$this->getDBTable('members')." WHERE username = '$username' OR email = '$email'");
					
				if(!$result){
					throw new Exception ('Registration could not be completed. Query could not be executed.');
				}
				
				if($result->num_rows > 0){
					throw new Exception ('The username or email you entered is already taken. Please go back and try again.'); 
				}
				
				//If ok, put in db
				$result = $conn->query("INSERT INTO ".$this->getDBTable('members')." (`id`,`group`,`fname`,`lname`,`username`,`password`,`email`) 
									  	VALUES (NULL,'0','$Fname','$Lname','$username',SHA1('$password'),'$email')");
				
				if(!$result){
					throw new Exception('Could not register you into the database at this time. Please try again later.');
				}
				
				return true;
			}
			
			public function login($username, $password){
			//Check username and password with db
				
				//Connect to db
				$db = new Database();
				$conn = $db->connect();
				
				//Check if username is unique
				$result = $conn->query("SELECT * FROM ".$this->getDBTable('members')." WHERE username = '$username' AND password = SHA1('$password') ");	
				
				if(!$result){
					throw new Exception('Could not log you in. Error executing query.');
					return false;
				}
			
				if($result->num_rows > 0){
				//If they are in the database, register the session id
					$row = $result->fetch_array();
					$username = $row['username'];
					$_SESSION['valid_user'] = $username;
					$sid = session_id();
					$result = $conn->query("UPDATE ".$this->getDBTable('members')." SET logged_in = NOW(), sid = '$sid' WHERE username = '$username'");
				} else {
					throw new Exception('Could not log you in. Username or Password Incorrect. <br /><br /> Please try again.');
					return false;
				}
			
				return true;
			}
			
			public function logout(){
			//Log out the current user
			
				//Store to test if they were logged in
				$old_user = $_SESSION['valid_user'];
				
				$db = new Database();
				$conn = $db->connect();
				
				$result = $conn->query("UPDATE ".$this->getDBTable('members')." SET logged_out = NOW() WHERE username = '$old_user'");
				
				$_SESSION = array(); 					// Clear all session variables
				$result_dest = session_destroy();		// Destroy session
			
				if(!empty($old_user)){
					if($result_dest){
					//If they are now logged out
						return true;
					} else {
					//They could not be logged out
						throw new Exception('You could not be logged out. Please try again.');
						return false;
					}
				} else {
				//If they weren't logged in but came to this page somehow
					throw new Exception('You were not logged in, and so have not been logged out.');
					return false;
				}
				
				return true;
			}
			
			public function change_password($username, $old_password, $new_password){
	
				$db = new Database();
				$conn = $db->connect();
				$result = $conn->query("SELECT * FROM ".$this->getDBTable('members')." WHERE username = '$username' AND password = SHA1('$old_password') ");	

				if($result->num_rows <= 0){
					throw new Exception("The old password you entered is incorrect. Your password could not be changed. <br /><br /> Please try again. <br />");
				} else {
					$result = $conn->query("UPDATE ".$this->getDBTable('members')." SET password = SHA1('$new_password') WHERE username = '$username'");
		
					if(!$result){
						throw new Exception('Password could not be changed.');
					}
				}
	
				return true;
			}
			
			public function change_email($username, $old_email, $new_email){
					$db = new Database();
					$conn = $db->connect();
					$result = $conn->query("SELECT * FROM ".$this->getDBTable('members')." WHERE username = '$username'");
					$row = $result->fetch_array();
					if($row['email'] != $old_email){
						throw new Exception('Old E-Mail is incorrect. E-Mail could not be changed.');
					}
					
					$result = $conn->query("SELECT * FROM ".$this->getDBTable('members')." WHERE email = '$new_email'");
					if($result->num_rows > 0){
						throw new Exception ('The New E-mail address you entered is already in use. Please try another e-mail.');
					}
					
					$result = $conn->query("UPDATE ".$this->getDBTable('members')." SET email = '$new_email' WHERE username = '$username'");
				
					if(!$result){
						throw new Exception('E-Mail could not be changed.');
					}
			
				return true;
			}
		
		    //Directory for dictionary is "/testserver/scripts/dict/words/english.3"		
			public function get_random_word($min_length, $max_length){
			
				// generate a random word
				$word = '';
			
				// remember to change this path to suit your system
				$dictionary = $_SERVER['DOCUMENT_ROOT'].'rushcms/scripts/dict/words/english.3';  // the ispell dictionary
				$fp = @fopen($dictionary, 'r');
				if(!$fp){
					return false; 
				}
				
				$size = filesize($dictionary);
			
				// go to a random location in dictionary
				srand ((double) microtime() * 1000000);
				$rand_location = rand(0, $size);
				fseek($fp, $rand_location);
			
				// get the next whole word of the right length in the file
				while (strlen($word)< $min_length || strlen($word)>$max_length || strstr($word, "'")){  
					if (feof($fp)){   
						fseek($fp, 0);        // if at end, go to start
					}
					
					$word = fgets($fp, 80);  // skip first word as it could be partial
					$word = fgets($fp, 80);  // the potential password
				};
				$word=trim($word); // trim the trailing \n from fgets
				return $word;  
			}
			
			public function reset_password($email){
			 
			// get a random dictionary word b/w 6 and 13 chars in length
				$new_password = $this->get_random_word(6, 13);
			  
				if($new_password==false){
					throw new Exception('Could not generate new password.');
				}
			  
			  // add a number  between 0 and 999 to it
			  // to make it a slightly better password
				srand ((double) microtime() * 1000000);
				$rand_number = rand(0, 999); 
				$new_password .= $rand_number;
			 
			  // set user's password to this in database or return false
				$db = new Database();
				$conn = $db->connect();
				$result = $conn->query( "UPDATE ".$this->getDBTable('members')." SET password = SHA1('$new_password') WHERE email = '$email'");
				
				if (!$result){
					throw new Exception('Could not change password.');  // not changed
				} else {
					return $new_password;  // changed successfully  
				}
			}
			
			//New password notification message inside...
			public function notify_password($email, $password){
				$db = new Database();
				$conn = $db->connect();
				$result = $conn->query("SELECT * FROM ".$this->getDBTable('members')." WHERE email = '$email'");
			
				if (!$result){
				  throw new Exception('Could not find email address.');  
				} elseif($result->num_rows==0){
					throw new Exception('Could not find email address.');   // email not in db
				} else {
					$row = $result->fetch_array();
					$username = $row['username'];
					$from = "From: Support@".preg_replace('/\s+/','',$this->getSiteVariable('SITENAME')).".com \r\n";
					$mesg ="$username, \r\n\r\n " 
						  ."Your password has been changed to: \r\n\r\n"
						  ."$password \r\n\r\n"
						  ."Please change it next time you log in. \r\n\r\n"
						  ."You can goto http://".$this->getSiteVariable('SITE_URL')."/members.php to login.\r\n\r\n"
						  ."This is an automated response so you do not need to reply.\r\n"
						  .$this->getSiteVariable('SITENAME')." Support Team";
			
				  
				  
				  if (mail($email, $this->getSiteVariable('SITENAME').' Login Information', $mesg, $from))
					return true;      
				  else
					throw new Exception('Could not send email.');
				}
			}
			
			public function showMembersList(){ 

				$db = new Database();
				$display = new Display();
				
				$rowsPerPage = 10;
				$pageNum = 1;
				$sort = '';
				$sort_type = 'desc';
				$order = "ORDER BY id desc";
				$self = $_SERVER['PHP_SELF'];
				$nav  = '';
				$search = '';
				
				$conn = $db->connect();
				$tablename = "members";
				$Tablename = ucfirst($tablename);	//Uppercase first letter of table
				
				// if $_GET['page'] defined, use it as page number
				if(isset($_GET['pg']))
					$pageNum = $_GET['pg'];
					
				if(isset($_GET['action']))
					$action = $_GET['action'];
				
				if(isset($_GET['id']))
					$id = $_GET['id'];
				
				if(isset($_GET['do']))
					$do = $_GET['do'];
					
				if(isset($_GET['index']))
					$index = $_GET['index'];	
				
				$output .= $display->listTable($tablename);
				
				//Lets add a post to the database
				/*if($do == "add$Tablename"){
					//If producted was just added...
					if(isset($_GET['status'])){
						$status = $_GET['status'];
						$output .= 'Post Added';
						return $output;
					}
					
					if(isset($_GET['submit'])){
						$submit = $_GET['submit'];
				
						$v_title = $_POST['title'];
						$v_content = $_POST['content'];
						$v_category = $_POST['category'];
						
						if(empty($v_title)){
							$v_title = "&nbsp;";
						}
						
						if(isset($_POST['addPosts_submit'])){
							if(!empty($v_content)){
							
								
								$result = $conn->query("INSERT INTO ".$this->getDBTable($tablename)." (id, category, author, title, content, time) VALUES (NULL, '$v_category', '$_SESSION[valid_user]', '$v_title','$v_content', NOW())");
								
								if($result){
									$output .= "<meta http-equiv='refresh' content='0;URL=$self?action=$action&do=$do&status=done&pg=$pageNum$sort_link'>";	
									return $output;
								} else {
									throw new Exception('Changes to the post could not be saved at this time.');
								}
							} elseif(empty($v_content)) {
								$output .= "A Post cannot be added with no content. Please try again.";
							}
					
						}
						
						if(isset($_POST['addPosts_cancel'])){
							$output .= "Post was not added.";
							return $output;	
						}
							
					}
					
					$title = $row['v_title'];
						
					$output .= "
						<div id='addPosts' align='left'><div id='content'>
							<form name='addPosts' method='post' action='$self?action=$action&do=$do&submit=submitPost&pg=$pageNum$sort_link'>
								Category: <input name='category' type='text' value=\"$category\"  /><br />
								Title: <input name='title' type='text' size='100%' value=\"$title\"  /><br />
								Content: <textarea class='ckeditor' id='editor1' name='content' cols='80' rows='10'></textarea><br />
								<input align='middle' name='addPosts_submit' type='submit' value='Add Post' />
								<input align='middle' name='addPosts_cancel' type='submit' value='Cancel' />
							</form></div>
						</div>";
				}
				
				//Lets edit a product in the database
				if($do == "edit$Tablename"){
					
					//If the product was edited successfully...
					if(isset($_GET['id'])){
						$id = $_GET['id'];
						
						$query   = "SELECT COUNT(*) AS numrows FROM ".$this->getDBTable($tablename)." WHERE id='$id' LIMIT 1";
						$result  = $conn->query($query);
						$row     = $result->fetch_array(MYSQL_ASSOC);
						$numrows = $row['numrows'];
						
						if($numrows < 1)
							echo "<meta http-equiv='refresh' content=\"0;url=$self?action=$action&do=delete$uctable&status=done&pg=$pageNum$sort_link\">";
					}
					
					if(isset($_GET['status'])){
						$status = $_GET['status'];
						$output .= 'Post Edited';
					}
					
					if(isset($_GET['submit'])){
						$submit = $_GET['submit'];
				
						$v_title = $_POST['title'];
						$v_content = $_POST['content'];	
						$v_category = $_POST['category'];
						
						if(empty($v_title)){
							$v_title = "&nbsp;";
						}		
						
						if(isset($_POST['editPost_submit'])){
							if(!empty($v_content)){
							
								$update = $conn->query("UPDATE ".$this->getDBTable($tablename)." SET category='$v_category', title='$v_title', content='$v_content' WHERE id='$id' LIMIT 1");
								 
								if($update){
									$output .= "<meta http-equiv='refresh' content='0;URL=$self?action=$action&do=$do&status=done&id=$id&pg=$pageNum$sort_link'>";
								} else {
									throw new Exception('Changes to the post could not be saved at this time.');
								}
							} elseif(empty($v_content)) {
								$output .= "A Post cannot be edited with no content. Please try again.";
							}
					
						}
						
						if(isset($_POST['editPost_cancel'])){
							$output .= "Post was not edited.";
							return $output;	
						}
							
					}
					
					if(!empty($id)){
							
						$result = $conn->query("SELECT * FROM ".$this->getDBTable($tablename)." WHERE id = '$id'"); 
						$row = $result->fetch_array();
						
						$title = $row['title'];
						$category = $row['category'];
						
						$output .= "
							<div id='editPosts' align='left'></div><div id='content'>
								<form name='editPosts' method='post' action='$self?action=$action&do=$do&id=$row[id]&submit=submitPost&pg=$pageNum$sort_link'>
									Post # $row[id] <br />
									Date: ".date('F d Y', strtotime($row['time']))."<br />
									Time: ".date('g:i A', strtotime($row['time']))."<br />
									Author: $row[author]<br />
									Category: <input name='category' type='text' value=\"$category\"  /><br />
									Title: <input name='title' type='text' size='100%' value=\"$title\"  /><br />
									Content: <textarea class='ckeditor' id='editor1' name='content' cols='80' rows='10'>".$row['content']."</textarea><br />
									<input align='middle' name='editPost_submit' type='submit' value='Edit Post' />
									<input align='middle' name='editPost_cancel' type='submit' value='Cancel' />
								</form></div>
							</div>";
							
							$result  = $conn->query("SELECT COUNT(*) AS numrows FROM ".$this->getDBTable($tablename)." ORDER BY `id` DESC");
							$row     = $result->fetch_array(MYSQL_ASSOC);
							$numrows = $row['numrows'];
							
							$result2  = $conn->query("SELECT id FROM ".$this->getDBTable($tablename)." ORDER BY `id` DESC ");
					
							$my_index = ($index - $offset) + $offset;
		
							if($offset == 0)
								$my_index = $index;
							
							$rowIndex = 1;
							
							while($row = $result2->fetch_row()){
		
								if($rowIndex == ($my_index - 1))
									$prevID = $row[0];
								
								if($rowIndex == ($my_index + 1))
									$nextID = $row[0];		
		
								if($my_index == (($rowsPerPage*($pageNum-1)) + 1)){		
									$pg = $pageNum - 1;
			
									$prevRow = $my_index - 1;
									$prev  = " <span class='link'><a href=\"$self?action=$action&do=edit$Tablename&id=$prevID&index=$prevRow&pg=$pg$sort_link\"> Prev </a></span> ";
								} elseif ($index > 1){
									$pg = $pageNum;
									$prevRow  = $index - 1;
									$prev  = " <span class='link'><a href=\"$self?action=$action&do=edit$Tablename&id=$prevID&index=$prevRow&pg=$pg$sort_link\"> Prev </a></span> ";
								} else  {
									$prev  = '&nbsp;'; // we're on page one, don't print previous link
								}
							
								if(is_int($index/$rowsPerPage) && ($index + $offset) != $numrows ){		
									$pg = $pageNum + 1;
									$nextRow = $index + 1;
									$next = " <span class='link'><a href=\"$self?action=$action&do=edit$Tablename&id=$nextID&index=$nextRow&pg=$pg$sort_link\"> Next </a></span> ";
								} elseif ($index < $numrows){
									$pg = $pageNum;
									$nextRow = $index + 1;
									$next = " <span class='link'><a href=\"$self?action=$action&do=edit$Tablename&id=$nextID&index=$nextRow&pg=$pg$sort_link\"> Next </a></span> ";
								} else  {
									$next = '&nbsp;'; // we're on the last page, don't print next link
								}
								
								$rowIndex++;
							}
							$output .= "
							<div class='products_nav'>
								<table cols='5' cellpadding='0' cellspacing='0'>
									<tr >		
										<td align='center' width='33%' style='color:#fff'>&nbsp;".$prev."&nbsp;</td>
										<td align='center' width='33%' style='color:#000'>&nbsp;".$nav."&nbsp;</td>
										<td align='center' width='33%' style='color:#fff'>&nbsp;".$next."&nbsp;</td>
									</tr>
								</table>
							</div>";
							
					}
				}
				*/
				//Lets delete a post
				if($do == "delete$Tablename"){
		
					if(isset($_GET['status'])){
						$output .= 'Member Deleted';
						return $output;
					}
					
					if(isset($_GET['id'])){
						$id = $_GET['id'];
						
						$result = $conn->query("DELETE FROM ".$this->getDBTable($tablename)." WHERE id = '$id' LIMIT 1");															
						if($result){
							$output .= "<meta http-equiv='refresh' content='0;URL=$self?action=$action&do=$do&status=done&id=$id&pg=$pageNum$sort_link'>";
						} else {
							throw new Exception("Changes to the post could not be saved at this time.");
						}
					} else {
						$output .= "The member was not deleted";
						return $output;
					}
				}
				
				return $output;
			}
	
								
		}//Close Members class
?>