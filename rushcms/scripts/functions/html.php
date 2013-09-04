<?php

require_once ($_SERVER['DOCUMENT_ROOT']."/testserver/scripts/functions/sf_fns.php");

function displayHeader($title, $bg) {
	Header("Cache-Control: max-age=86400, must-revalidate");
	?>

		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
                <META HTTP-EQUIV="Expires" CONTENT="-1">
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                
                <title>Strike Force Music: <?php echo($title) ?></title>

        		<script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
                <script src="/testserver/scripts/fonts.php" language="php"></script>
                <script src="/testserver/scripts/scripts.js" type="text/javascript"></script>
                <script src="/scripts/AC_ActiveX.js" type="text/javascript"></script>
                <script src="/scripts/AC_RunActiveContent.js" type="text/javascript"></script>
                
                <link href="/testserver/styles/fonts.css" type="text/css" rel="stylesheet" />
                <link href="/testserver/styles/main.css" type="text/css" rel="stylesheet" />
            </head>
            <!--  no-repeat-->
            <body background="/testserver/images/backgrounds/bg_<?php echo($bg) ?>.jpg">
             
                <div align="center"> 
                    
                    <div class="nav">
                        |  <a href="/testserver/home.php">HOME</a>  |  
                        <a href="/testserver/biography.php">BIOGRAPHY</a>  |  
                        <a href="/testserver/members.php">MEMBERS</a>  |  
                    </div>
                   <div class="wrapper"> 
		<?php
}

function displayBanner($num){
	?>
                    <div class="banner" >
                        <img src="/testserver/images/banners/banner_<?php echo($num); ?>.jpg" width="100%" height="100%" />
                    </div>
    <?php

}

function displayFooter() {			

	?>
            		</div> <!-- end wrapper -->
                    
                    <div class="footer"></div>            
                
                </div>    
            </body>
        </html>
    			
	<?php
}

function displayThis($var_header, $var_content) {
		
	$header = $var_header;
	$content = $var_content;
	
	return "<h1>".$header."</h1>".$content;
}	

function displayURL($link, $linkname){
		
	$url = $link;
	$name = $linkname;
	
	return "<a href=".$url.">".$name."</a>";	
}	

function displayLogin(){

return "
                	<form name='login' method='post' action='/testserver/members.php'>
            			<h1>Login</h1>
                    	
                        
                        	Username: &nbsp;<input type='text' name='username' size='22'  /><br  />
                        	Password: &nbsp;<input type='password' name='password' size='22'/><br />  
                            <br />
                          
                            <input name='sublogin' type='submit' value='Login' />
                          	<br />
                        
           	   	   			<br />
            	   	    	<a href='/testserver/registration.php'>Not Registered</a> | 
           	   	        	<a href='/testserver/resetpass.php'>Forgot Password</a>         	   	       
               			
                	</form>";
}

function displayChgPass(){
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

function displayChgEmail(){
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

function displayResetPass(){
return "
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
                        </form>";

		
}

function displaySendEmail(){

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

function displayRegForm(){

return "                
       	   	 	  <h1>Registration</h1>
                    	<div class='wrap' style='width:65%'>
                        Already Registered?
                    	
             			<form name='login' method='post' action='/testserver/members.php'> 
							Username: &nbsp;<input type='text' name='username' size='20'  /><br />
                        	Password:&nbsp;&nbsp;<input type='password' name='password' size='21'/><br />
                        	<input name='sublogin' type='submit' value='Login' />
                      	</form>
						<a href='/testserver/resetpass.php'>Forgot Your Password</a>  
						</div>
						
						<div class='wrap' style='width:65%'>
                    	<form name='register' method='post' action='/testserver/scripts/actions/doRegister.php'  onsubmit='return checkRegForm();'>
						  <table cellspacing='2' cellpadding='2px' style='width:auto;'>
								<tr>
                                	<td>
								  <td align='left'> <strong> Please Enter Your Name:</strong> </td>
                                  		<td> 
                                        	<input type='text' name='fname' size='17' maxlength='75' value='First Name' onfocus=\"if (value == 'First Name'){value=''}\" >
	  								  		<input type='text' name='lname' size='17' maxlength='75' value='Last Name' onfocus=\"if (value == 'Last Name'){value=''}\" >
								  		</td>
                               		</td>
                                </tr>
                                <tr>
                                	<td>
								  <td align='left'> 
                                        	<strong> Please Choose A Username:</strong><br />
											<font size='-1' color='#ffffff'>Username must be 6 to 25 characters long</font>
						  		  </td>
										<td width='280' colspan='2'> <input type='text' name='username' size='40' maxlength='75' value=''> </td>
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
										<td colspan='2'> <input type='text' name='email' size='40' maxlength='75' value=''> </td>
                                  	</td>
								</tr>
								<tr>
                                	<td>
								  <td align='left'> <strong>Please Re-enter Your Email Address:</strong> </td>
										<td colspan='2'> <input type='text' name='email2' size='40' maxlength='75' value=''> </td>
                                   	</td>
								</tr>
						  </table>

                            <br />
                            <br />

							<noscript><p>[This resource requires a Javascript enabled browser.]</p></noscript>

                            <input type='submit' name='submit' value='Submit My Registration'>

						</form></div>";
}	

function displayPost($width, $content, $float = null){
if(!$float){
	$doFloat = "";
} else {
	$doFloat = "float: ".$float.";";
}


?> 
	
	<div align="center" id="content" class="wrap" style="width:<? echo($width);?>;<? echo($doFloat);?>">
	<span class="overlay"></span>
    <div class='display_post'><? echo $content ?></div>
    	
    </div>
<?
}		

function displayPostManager(){ 
return list_posts();
}

function displaySlideshowManager(){ 
return list_slideshows();
}

function displayMusicManager(){ 
return list_music();
}

?>