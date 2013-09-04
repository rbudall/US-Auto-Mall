<?php 

	class Display extends CMS{
			
			//Define Variables
			var $var;
			
			//Constructor
			public function Display(){
				//Code goes here	
			}
			public function showHeader(){
				return "
					<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
					<html xmlns='http://www.w3.org/1999/xhtml'>
					<head>
						<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
						<title>". $this->getSiteVariable('SITENAME')." : RUSH CMS</title>
						
						<link href='styles/main.css' type='text/css' rel='stylesheet' />
						
						<script type='text/javascript' src='/ckeditor/ckeditor.js'></script>
					</head>

					<body>
						<div align='center'>";	
			}
			
			public function showBanner(){
				return "					
					<div class='banner' >
						<img src='images/banner.jpg'/>
                    </div>";	
			}
			
			public function startWrapper(){
				return "<div class='wrapper' id='wrapper' align='center'>";
			}
			
			public function endWrapper(){
				return "</div>";
			}	
				
			public function startWrap($bgcolor = null){
				if(isset($bgcolor)){
					$output = "<div class='wrap' style='background-color: ".$bgcolor."'>";		
				 } else {
					$output = "<div class='wrap'>";		 
				 }
				
				return $output;
			}
			
			public function endWrap(){
				return "</div>";
			}
			
			public function addPadding($size = null){
				if(isset($size)){
					$output = "<div style='padding: ".$size."'>";		
				 } else {
					$output = "<div style='padding: 10px 0px'>";		 
				 }
				
				return $output;
			}
			
			public function endPadding(){
				return "</div>";
			}
			
			public function startColumn($size = null){
				if(isset($size)){
					$output .= "<div class='column' id='column' style=' width:".$size."; '>";
				} else {
					$output .= "<div class='column' id='column' style='width:auto;'>";	
				}
				
				return	$output;
			}
			
			public function leftColumn($size = null){
				if(isset($size)){
					$output .= "<div class='column' id='leftCol' style=' width:".$size."; '>";
				} else {
					$output .= "<div class='column' id='leftCol' >";	
				}
				
				return	$output;
			}

			public function rightColumn($size = null){
				if(isset($size)){
					$output .= "<div class='column' id='rightCol' style=' width:".$size."; '>";
				} else {
					$output .= "<div class='column' id='rightCol' >";	
				}
				
				return	$output;
			}	
			
			public function endColumn(){
				return "</div>";	
			}
			
			public function showGreeting($greeting = null){
				if(isset($greeting)){
					$output .= "<div class='greeting'>$greeting</div>";
				} else {
					$output .= "
						<div class='greeting'>
							Welcome To ".$this->getSiteVariable('SITENAME')."
						</div>";	
				}
				
				return $output;
			}
			
			public function showNav(){
				return "
						<div class='nav'>
							<a href='index.php' > Home </a> | 
							<a href='members.php' > Members </a> | 
							<a href='registration.php' > Register </a> | 
							<a href='' > News </a> | 
							<a href='' > Button </a> | 
							<a href='' > Button </a> | 
							<a href='' > Button </a>
						</div>";	
			}
			
			public function showFooter(){
				return "
					<div class='footer' >
						<img src='images/footer.jpg' />
                    </div>
					
				</div>
		    </body>
			</html>";	
			}
			
			public function showLogin(){

				return "
                	<div id='login' align='center'>
						<form name='login' method='post' action='members.php'>
            			
							<h1>Login</h1>
                    	
                        	
                        	<div align='center'>Username: &nbsp;<input align='middle' type='text' name='username'   /></div>
                        	<div align='center'>Password: &nbsp;<input align='middle' size='21' type='password' name='password'/></div>  
                            <br />
                          	
                            <input name='login' type='submit' value='Login' />
                          	<br />
                        
           	   	   			<br />
            	   	    	<a href='registration.php'>Not Registered</a> | 
           	   	        	<a href='resetpass.php'>Forgot Password</a>         	   	       
               				
                		</form>
					</div>";
				}
				
			public function showLoginSmall(){

				return "<div id='loginSmall' align='center'>
							<form name='login' method='post' action='members.php'>
								<input align='middle' size='12' type='text' name='username' value='Username' onfocus=\"if (value == 'Username'){value=''}\" onblur=\"if (value == ''){value='Username'}\"  />&nbsp;<input align='middle' size='11' type='password' name='password' value='Password' onfocus=\"if (value == 'Password'){value=''}\" onblur=\"if (value == ''){value='Password'}\" />&nbsp;<input name='login' type='submit' value='Login' />  	   	       
							</form>
						</div>";
                	
				}				
			
			public function showRegistration(){
				if(isset($_POST['register'])){
					$fname = $_POST['fname'];
					$lname = $_POST['lname'];
					$username = $_POST['username'];
					$password = $_POST['password'];
					$password2 = $_POST['password2'];
					$email = $_POST['email'];  
					$email2 = $_POST['email2'];

					$validate = new Validator();
					$members = new Members();
			
					$validate->fname($_POST['fname']);
					$validate->lname($_POST['lname']);
					$validate->password($_POST['password'],$_POST['password2']);
					$validate->email($_POST['email'],$_POST['email2']);
					
					if(!isset($validate->errors)){
						$members->register($fname, $lname, $username, $password, $email);
						echo "<div id='registration' >
								Registration Completed
							</div>";
						return;
					} 
				}
				if(!isset($fname)){ 
					$fname = 'First Name';
				}
				
				if(!isset($lname)){ 
					$lname = 'Last Name';
				}
		
				
				$output .= "                
       	   	 	  	<div id='registration' >";
					
                if(isset($validate->errors)){
					$output .= "<p style='color:red' >".$validate->errors."</p>";	
				}   	
				
				$output .= "
					<div align='center' style='width:540px'>
                        Already Registered?
                    	 
             			<form name='login' method='post' action='members.php'> 
							<input align='middle' size='12' type='text' name='username' value='Username' onfocus=\"if (value == 'Username'){value=''}\" onblur=\"if (value == ''){value='Username'}\"  />&nbsp;<input align='middle' size='11' type='password' name='password' value='Password' onfocus=\"if (value == 'Password'){value=''}\" onblur=\"if (value == ''){value='Password'}\" />&nbsp;<input name='login' type='submit' value='Login' />
                      	</form>
						<a href='resetpass.php'>Forgot Your Password</a>  
						</div>
						<br />
						<h1 style='width:540px'>Registration</h1>					
						<div align='center' style='width:540px'>
                    	<form name='register' method='post' action='".$self."?action=register'>
						  <table cellspacing='2' cellpadding='2px' style='width:auto;'>
								<tr>
                                	<td>
								  <td align='left'> <strong> Please Enter Your Name:</strong> </td>
                                  		<td> 
                                        	<input type='text' name='fname' size='16' maxlength='75' value='".$fname."' onfocus=\"if (value == 'First Name'){value=''}\" onblur=\"if (value == ''){value='First Name'}\" >
										</td><td>
	  								  		<input type='text' name='lname' size='16' maxlength='75' value='".$lname."' onfocus=\"if (value == 'Last Name'){value=''}\" onblur=\"if (value == ''){value='Last Name'}\">
								  		</td>
                               		</td>
                                </tr>
                                <tr>
                                	<td>
								  <td align='left'> 
                                        	<strong> Please Choose A Username:</strong><br />
											<font size='-1' color='#ffffff'>Username must be 6 to 25 characters long</font>
						  		  </td>
										<td width='280' colspan='2'> <input type='text' name='username' size='40' maxlength='75' value='".$username."'> </td>
                               		</td>
								</tr>
								<tr>
                                	<td>
                               	  <td align='left'> 
                                        	<strong> Please Choose A Password:</strong><br />
											<font size='-1' color='#ffffff'>Passwords must be at least 6 characters long</font>
						  		  </td>
                                   		<td colspan='2'> <input type='password' name='password' size='40' maxlength='75'> </td>
                                    </td>
								</tr>
								<tr>
                                	<td>
								  <td align='left'>
											<strong>Please Re-enter Your Password:</strong><br />
	   										<font size='-1' color='#ffffff'>It must match exactly</font>
						  		  </td>
										<td colspan='2'> <input type='password' name='password2' size='40' maxlength='75'> </td>
                               		</td>
								</tr>
								<tr>
                                	<td>
								  <td align='left'>
									  		<strong> Please Enter Your Email Address:</strong><br />
											<font size='-1' color='#ffffff'>You will need to enter a real email address</font>
						  		  </td>
										<td colspan='2'> <input type='text' name='email' size='40' maxlength='75' value='".$email."'> </td>
                                  	</td>
								</tr>
								<tr>
                                	<td>
								  <td align='left'> <strong>Please Re-enter Your Email Address:</strong> </td>
										<td colspan='2'> <input type='text' name='email2' size='40' maxlength='75' value='".$email2."'> </td>
                                   	</td>
								</tr>
						  </table>

                            <br />
                            <br />

							<noscript><p>[This resource requires a Javascript enabled browser.]</p></noscript>

                            <input type='submit' name='register' value='Register'>

						</form></div>
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

			public function listTable($var, $rowsPerPage = null){			
				
				$pageNum = 1;
				$sort = '';
				$sort_type = 'desc';
				$order = "ORDER BY id desc";
				$self = $_SERVER['PHP_SELF'];
				$nav  = '';
				$search = '';
				$ucvar = ucfirst($var);
				
				if($rowsPerPage == null)
					$rowsPerPage = 10;
				
				$display = new Display();				
				$db = new Database();
				$conn = $db->connect();	

				$getField = $conn->query("SELECT * FROM ".$this->getDBTable($var));
				
				if($var == 'members'){
					$numfields = $getField->field_count - 4;
				} else if($var == 'products'){
					$numfields = $getField->field_count - 1;
				} else {
					$numfields = $getField->field_count;
				}
				
				$width = 100/$numfields;
				$tablesize = 625;
				// if $_GET['page'] defined, use it as page number
				if(isset($_GET['pg']))
					$pageNum = $_GET['pg'];
				
				if(isset($_GET['action']))
					$action = $_GET['action'];
				
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
								<div align='left'>";
								
				if($var == "members"){ 				
					$output .= "";
				} else {
					$output .= "<img src='../rushcms/images/buttons/add.gif' onclick=\"window.location.href='$self?action=$action&do=add$ucvar&pg=$pageNum$sort_link'\" alt='Add Post' style='cursor:pointer; position: relative; margin: 5px 30px;'>";

				}
				
				$output .= "</div>
								<tr style='background:url(../rushcms/images/background/nav_01.jpg)'>";
								

				
				for ($i=0; $i<$numfields ; $i++ ) {
					$fetch_field = $getField->fetch_field();
					$row_title = $fetch_field->name;
					
					if(($row_title == 'password')||($row_title == 'sid')||($row_title == 'group')||($row_title == 'profile_pic')||($row_title == 'frontcover')||($row_title == 'backcover')){
							$output .= "";
					} else if($row_title == 'fname'){
						$output .= "<td colspan='1' width='".$width."%' align='center'>
										<strong>
											<a href='$self?action=$action&pg=$pageNum&sort=".$row_title."&type=$sort_type'>First</a>
										</strong>
									</td>";
					} else if($row_title == 'lname'){
						$output .= "<td colspan='1' width='".$width."%' align='center'>
										<strong>
											<a href='$self?action=$action&pg=$pageNum&sort=".$row_title."&type=$sort_type'>Last</a>
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
				$index = 1;
				$my_index = $index;

				while ($row = $result->fetch_array()){	
					
					if($offset == 0){
						$my_index = $index;
			 		} else {
						$my_index = $index + $offset;
					}
					
					$output .= "<tr id='$row[id]'>";
					$getField->field_seek(0);
					
					for ($i=0; $i<$numfields ; $i++ ) {
						
					   	$fetch_field = $getField->fetch_field();
						$row_title = $fetch_field->name;
						
						if(($row_title == 'password')||($row_title == 'sid')||($row_title == 'group')||($row_title == 'profile_pic')||($row_title == 'frontcover')||($row_title == 'backcover')){
							$output .= "";
						} else if(($row_title == 'logged_in') || ($row_title == 'logged_out')||($row_title == 'time')){
							$output .= "<td colspan='1' width='".$width."%' align='center'>".date('F d Y g:i A', strtotime($row[$row_title]))."</td>";
						} else if($row_title == 'content'){
							$output .= "<td colspan='1' width='".$width."%' align='center'>".$this->neat_trim(strip_tags($row[$row_title]), 25)."</td>";
						} else if($row_title == 'url'){
							$output .= "<td colspan='1' width='".$width."%' align='center'>".$this->neat_trim(strip_tags($row[$row_title]), 15)."</td>";
						} else if($row_title == 'picture'){
							$output .= "<td colspan='1' width='".$width."%' align='center'>".$this->neat_trim(strip_tags($row[$row_title]), 5)."</td>";
						} else if($row_title == 'name'){
							$output .= "<td colspan='1' width='".$width."%' align='center'>".$this->neat_trim(strip_tags($row[$row_title]), 20)."</td>";
						} else if($row_title == 'size'){
							$output .= "<td colspan='1' width='".$width."%' align='center'>".$this->neat_trim(strip_tags($row[$row_title]), 10)."</td>";
						} else if($row_title == 'title'){
							$output .= "<td colspan='1' width='".$width."%' align='center'>".$this->neat_trim(strip_tags($row[$row_title]), 10)."</td>";
						} else if($row_title == 'id'){
							$output .= "<td onclick='window.location=\"$self?action=$action&do=edit$ucvar&id=$row[id]&index=$my_index&pg=$pageNum$sort_link\";' onmouseover='document.getElementById(\"$row[id]\").style.backgroundColor=\"#FC6\";' onmouseout='document.getElementById(\"$row[id]\").style.backgroundColor=\"#CCC\";' style='cursor:pointer;' colspan='1' width='".$width."%' align='center'>".$this->neat_trim(strip_tags($row[$row_title]), 10)."</td>";
						} else {
							$output .= "<td colspan='1' width='".$width."%' align='center'>".$row[$row_title]."</td>";
						}

					}
					
					$output .= "<td><img src='../rushcms/images/buttons/delete.gif' onclick='if(confirm(\"Are you sure you want to delete ID# $row[id] from $ucvar?\")){window.location=\"$self?action=$action&do=delete$ucvar&id=$row[id]&pg=$pageNum$sort_link\";}' alt='Delete Post' style='cursor:pointer;'></td></tr>";
					
					$index++;
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
				$output .= "
						<tr align='left' style='background: #333333;'>
							<td colspan='".($numfields + 1)."'>
								<div class='list_nav'>".$first."</div> 
								<div class='list_nav'>".$prev."</div>
								<div class='list_nav'>".$nav."</div>
								<div class='list_nav'>".$next."</div>
								<div class='list_nav'>".$last."</div>			
							</td>
						</tr>";
			
				$output .= "</table>
						<br />
						<br />
						
						<form name=\"srch\" method=\"post\" action=\"$self?action=$action&pg=$pageNum$sort_link\" onsubmit=\"return checkSearchForm();\">
						Seach for: <input type=\"text\" name=\"srch\" /> in 
						<Select NAME=\"field\">";

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
					$cols = $numfields;
					$output .= "<table class='list' cellspacing='0' width='".$tablesize."'>";
					$output .= "<tr style='background:url(rushcms/images/background/nav_01.jpg)'><td align='center' colspan='".$cols."'><strong>Results</strong></td></tr>"; 
					$output .= "<tr>";
					
					$num = $data->num_rows;
					
					$getField->field_seek(0);
					//And we display the results 
					while($row = $data->fetch_array()){ 
						$my_id = $row['id'];
						$output .= "</tr><tr <tr id='srch_$my_id' onclick='window.location=\"$self?action=$action&do=edit$ucvar&id=$row[id]&index=$my_index&pg=$pageNum$sort_link\";' onmouseover='document.getElementById(\"srch_$my_id\").style.backgroundColor=\"#FC6\";' onmouseout='document.getElementById(\"srch_$my_id\").style.backgroundColor=\"#CCC\";'>";
						$getField->field_seek(0);
												
						for ($i=0; $i<$numfields ; $i++ ) {
					   		$fetch_field = $getField->fetch_field();
							$row_title = $fetch_field->name;
							
							if(($row_title == 'password')||($row_title == 'sid')||($row_title == 'group')||($row_title == 'profile_pic')||($row_title == 'frontcover')||($row_title == 'backcover')){
								$output .= "";
							} else if(($row_title == 'logged_in') || ($row_title == 'logged_out')||($row_title == 'time')){
								$output .= "<td colspan='1' width='".$width."%' align='center'>".date('F d Y g:i A', strtotime($row[$row_title]))."</td>";
							} else if($row_title == 'content'){
								$output .= "<td colspan='1' width='".$width."%' align='center'>".$this->neat_trim(strip_tags($row[$row_title]), 25)."</td>";
							} else if($row_title == 'url'){
								$output .= "<td colspan='1' width='".$width."%' align='center'>".$this->neat_trim(strip_tags($row[$row_title]), 15)."</td>";
							} else {
								$output .= "<td colspan='1' width='".$width."%' align='center'>".$this->neat_trim(strip_tags($row[$row_title]), 15)."</td>";
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

			public function showBlog($id = null){
				
				$db = new Database();
				$conn = $db->connect();
				$rowsPerPage = 5;
				$pageNum = 1;
				$nav = '';

				if(isset($_GET['pg'])){
					$pageNum = $_GET['pg'];
				}
				
				$offset = ($pageNum - 1) * $rowsPerPage;
				
				if(isset($id)){
					$result = $conn->query("SELECT * FROM ".$this->getDBTable('posts')." WHERE id = $id");
				} else {
					$result = $conn->query("SELECT * FROM ".$this->getDBTable('posts')." ORDER BY id DESC LIMIT $offset, $rowsPerPage");
				}
				
				$num = $result->num_rows;
				
				$output .= "
					<div id='blog'>
					
						<h3>Blog</h3>";
				
				if($num < 1){
					$output .= "
							<div class='post'>
								<div class='overlay_post'></div>
								<div>
									<div class='post_date'> 
										Posted on: ".date("F-d-Y ")." | ".date("g:i A ")." 
									</div>
									
									<div class='post_layout'>
										<br />
										<hr />
										No Name
										<hr /> 
										
										<div class='post_content'>
											No posts were found in the database.
										</div>
									</div>
								</div>
							</div>";
							
					$output .= "</div>";		
					
					return $output;
				}
				
				while ($row = $result->fetch_array()){
					$search = $conn->query("SELECT * FROM ".$this->getDBTable('members')." WHERE username = '".$row['author']."' ");
					$day = date("F-d-Y ", strtotime($row['time']));
					$time = date("g:i A ", strtotime($row['time']));
					
					if($pic = $search->fetch_array()){
						$display_pic = "<img class='profile_pic' src='".$pic['profile_pic']."' onclick=\"window.location.href='#'\" style='cursor:pointer;'/>";
					} else {
						$display_pic = "<img class='profile_pic' src='images/profile_pic.jpg' onclick=\"window.location.href='#'\" style='cursor:pointer;'/>";
					}	
					
					if(!$row['title']){
						$display_title = "";
					} else {
						$display_title = "<div class='post_title'>".stripslashes($row['title'])."</div>";
					}
					
					if(!$row['author']){
						$display_author = "";
					} else {
						$display_author = "<div id='author' class='author'>".$row['author']."</div>&nbsp"; 
					}
					
						
					$output .= "
					<div class='post'>
						<div class='overlay_post'></div>
						
						<div>
							<div class='post_date'> 
								Posted on: ".$day."&nbsp;|&nbsp;".$time."
							</div>
				
							<div class='post_layout'>
								".$display_author."".$display_pic."
								<br />
								<hr />
								".$display_title."
								<hr />
							
								
								<div id='content' class='post_content'>
									".$row['content']."
								</div>
							</div>
						</div>
					</div>";
				}
				
				$output .= "</div>";
				
				$query   = "SELECT COUNT(*) AS numrows FROM ".$this->getDBTable('posts')."";
				$result  = $conn->query($query);
				$row     = $result->fetch_array(MYSQL_ASSOC);
				$numrows = $row['numrows'];
				
				$maxPage = ceil($numrows/$rowsPerPage);
			
				for($page = 1; $page <= $maxPage; $page++){
				
					if ($page == $pageNum){
					  $nav .= " $page "; // no need to create a link to current page
					} else {
						$nav .= " <span class='link'><a href=\"$self?pg=$page\">$page</a></span> ";
					} 
				}	
				
				if ($pageNum > 1){
					$page  = $pageNum - 1;
					$prev  = " <span class='link'><a href=\"$self?pg=$page\"> Prev </a></span> ";
					$first = " <span class='link'><a href=\"$self?pg=1\"> |<< </a></span> ";
				} else {
					$prev  = '&nbsp;'; // we're on page one, don't print previous link
					$first = '&nbsp;'; // nor the first page link
				}
			
				if ($pageNum < $maxPage){
					$page = $pageNum + 1;
					$next = " <span class='link'><a href=\"$self?pg=$page\"> Next </a></span> ";
					$last = " <span class='link'><a href=\"$self?pg=$maxPage\"> >>| </a></span> ";
				} else {
					$next = '&nbsp;'; // we're on the last page, don't print next link
					$last = '&nbsp;'; // nor the last page link
				}
				
				$output .= "
				<div class='pg_nav'>
					<table cols='5' cellpadding='0' cellspacing='0'>
						<tr >		
							<td align='center' width='175px' style='color:#fff'>&nbsp;".$first."&nbsp;</td> 
							<td align='center' width='175px' style='color:#fff'>&nbsp;".$prev."&nbsp;</td>
							<td align='center' width='175px' style='color:#000'>&nbsp;".$nav."&nbsp;</td>
							<td align='center' width='175px' style='color:#fff'>&nbsp;".$next."&nbsp;</td>
							<td align='center' width='175px' style='color:#fff'>&nbsp;".$last."&nbsp;</td>
						</tr>
					</table>
				</div>";
				
				return $output;		
					
			}
			
			public function showBlogManager(){ 
	
				$rowsPerPage = 5;
				$pageNum = 1;
				$sort = '';
				$sort_type = 'desc';
				$order = "ORDER BY id desc";
				$self = $_SERVER['PHP_SELF'];
				$nav  = '';
				$search = '';
				$db = new Database();
				$conn = $db->connect();
				
				// if $_GET['page'] defined, use it as page number
				if(isset($_GET['pg'])){
					$pageNum = $_GET['pg'];
				}
				if(isset($_GET['action'])){
					$action = $_GET['action'];
				}
				
				if(isset($_GET['postID'])){
					$postID = $_GET['postID'];
				}
				
				if(isset($_GET['do'])){
					$do = $_GET['do'];
				}
				
				$output .= $this->listTable('posts');
				
				//Lets add a post to the database
				if($do == "addPost"){
					if(isset($_GET['status'])){
						$status = $_GET['status'];
						$output .= 'Post Added';
						return $output;
					}
					
					if(isset($_GET['submit'])){
						$submit = $_GET['submit'];
				
						$v_title = $_POST['title'];
						$v_content = $_POST['content'];
						
						if(empty($v_title)){
							$v_title = "&nbsp;";
						}
						
						if(isset($_POST['addPost_submit'])){
							if(!empty($v_content)){
							
								
								$result = $conn->query("INSERT INTO ".$this->getDBTable('posts')." (id, author, title, content, time) VALUES (NULL, '$_SESSION[valid_user]', '$v_title','$v_content', NOW())");
								
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
						
						if(isset($_POST['addPost_cancel'])){
							$output .= "Post was not added.";
							return $output;	
						}
							
					}
					
					$title = $row['v_title'];
						
					$output .= "
						<div id='addPost' align='left'><div class='overlay_post'></div><div id='content'>
							<form name='addPost' method='post' action='$self?action=$action&do=$do&submit=submitPost&pg=$pageNum$sort_link'>
								Title: <input name='title' type='text' size='100%' value=\"$title\"  /><br />
								Content: <textarea class='ckeditor' id='editor1' name='content' cols='80' rows='10'></textarea><br />
								<input align='middle' name='addPost_submit' type='submit' value='Add Post' />
								<input align='middle' name='addPost_cancel' type='submit' value='Cancel' />
							</form></div>
						</div>";
				}
				
				//Lets edit a post in the database
				if($do == "editPost"){
					if(isset($_GET['postID'])){
						$postID = $_GET['postID'];
					}
					
					if(isset($_GET['status'])){
						$status = $_GET['status'];
						$output .= 'Post Edited';
					}
					
					if(isset($_GET['submit'])){
						$submit = $_GET['submit'];
				
						$v_title = $_POST['title'];
						$v_content = $_POST['content'];	
						
						if(empty($v_title)){
							$v_title = "&nbsp;";
						}		
						
						if(isset($_POST['editPost_submit'])){
							if(!empty($v_content)){
							
								$update = $conn->query("UPDATE ".$this->getDBTable('posts')." SET title='$v_title', content='$v_content' WHERE id='$postID' LIMIT 1");
								 
								if($update){
									$output .= "<meta http-equiv='refresh' content='0;URL=$self?action=$action&do=$do&status=done&postID=$postID&pg=$pageNum$sort_link'>";
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
					
					if(!empty($postID)){
							
						$result = $conn->query("SELECT * FROM ".$this->getDBTable('posts')." WHERE id = '$postID'"); 
						$row = $result->fetch_array();
						
						$title = $row['title'];
						
						$output .= "
							<div id='editPost' align='left'><div class='overlay_post'></div><div id='content'>
								<form name='editPost' method='post' action='$self?action=$action&do=$do&postID=$row[id]&submit=submitPost&pg=$pageNum$sort_link'>
									Post # $row[id] <br />
									Date: ".date('F d Y', strtotime($row['time']))."<br />
									Time: ".date('g:i A', strtotime($row['time']))."<br />
									Author: $row[author]<br />
									Title: <input name='title' type='text' size='100%' value=\"$title\"  /><br />
									Content: <textarea class='ckeditor' id='editor1' name='content' cols='80' rows='10'>".$row['content']."</textarea><br />
									<input align='middle' name='editPost_submit' type='submit' value='Edit Post' />
									<input align='middle' name='editPost_cancel' type='submit' value='Cancel' />
								</form></div>
							</div>";
							
					}
				}
				
				//Lets delete a post
				if($do == "deletePost"){
				
					if(isset($_GET['status'])){
						$output .= 'Post Deleted';
						return $output;
					}
					
					if(isset($_GET['postID'])){
						$postID = $_GET['postID'];
						
						$result = $conn->query("DELETE FROM ".$this->getDBTable('posts')." WHERE id = '$postID' LIMIT 1");															
						if($result){
							$output .= "<meta http-equiv='refresh' content='0;URL=$self?action=$action&do=$do&status=done&postID=$postID&pg=$pageNum$sort_link'>";
						} else {
							throw new Exception("Changes to the post could not be saved at this time.");
						}
					} else {
						$output .= "The post was not deleted";
						return $output;
					}
				}
				
				return $output;
			} 
			
			public function showChgPass(){
return "  	<h1>Change Password</h1>
                    
                    	<form name='chgpass' method='post' action=".$_SERVER['PHP_SELF']." oncsubmit='return checkChgPassForm()'>
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
	}//Close Display class
        
 ?>