<?php 

	class Display extends CMS{
			
			//Define Variables
			var $var;
			
			//Constructor
			public function Display(){
				//Code goes here	
			}
			
			public function startHtml(){
				return "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
						<html xmlns='http://www.w3.org/1999/xhtml'>";
			}
			
			public function endHtml(){
				return "	</div>
						</body>
					</html>";
			}
			 
			public function showHeader(){
				
				
				$output = "<head>
						<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
						<meta name=\"description\" content=\"Rashan Budall is a Visual Communicator with a love for Graphic Design, Web Design, and Motion Graphics. Enter to check out his work and more.\"  />
						<meta http-equiv=\"content-language\" content=\"en-us\" />
						<title>Rashan Budall: ".$this->curPageName()."</title>
							<link rel=\"shortcut icon\" href=\"images/favicon.ico\" />
							
							<link href=\"styles/styles.css\" type=\"text/css\" rel=\"stylesheet\" media=\"all\"  />
							
							<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
							<link href='http://fonts.googleapis.com/css?family=Playfair+Display+SC' rel='stylesheet' type='text/css'>
							<link href='http://fonts.googleapis.com/css?family=Parisienne' rel='stylesheet' type='text/css'>
							
							<script type='text/javascript' src='rushcms/ckeditor/ckeditor.js'></script>
							<script src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js\"></script>
							
							<script>
								(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
								(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
								m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
								})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
								
								ga('create', 'UA-42844217-1', 'rashanbudall.com');
								ga('send', 'pageview');
							</script>
							
						</head>
						
						<body>
							<div id=\"colorBand\"></div>
							<div id=\"wrapper\">
								<div id=\"header\">
									<h1>Rashan Budall: Blog</h1>
									<h2>
										<span class=\"color_punch\">|&nbsp;</span>  6150 SW 153 Court Road  
										<span class=\"color_punch\">&nbsp;|&nbsp;</span>  Miami, FL  33193  
										<span class=\"color_punch\">&nbsp;|&nbsp;</span>  (305) 283-6524  
										<span class=\"color_punch\">&nbsp;|&nbsp;</span>  <a href=\"mailto:ContactMe@RashanBudall.com\">ContactMe@RashanBudall.com</a>  
										<span class=\"color_punch\">&nbsp;|</span> 
									</h2>
								</div>";
								
				return $output;				
			}
			
			
			
			public function startMainContent(){
				return "<div id=\"content\">
							<h2>".ucfirst($this->curPageName())."</h2>
							<p>";
			}
			
			public function endMainContent(){
				return "	</p>
						</div>
						<div class=\"clear\"></div>";
			}
				
			
			public function showSideNav(){
				return "<div id=\"nav\">
							<ul>
								<li><a href=\"/\">HOME</a></li>
								<li><a href=\"/about.html\">ABOUT</a></li>
								<li><a href=\"/work.html\">WORK</a></li>
								<li><a href=\"/blog.php\">BLOG</a></li>            
								<li><a href=\"/contact.html\">CONTACT</a></li>
							</ul>
						</div>";
			}
			
			public function showAdminNav(){
				return "
						<div id='admin_nav'>
							<ul>
								<li><a href='members.php?action=managePosts' > Posts Manager </a></li>						
								<li><a href='members.php?action=listMembers' > Members List </a></li> 
								<li><a href='members.php?action=sendEmail' > Send E-mail </a></li> 
								<li><a href='members.php?action=chgPass' > Change Password </a></li>  
								<li><a href='members.php?action=chgEmail' > Change E-mail </a></li>  
								<li><a href='logout.php' > Logout </a></li>
							</ul>
						</div>";	
			}
			
			public function showMembersNav(){
				return "
						<div id='members_nav'>
						
							<a href='members.php?action=chgPass' > Change Password </a>  
							<a href='members.php?action=chgEmail' > Change E-mail </a> 
							<a href='logout.php' > Logout </a>
						
						</div>";	
			}
			
			public function showFooter(){
				return "<div id=\"footer\">
							<p>
								<span class=\"color_punch\">|&nbsp;</span> Copyright &copy; 2012 
								<span class=\"color_punch\">&nbsp;|&nbsp;</span> RashanBudall.com 
								<span class=\"color_punch\">&nbsp;|&nbsp;</span> All Rights Reservered
								<span class=\"color_punch\">&nbsp;|</span>
							</p>
						</div>";
			}
		
			public function showLogin(){

				return "
                	<div id='login'>
						<div id='form'>
							<form name='login' method='post' action='members.php'>
								
								<fieldset>
									<legend>Login Form</legend>
									<p><strong>Login</strong></p>                   	
									
									<p>
										<label for='username'>Username</label>
										<input id='username' name='username' type='text' value='Username' onfocus=\"if(this.value == 'Username') { this.value = ''; }\" onblur=\"if(this.value == ''){ this.value = 'Username';}\" />
										<label for='password'>Password</label>
										<input id='password' name='password' type='password' value='Password' onfocus=\"if(this.value == 'Password') { this.value = ''; }\" onblur=\"if(this.value == ''){ this.value = 'Password';}\" />
									</p>
									
									<p>
										<input name='login' type='submit' value='Login' />
									</p>
								
									<p>
										<a href='registration.php'>Not Registered</a> | 
										<a href='resetpass.php'>Forgot Password</a>  
								    </p>   	   	       
								</fieldset>
								<div class='clear'></div>
							</form>
						</div>
					</div>";
				}
				
			public function showLoginSmall(){
				
				$output = "<div id='loginSmall'>
								<p>Already Registered?</p>
								
								<form name='login' method='post' action='members.php'>
									
									<fieldset>
										<legend>Login Form</legend>                   	
										
										
										<div id='smallInput'>
											<p>
												<input id='user' name='user' type='text' value='Username' onfocus=\"if(this.value == 'Username') { this.value = ''; }\" onblur=\"if(this.value == ''){ this.value = 'Username';}\" />
												<input id='pass' name='pass' type='password' value='Password' onfocus=\"if(this.value == 'Password') { this.value = ''; }\" onblur=\"if(this.value == ''){ this.value = 'Password';}\" />
												<input name='login' type='submit' value='Login' />
											</p>
										</div>
									
										<p> 
											<a href='resetpass.php'>Forgot Password</a>  
										</p>   	   	       
									</fieldset>
									<div class='clear'></div>
								</form>
						</div>";
				
				
				return $output;
			}				
			
			public function showRegistration(){
				$output = $this->showLoginSmall();
				
				if(isset($_POST['register'])){
					$fName = $_POST['fName'];
					$lName = $_POST['lName'];
					$username = $_POST['username'];
					$password = $_POST['password'];
					$password2 = $_POST['password2'];
					$email = $_POST['email'];  
					$email2 = $_POST['email2'];
					
					
					$validate = new Validator();
					$members = new Members();
			
					$validate->fName($_POST['fName']);
					$validate->lName($_POST['lName']);
					$validate->password($_POST['password'],$_POST['password2']);
					$validate->email($_POST['email'],$_POST['email2']);
					
					if(!isset($validate->errors)){
						$members->register($fName, $lName, $username, $password, $email);
						$output = "<div id='registration' >
										<div id='reg_complete'>
											<p>Registration Completed</p>
											<br />
											<p><a href='members.php'>Continue onto the Members Section</a></p>
										</div>
									</div>";
						return $output;
					} 
				}
				if(!isset($fName)){ 
					$fName = 'First Name';
				}
				
				if(!isset($lName)){ 
					$lName = 'Last Name';
				}
		
				
				$output .= "                
       	   	 	  	<div id='registration' >";
					
                if(isset($validate->errors)){
					$output .= "<p style='color:red' >".$validate->errors."</p>";	
				}   	
				
				$output .= "
						<br />				
						<div id='form'>
                    		<form name='register' method='post' action='".$self."?action=register'>
								<fieldset>  
						  	
									<legend>Registration Form</legend> 
									
									<p>
										<label for='fName'>Name: </label>
										<input id='fName' type='text' name='fName' value='".$fName."' onfocus=\"if (value == 'First Name'){value=''}\" onblur=\"if (value == ''){value='First Name'}\" />
										<input id='lName' type='text' name='lName' value='".$lName."' onfocus=\"if (value == 'Last Name'){value=''}\" onblur=\"if (value == ''){value='Last Name'}\" />
									</p>
									<p>	
										<label for='username'>Username: </label>
										<input id='username' type='text' name='username' value='".$username."' />
									</p>
									<p>
										<label for='password'>Password: </label>
										<input id='password' type='password' name='password' />
									</p>
									<p>	
										<label for='password2'>Confirm Password: </label>
										<input id='password2' type='password' name='password2' />
									</p>
									<p>
										<label for='email'>Email Address: </label>
										<input id='email' type='text' name='email' value='".$email."' />
									</p>
									<p>	
										<label for='email2'>Confirm Email Address: </label>
										<input id='email2' type='text' name='email2' value='".$email2."' />
									</p>
										
									<p>
										<input type='reset' name='reset' value='Reset' />
										<input type='submit' name='register' value='Register' />
									</p>
									
								</fieldset>
							</form>
						</div>
					</div>";
				
                return $output;
			}	
			
			public function showResetPass(){
				return "
							<div id='resetPass'>
								<h1>Forgot Password</h1>
			
									<p>Please enter your email address to reset your password.</p>
									
									<form name='rstpass' method='post' action='".$_SERVER['PHP_SELF']."' onsubmit='return checkRstPassForm();'>
										<table>
											<tr>
												<td align='right'><strong>E-Mail:</strong></td><td><input name='email' type='text' size='35' value=''></td>		
											</tr>
											<tr>
												<td align='center' colspan='2'><input name='rstPass' type='submit' value='Submit'></td>		
											</tr>
										</table>
									</form>
								</div>";	
			}
			
			public function neat_trim($str, $n, $delim='...') { 
			   $len = strlen($str); 
			   if ($len > $n) { 
				   preg_match('/(.{' . $n . '}.*?)\b/', $str, $matches); 
				   return rtrim($matches[1]) . $delim; 
			   } 
			   else { 
				   return $str; 
			   } 
			} 

			public function listTable($var){			
				$rowsPerPage = 10;
				$pageNum = 1;
				$sort = '';
				$sort_type = 'desc';
				$order = "ORDER BY id desc";
				$self = $_SERVER['PHP_SELF'];
				$nav  = '';
				$search = '';
				
				$display = new Display();				
				$db = new Database();
				$conn = $db->connect();	

				$getField = $conn->query("SELECT * FROM ".$this->getDBTable($var));
				$numfields = $getField->field_count;
				$width = 100/$numfields;
				$tablesize = 700;
				// if $_GET['page'] defined, use it as page number
				if(isset($_GET['pg'])){
					$pageNum = $_GET['pg'];
				}
				if(isset($_GET['action'])){
					$action = $_GET['action'];
				}
				
				//If $_GET['sort'] defined, sort results
				if(isset($_GET['sort'])){
					$sort = $_GET['sort'];
					$sort_type = $_GET['type'];
					$sort_link = "&sort=$sort&type=$sort_type";
					
					if ($sort_type == "asc"){
						$sort_type = "desc";
						$order = "ORDER BY $sort $sort_type";
					} else if ($sort_type == "desc") {
						$sort_type = "asc";
						$order = "ORDER BY $sort $sort_type";
					} 
				}
			
				$offset = ($pageNum - 1) * $rowsPerPage;
			
				//Query the database		
								
				$sql = "SELECT * FROM ".$this->getDBTable($var)." $order $search LIMIT $offset, $rowsPerPage";
				$result = $conn->query($sql);
				
				$output .= "<div>";
				$output .= "
							<table class='list' cols='".$numfields."' cellspacing='0' cellpadding='0px'  width='".$tablesize."'> 
								<div align='left'><img src='/testserver/images/buttons/add.jpg' onclick=\"window.location.href='$self?action=$action&do=addPost&pg=$pageNum$sort_link'\" alt='Add Post' style='cursor:pointer'></div>
								<tr style='background:url(/testserver/images/backgrounds/nav_01.jpg)'>";
				
				for ($i=0; $i<$numfields ; $i++ ) {
					$fetch_field = $getField->fetch_field();
					$row_title = $fetch_field->name;
					
					if(($row_title == 'password')||($row_title == 'sid')||($row_title == 'group')||($row_title == 'profile_pic')||($row_title == 'frontcover')||($row_title == 'backcover')){
							$output .= "";
					} else if($row_title == 'fname'){
						$output .= "<td colspan='1' width='".$width."%' align='center'>
										<strong>
											<a href='$self?action=$action&pg=$pageNum&sort=".$row_title."&type=$sort_type'>First Name</a>
										</strong>
									</td>";
					} else if($row_title == 'lname'){
						$output .= "<td colspan='1' width='".$width."%' align='center'>
										<strong>
											<a href='$self?action=$action&pg=$pageNum&sort=".$row_title."&type=$sort_type'>Last Name</a>
										</strong>
									</td>";
					} else {	
				   		$output .= "<td colspan='1' width='".$width."%' align='center'>
										<strong>
											<a href='$self?action=$action&pg=$pageNum&sort=".$row_title."&type=$sort_type'>".ucfirst($row_title)."</a>
										</strong>
									</td>";
					}	
					
				}
				$output .= "<td align='center'><span colspan='1' class='link'>&nbsp;</span></td>";
				$num = $result->num_rows;

				while ($row = $result->fetch_array()){	
					
					$output .= "<tr id='$row[id]' onclick='window.location=\"$self?action=$action&do=editPost&postID=$row[id]&pg=$pageNum$sort_link\";' onmouseover='document.getElementById(\"$row[id]\").style.backgroundColor=\"#FC6\";' onmouseout='document.getElementById(\"$row[id]\").style.backgroundColor=\"#CCC\";'>";
					$getField->field_seek(0);
					
					for ($i=0; $i<$numfields ; $i++ ) {
						
					   	$fetch_field = $getField->fetch_field();
						$row_title = $fetch_field->name;
						
						if(($row_title == 'password')||($row_title == 'sid')||($row_title == 'group')||($row_title == 'profile_pic')||($row_title == 'frontcover')||($row_title == 'backcover')){
							$output .= "";
						} else if(($row_title == 'logged_in') || ($row_title == 'logged_out')||($row_title == 'time')){
							$output .= "<td colspan='1' width='".$width."%' align='center'>".date('F d Y g:i A', strtotime($row[$row_title]))."</td>";
						} else if($row_title == 'content'){
							$output .= "<td colspan='1' width='".$width."%' align='center'>".$display->neat_trim(strip_tags($row[$row_title]), 25)."</td>";
						} else if($row_title == 'url'){
							$output .= "<td colspan='1' width='".$width."%' align='center'>".$display->neat_trim(strip_tags($row[$row_title]), 15)."</td>";
						} else {
							$output .= "<td colspan='1' width='".$width."%' align='center'>".$row[$row_title]."</td>";
						}

					}
					
					$output .= "<td><img src='/testserver/images/buttons/delete.jpg' onclick='if(confirm(\"Are you sure you want to delete this post?\")){window.location.href=\"$self?action=$action&do=deletePost&postID=$row[id]&pg=$pageNum$sort_link\"}' alt='Delete Post' style='cursor:pointer;'></td></tr>";
					
				}
				
				$output .= "</tr>";
				
				// how many rows we have in database
				$query   = "SELECT COUNT(*) AS numrows FROM ".$this->getDBTable($var)."";
				$result  = $conn->query($query);
				$row     = $result->fetch_array(MYSQL_ASSOC);
				$numrows = $row['numrows'];
			
				// how many pages we have when using paging?
				$maxPage = ceil($numrows/$rowsPerPage);
			
				for($page = 1; $page <= $maxPage; $page++){
				
					if ($page == $pageNum){
					  $nav .= " $page "; // no need to create a link to current page
					} else {
						$nav .= " <span ><a href=\"$self?action=$action&pg=$page$sort_link\">$page</a></span> ";
					} 
				}	
				
				if ($pageNum > 1){
					$page  = $pageNum - 1;
					$prev  = " <span ><a href=\"$self?action=$action&pg=$page$sort_link\"> Prev </a></span> ";
					$first = " <span ><a href=\"$self?action=$action&pg=1$sort_link\"> |<< </a></span> ";
				} else {
					$prev  = '&nbsp;'; // we're on page one, don't print previous link
					$first = '&nbsp;'; // nor the first page link
				}
			
				if ($pageNum < $maxPage){
					$page = $pageNum + 1;
					$next = " <span ><a href=\"$self?action=$action&pg=$page$sort_link\"> Next </a></span> ";
					$last = " <span ><a href=\"$self?action=$action&pg=$maxPage$sort_link\"> >>| </a></span> ";
				} else {
					$next = '&nbsp;'; // we're on the last page, don't print next link
					$last = '&nbsp;'; // nor the last page link
				}
			
				// print the navigation link 
				$output .= "</table><table width='".$tablesize."' cols='5' cellpadding='0' cellspacing='0'>
						<tr style='background: #333333;'>
							
									<td align='center' colspan='1' width='20%' style='color:#fff'>".$first."</td> 
									<td align='center' colspan='1' width='20%' style='color:#fff'>".$prev."</td>
									<td align='center' colspan='1' width='20%' style='color:#fff'>".$nav."</td>
									<td align='center' colspan='1' width='20%' style='color:#fff'>".$next."</td>
									<td align='center' colspan='1' width='20%' style='color:#fff'>".$last."</td>
								
					</tr>";
			
				$output .= "</table>
						<br />
						<br />
						
						<form name=\"srch\" method=\"post\" action=\"$self?action=$action&pg=$pageNum$sort_link\" onsubmit=\"return checkSearchForm();\">
						Seach for: <input type=\"text\" name=\"srch\" /> in 
						<Select NAME=\"field\">
						";

				$getField->field_seek(0);
				for ($i=0; $i<$numfields ; $i++ ) {
				   	$fetch_field = $getField->fetch_field();
					$row_title = $fetch_field->name;
					
					if(($row_title == 'password')||($row_title == 'sid')||($row_title == 'group')||($row_title == 'profile_pic')||($row_title == 'frontcover')||($row_title == 'backcover')){
						$output .= "";
					} else {
						$output .= "<Option VALUE=\"".$row_title."\">".$row_title."</option>";
					}
				}
			
				$output .= "</Select>
						<input type=\"hidden\" name=\"searching\" value=\"yes\" />
						<input type=\"submit\" name=\"submitsrch\" value=\"Search\"  />
						</form>
						<br />";
								
				if ($_POST['searching'] == "yes") {
					
			
					//If they did not enter a search term we give them an error 
					if ($_POST['srch'] == "") { 
						$output .= "<p>You forgot to enter a search term</p>"; 
						$output .= "</div>";
						return $output;
					} 
					
					$find = $_POST['srch'];
					
					// We preform a bit of filtering 
					$find = strip_tags($find); 
					$find = trim ($find); 
			
					//Now we search for our search term, in the field the user specified 
					$data = $conn->query("SELECT * FROM ".$this->getDBTable($var)." WHERE $_POST[field] LIKE '%$find%'"); 
					
					$output .= "<table class='list' cellspacing='0' width='".$tablesize."'>";
					$output .= "<tr style='background-image:url(images/backgrounds/nav_01.jpg)'><td align='center' colspan='".$numfields."'><strong>Results</strong></td></tr>"; 
					$output .= "<tr>";
					
					$num = $data->num_rows;
					
					$getField->field_seek(0);
					//And we display the results 
					while($row = $data->fetch_array()){ 
						
						$output .= "</tr><tr>";
						$getField->field_seek(0);
												
						for ($i=0; $i<$numfields ; $i++ ) {
					   		$fetch_field = $getField->fetch_field();
							$row_title = $fetch_field->name;
							
							if(($row_title == 'password')||($row_title == 'sid')||($row_title == 'group')||($row_title == 'profile_pic')||($row_title == 'frontcover')||($row_title == 'backcover')){
								$output .= "";
							} else if(($row_title == 'logged_in') || ($row_title == 'logged_out')||($row_title == 'time')){
								$output .= "<td colspan='1' width='".$width."%' align='center'>".date('F d Y g:i A', strtotime($row[$row_title]))."</td>";
							} else if($row_title == 'content'){
								$output .= "<td colspan='1' width='".$width."%' align='center'>".$display->neat_trim(strip_tags($row[$row_title]), 50)."</td>";
							} else if($row_title == 'url'){
								$output .= "<td colspan='1' width='".$width."%' align='center'>".$display->neat_trim(strip_tags($row[$row_title]), 15)."</td>";
							} else {
								$output .= "<td colspan='1' width='".$width."%' align='center'>".$row[$row_title]."</td>";
							}
							
						}
							
					} 
					
					$output .= "</tr>";
			
					//This counts the number or results - and if there wasn't any it gives them a little message explaining that 
					$anymatches=$data->num_rows; 
					if ($anymatches == 0) { 
						$output .= "<tr><td align='center' colspan='7'>Sorry, but we can not find an entry to match your query</td></tr><br><br>"; 
					} 
					
					//And we remind them what they searched for 
					$output .= "<br /><strong>Searched For:</strong> " .$find; 
					$output .= "</table>";
				}
				
				$output .= "</div>";
				return $output;
			}			
			
			public function showChgPass(){
				return "  	<h1>Change Password</h1>
                    
                    	<form name='chgpass' method='post' action=".$_SERVER['PHP_SELF']." onsubmit='return checkChgPassForm()'>
                            <table>
                                <tr>
                                    <td><strong>Old Password:</strong></td><td><input name='old_password' type='password' size='35' maxlength='75' value=''></td>
                                </tr>
                                <tr>
                                    <td><strong>New Password:</strong></td><td><input name='new_password' type='password' size='35' maxlength='75' value=''></td>		
                                </tr>
                                <tr>
                                    <td><strong>Re-Enter Password:</strong></td><td><input name='new_password2' type='password' size='35' maxlength='75' value=''></td>		
                                </tr>
                                <tr>
                                    <td align='center' colspan='2'><input name='chgpass' type='submit' size='35' maxlength='75' value='Submit'></td>		
                                </tr>
                        	</table>
                   		</form>
                   
                ";
			}	

			public function showChgEmail(){
				return "
                	<h1>Change E-Mail</h1>
                      <form name='chgemail' method='post' action='".$_SERVER['PHP_SELF']."' onsubmit='return checkChgEmailForm();'>
                        <table>
                          <tr>
                            <td><strong>Old E-Mail:</strong></td><td><input name='old_email' type='text' size='35' maxlength='75' value=''></td>
                            </tr>
                          <tr>
                            <td><strong>New E-Mail:</strong></td><td><input name='new_email' type='text' size='35' maxlength='75' value=''></td>		
                            </tr>
                          <tr>
                            <td><strong>Re-Enter E-Mail:</strong></td><td><input name='new_email2' type='text' size='35' maxlength='75' value=''></td>		
                            </tr>
                          <tr>
                            <td align='center' colspan='2'><input name='subchgemail' type='submit' size='35' maxlength='75' value='Submit'></td>		
                            </tr>
                        </table>
                      </form>";
			}	

			public function showSendEmail(){

				return "
                	<h1>E-Mail</h1>

                    	<p>To send a mass E-Mail to all registered members, leave the 'To:' field blank.</p>
                        
                        <form name='email' method='post' action='".$_SERVER['PHP_SELF']."'>
                            <table>
                                <tr>
                                    <td align='left'>To :</td>
                                    <td align='left'><input name='recipient' type='text' size='50' /></td>
                                </tr>
                                <tr>
                                    <td align='left'>Subject :</td>
                                    <td align='left'><input name='subject' type='text' size='50' /></td>
                                </tr>
                                <tr><td><br /></td></tr>
                                <tr>
                                    <td align='left' valign='top'>Message :</td>
                                    <td align='left'><textarea name='message' rows='10' cols='50'></textarea></td>
                                </tr>
                                <tr><td><br /></td></tr>
                                <tr><td align='center' colspan='2'><input name='email' type='submit' value='   Send   ' /></td></tr>
                            </table>
                       	</form>";
		}
		
		public function showAddProduct(){
			
			$db = new Database();
			$conn = $db->connect();
			
			$tablename = "categories";
			$Tablename = ucfirst($tablename);
			
			$cat_result = $conn->query("SELECT * FROM ".$this->getDBTable($tablename));
			
			$output .= "
							<table >
							<tr>
								<td colspan='2'>
									<div class='input_name'>Name: </div>
									<div class='input_field'><input name='name' type='text'  value=\"\"  /></div>
									<br />
								</td>
							</tr>
							<tr>
								<td>
									<div class='input_name'>Category: </div>
									<div class='input_field'>
										<select name='category'>
											<option name='&nbsp;' value='&nbsp;' selected='selected'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
											";
										
			while ($cat_row = $cat_result->fetch_array()){
			
				$cat_name = ucfirst($cat_row['name']);
				$output .= "<option name='$cat_name'  value=\"$cat_name\" >$cat_name</option>";
		
			}
										 /*
											<option name='&nbsp;' value='&nbsp;' selected='selected'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
											<option name='new'  value=\"New\" >New</option>
											<option name='shoes'  value=\"Shoes\" >Shoes</option>
											<option name='clothes'  value=\"Clothes\" >Clothes</option>
											<option name='accessories'  value=\"Accessories\" >Accessories</option>
											<option name='jewelry'  value=\"Jewelry\" >Jewelry</option>
											<option name='sale'  value=\"Sale\" >Sale</option>
											*/		
			$output .= "								
                                        </select>
									</div>
									<br /><br />
								</td>
							</tr>
							<tr>
								<td>
									<div class='input_name'>Size: </div>
									<div class='input_field'><input name='size' type='text'  value=\"\"  /></div>
									<br />
								</td>
								<td>
									<div class='input_name'>Color: </div>
									<div class='input_field'><input name='color' type='text'  value=\"\"  /></div>
									<br />
								</td>
								
							</tr>
							<tr>
								<td>
									<div class='input_name'>Price: </div>
									<div class='input_field'><input name='price' type='text'  value=\"0.00\" onfocus=\"if (value == '0.00'){value=''}\" onblur=\"if (value == ''){value='0.00'}\"   /></div>
									<br />								
								</td>
								<td>
									<div class='input_name'>Quantity: </div>
									<div class='input_field'><input name='quantity' type='text'  value=\"0\" onfocus=\"if (value == '0'){value=''}\" onblur=\"if (value == ''){value='0'}\"  /></div>
									<br />
								</td>
							</tr>
							<tr>
								<td colspan='2'>
									<br />
									<div class='input_name'>Picture: </div>
									<div class='input_field'><input type='file' name='picture' /></div>
									<br />
								</td>
							</tr>
						</table>
						
						<br />
						
						<div class='input_name'>Description: </div>
						<div id='textbox'>
							<textarea class='ckeditor' id='editor1' name='description' cols='80' rows='10'>&nbsp;</textarea>
						</div>
						
						<br />
						
						<input align='middle' name='add$Tablename_submit' type='submit' value='Add $Tablename' />
						<input align='middle' name='add$Tablename_cancel' type='submit' value='Cancel' />
						
					";
			
			return $output;
		}
		
		public function startTemplate($bg = null){
			if(isset($bg))
				$banner = "<div id='$bg' ></div>";
			else
				$banner = "<div id='newandfab' ></div>";
				
			$output .=	
				$this->startHtml().	
			 		$this->showHeader().
						$this->showSideNav().
						$this->startMainContent();	
									 
							 
			return $output;					 
		}
		
		public function endTemplate(){
			$output .= 
									
						$this->endMainContent().
					$this->showFooter().
				$this->endHtml();	
				
			return $output;	
		}
		
		public function showProductsPage($var){		
			$cms = new CMS();
			$display = new Display();
			$members = new Members();
			$products = new Products();
			$blog = new Blog();
			
			if($_GET['do'] == "viewProducts"){
				$id = $_GET['id'];
				return $products->showProducts($id); 
			} else {
				return $products->showProducts(null, "$var"); 
			}	
		}
			
	}//Close Display class
        
 ?>