<?php

	include "scripts/library.php";

	if($_POST['install']){
		//Form has been submited, do install
		
		$sitename = $_POST['sitename'];
		$url = $_POST['url'];
		$root = $_SERVER['DOCUMENT_ROOT'];     
		
		$dbhost = $_POST['dbhost'];
		$dbuser = $_POST['dbuser'];
		$dbpass = $_POST['dbpass'];
		$dbname = $_POST['dbname'];
		$dbprefix = $_POST['dbprefix'];
		
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$password2 = $_POST['password2'];
		$email1 = $_POST['email'];  
		$email2 = $_POST['email2'];
		
		$validate = new Validator();
		
		$validate->database($_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass'], $_POST['dbname']);
		
		$validate->fname($_POST['fname']);
		$validate->lname($_POST['lname']);
		$validate->password($_POST['password'],$_POST['password2']);
		$validate->email($_POST['email'],$_POST['email2']);
		
		if(!isset($validate->errors)){
			$db = new Database();
			$db->setSettings($sitename, $url, $root, $dbhost, $dbuser, $dbpass, $dbname, $dbprefix);
			
			if($db->create_members_table()){
				$db->create_members_admin($fname, $lname, $username, $password, $email1);
				$db->create_posts_table();
				$db->create_cart_table();
				$db->create_categories_table();
				$db->create_products_table();
			}
			
			header ( 'Location: do_install.php');
		}
		
	}

?>

<?php 
	
	$cms = new CMS();
	$display = new Display();
	$members = new Members();

	echo $display->showHeader();
		
	echo $display->showBanner();
			
		echo $display->startWrapper();
				
			echo $display->startWrap();
				echo $display->showGreeting("<h3 align='center'>Welcome to the RUSH Content Management System (RCMS)</h3>");
			echo $display->endWrap();
			
			echo $display->startWrap();	
 ?>				
    

    <br />
	<?php
		if(isset($validate->errors)){
			echo "<p align='center' style='color:red;'>".$validate->errors."</p>";
		}
	?>
    <form name="install" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
        <table align="center" width="470px" cols="2" border="1px" bordercolor="#000000" cellpadding="2px" cellspacing="0px">
            <td colspan="2" bgcolor="#CCCCFF">
            	<p>Tell us about your site:</p>
            </td>
            <tr>
                <td align="right" colspan="1">Site Name:&nbsp;</td>
                <td align="center" colspan="1"><input name="sitename" size="47" type="text" value="<? if(isset($sitename)){ echo $sitename; } else { echo "Your Site Name";} ?>" onfocus="if (value == 'Your Site Name'){value=''}" onblur="if (value == ''){value='Your Site Name'}" /></td>
            </tr>
            <tr>
                <td align="right">URL:&nbsp;</td>
                <td align="center" colspan="1"><input name="url" size="47" type="text" value="<? if(isset($url)){ echo $url; } else { echo "www.YourSiteName.com";} ?>" onfocus="if (value == 'www.YourSiteName.com'){value=''}" onblur="if (value == ''){value='www.YourSiteName.com'}" /></td>
            </tr>
        
        	<td colspan="2" bgcolor="#CCCCFF">
        		<p>Let's setup the database:</p>
        	</td>
        
            <tr>
                <td align="right" colspan="1">Database Host:&nbsp;</td>
                <td align="center" colspan="1"><input name="dbhost" value="<? if(isset($dbhost)){ echo $dbhost; } ?>" size="47" type="text" /></td>
            </tr>
            <tr>
                <td align="right" colspan="1">Database Username:&nbsp;</td>
                <td align="center" colspan="1"><input name="dbuser" value="<? if(isset($dbuser)){ echo $dbuser; } ?>" size="47" type="text" /></td>
            </tr>
            <tr>
                <td align="right" colspan="1">Database Password:&nbsp;</td>
                <td align="center" colspan="1"><input name="dbpass" value="<? if(isset($dbpass)){ echo $dbpass; } ?>" size="47" type="text" /></td>
            </tr>
            <tr>
                <td align="right" colspan="1">Database Name:&nbsp;</td>
                <td align="center" colspan="1"><input name="dbname" value="<? if(isset($dbname)){ echo $dbname; } ?>" size="47" type="text" /></td>
            </tr>
            <tr>
                <td align="right" colspan="1">Table Prefix:&nbsp;</td>
                <td align="center" colspan="1"><input name="dbprefix" value="<? if(isset($dbprefix)){ echo $dbprefix; } else { echo "rcms_"; }?>" size="47" type="text" value="rcms_" onfocus="if (value == 'rcms_'){value=''}" onblur="if (value == ''){value='rcms_'}" /></td>
            </tr>
        
       
        	<td colspan="2" bgcolor="#CCCCFF">
            	<p>Now, let's setup the administrator:</p>
            </td>
        
            <tr>
                <td align="right" colspan="1">Name:&nbsp;</td>
                <td align="center" colspan="1">
                	<input name="fname" type="text" value="<? if(isset($fname)){ echo $fname; } else { echo "First Name";} ?>" onfocus="if (value == 'First Name'){value=''}" onblur="if (value == ''){value='First Name'}" />
                	<input name="lname" type="text" value="<? if(isset($lname)){ echo $lname; } else { echo "Last Name";} ?>" onfocus="if (value == 'Last Name'){value=''}" onblur="if (value == ''){value='Last Name'}"/>
            	</td>
            </tr>
            <tr>
                <td align="right" colspan="1">Username:&nbsp;</td>
                <td align="center" colspan="1"><input name="username" value="<? if(isset($username)){ echo $username; } ?>" size="47" type="text" /></td>
            </tr>
            <tr>
                <td align="right" colspan="1">Password:&nbsp;</td>
                <td align="center" colspan="1"><input name="password" value="<? if(isset($password)){ echo $password; } ?>" size="48" type="password" /></td>
            </tr>
            <tr>
                <td align="right" colspan="1">Re-Enter Password:&nbsp;</td>
                <td align="center" colspan="1"><input name="password2" value="<? if(isset($password2)){ echo $password2; } ?>" size="48" type="password" /></td>
            </tr>
            <tr>
                <td align="right" colspan="1">E-Mail:&nbsp;</td>
                <td align="center" colspan="1"><input name="email" value="<? if(isset($email1)){ echo $email1; } ?>" size="47" type="text" /></td>
            </tr>
            <tr>
                <td align="right" colspan="1">Re-Enter E-Mail:&nbsp;</td>
                <td align="center" colspan="1"><input name="email2" value="<? if(isset($email2)){ echo $email2; } ?>" size="47" type="text" /></td>
            </tr>
			
            <td align="center" colspan="2" bgcolor="#CCCCFF">
            	<p>And that's it for now!</p>
            	<input name="reset" type="reset" value="Reset"  /><input type="submit" name="install" value="Install"  />
        	</td>
        </table>
    </form>
    
    
<?			echo $display->endWrap();	

		echo $display->endWrapper();
	
	echo $display->showFooter(); 

?>
