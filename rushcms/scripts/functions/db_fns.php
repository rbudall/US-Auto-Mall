<?php

require_once($_SERVER['DOCUMENT_ROOT']."/testserver/scripts/functions/sf_fns.php");

function db_connect(){
//Connect To Database

	$hostname='strikeforce.powwebmysql.com';
	$username='strikeforce';
	$password='carolcity1!';
	$dbname='db_strikeforce';

	
	$result = new mysqli($hostname, $username, $password, $dbname);
	
	if(!$result){
		throw new Exception('Could not connect to the database server');
	} else {
		return $result;
	}
	
	return true;
}

function register($fname, $lname, $username, $password, $email){
//Register new user into db
	
	//Capitalize first letter of name
	$Fname = ucfirst(strtolower($fname));	
	$Lname = ucfirst(strtolower($lname));

	//Connect to db
	$conn = db_connect();

	//Check to see if username is unique
	$result = $conn->query("SELECT * FROM sf_members WHERE username = '$username' OR email = '$email'");
		
	if(!$result){
		throw new Exception ('Registration could not be completed. Query could not be executed.');
	}
	
	if($result->num_rows > 0){
		throw new Exception ('The username or email you entered is already taken. Please go back and try again.');
	}
	
	//If ok, put in db
	
	$result = $conn->query("INSERT INTO sf_members (`id`,`group`,`fname`,`lname`,`username`,`password`,`email`) VALUES (NULL,'0','$Fname','$Lname','$username', SHA1('$password'), '$email')");
	
	if(!$result){
		throw new Exception('Could not register you into the database at this time. Please try again later.');
	}
	
	return true;
}

function login($username, $password){
//Check username and password with db
	
	//Connect to db
	$conn = db_connect();
	
	//Check if username is unique
	$result = $conn->query("SELECT * FROM sf_members WHERE username = '$username' AND password = SHA1('$password') ");	
	
	if(!$result){
		throw new Exception('Could not log you in. Error executing query.');
		return false;
	}

	if($result->num_rows > 0){
	//If they are in the database register the user id
		$row = $result->fetch_array();
		$username = $row['username'];
		$_SESSION['valid_user'] = $username;
		$sid = session_id();
		$result = $conn->query("UPDATE sf_members SET logged_in = NOW(), sid = '$sid' WHERE username = '$username'");
	} else {
		throw new Exception('Could not log you in. Username or Password Incorrect. <br /><br /> Please try again.');
		return false;
	}

	return true;
}

function logout(){
//Log out the current user

	//Store to test if they were logged in
	$old_user = $_SESSION['valid_user'];
	
	$conn = db_connect();
	$result = $conn->query("UPDATE sf_members SET logged_out = NOW() WHERE username = '$old_user'");
	
	$_SESSION = array(); 					// Clear all session variables
	$result_dest = session_destroy();		// Destroy session

	if(!empty($old_user)){
		if($result_dest){
		//If they were logged in and are now logged out
			return true;
		} else {
		//They were logged in and could not be logged out
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

function check_user(){
//See is somebody is logged in and notify them if not	
	
	if(isset($_SESSION['valid_user'])){
		return true;
	} else {
	//They are not logged in
		return false;
	}
}

function check_admin(){
//Check to see it an administrator is logged in

	if(check_user()){
	//If the user is logged in...
		
		$username = $_SESSION['valid_user'];
	
		$conn = db_connect();
		
		$result = $conn->query("SELECT * FROM sf_members WHERE username = '$username'");
		$num = $result->num_rows;
		$row = $result->fetch_array();
		$group = $row['group'];
		

		if (!$result){
			throw new Exception ("Could not query the database. Please try again later.");
		} else {
			if ($group >= 3){
				return true;
			} else {
				return false;
			}
		}
	}
}

function list_members(){
	$output = "";

//Display a list of all the members
	
	$rowsPerPage = 5;
	$pageNum = 1;
	$sort = '';
	$sort_type = 'desc';
	$order = "ORDER BY id asc";
	$self = $_SERVER['PHP_SELF'];
	$nav  = '';
	$search = '';
	$conn = db_connect();
	
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

	//Connect to the database		
	$conn = db_connect();
	
	$sql = "SELECT * FROM sf_members $order $search LIMIT $offset, $rowsPerPage";
	$result = $conn->query($sql);
	
	$num = $result->num_rows;
	
	$output .= "
	<table class='list' cols='7' cellspacing='0' cellpadding='0'  width='680px'> 
    	<tr style='background:url(/testserver/images/backgrounds/nav_01.jpg)'>
			<td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=id&type=$sort_type'>ID</a></strong></span></td>
            <td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=username&type=$sort_type'>Username</a></strong></span></td>
            <td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=fname&type=$sort_type'>First Name</a></strong></span></td>
            <td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=lname&type=$sort_type'>Last Name</a></strong></span></td>
            <td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=email&type=$sort_type'>E-Mail</a></strong></span></td>
            <td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=logged_in&type=$sort_type'>Logged In</a></strong></span></td>
            <td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=logged_out&type=$sort_type'>Logged Out</a></strong></span></td>
       </tr>";
	
	while ($row = $result->fetch_array()){	
		$output .= "<tr>
          	  		<td>$row[id]</td>
            		<td>$row[username]</td>
					<td>$row[fname]</td>
            		<td>$row[lname]</td>
            		<td>$row[email]</td>
            		<td>".date('F d Y g:i A', strtotime($row['logged_in']))."</td>
            		<td>".date('F d Y g:i A', strtotime($row['logged_out']))."</td>
                </tr>";
	}
	
	// how many rows we have in database
	$query   = "SELECT COUNT(*) AS numrows FROM sf_members";
	$result  = $conn->query($query);
	$row     = $result->fetch_array(MYSQL_ASSOC);
	$numrows = $row['numrows'];

	// how many pages we have when using paging?
	$maxPage = ceil($numrows/$rowsPerPage);

	for($page = 1; $page <= $maxPage; $page++){
	
		if ($page == $pageNum){
	      $nav .= " $page "; // no need to create a link to current page
	   	} else {
   	   		$nav .= " <span class='link'><a href=\"$self?action=$action&pg=$page$sort_link\">$page</a></span> ";
  	 	} 
	}	
	
	if ($pageNum > 1){
		$page  = $pageNum - 1;
   		$prev  = " <span class='link'><a href=\"$self?action=$action&pg=$page$sort_link\"> Prev </a></span> ";
   		$first = " <span class='link'><a href=\"$self?action=$action&pg=1$sort_link\"> |<< </a></span> ";
	} else {
  		$prev  = '&nbsp;'; // we're on page one, don't print previous link
   		$first = '&nbsp;'; // nor the first page link
	}

	if ($pageNum < $maxPage){
		$page = $pageNum + 1;
   		$next = " <span class='link'><a href=\"$self?action=$action&pg=$page$sort_link\"> Next </a></span> ";
   		$last = " <span class='link'><a href=\"$self?action=$action&pg=$maxPage$sort_link\"> >>| </a></span> ";
	} else {
   		$next = '&nbsp;'; // we're on the last page, don't print next link
   		$last = '&nbsp;'; // nor the last page link
	}

	// print the navigation link 
	$output .= "</table><table class='list' width='680' cols='5' cellpadding='0' cellspacing='0'>
			<tr style='background: #333333;'>
				
						<td width='10%' style='color:#fff'>".$first."</td> 
						<td width='15%' style='color:#fff'>".$prev."</td>
						<td width='50%' style='color:#fff'>".$nav."</td>
						<td width='15%' style='color:#fff'>".$next."</td>
						<td width='10%' style='color:#fff'>".$last."</td>
					
		</tr>";

	$output .= "</table>
			<br />
			<br />
			
			<form name=\"srch\" method=\"post\" action=\"$self?action=$action&pg=$pageNum$sort_link\" onsubmit=\"return checkSearchForm();\">
			Seach for: <input type=\"text\" name=\"srch\" /> in 
			<Select NAME=\"field\">
			<Option VALUE=\"id\">ID</option>
			<Option VALUE=\"username\">Username</option>
			<Option VALUE=\"fname\">First Name</option>
			<Option VALUE=\"lname\">Last Name</option>
			<Option VALUE=\"email\">E-Mail</option>
			</Select>
			<input type=\"hidden\" name=\"searching\" value=\"yes\" />
			<input type=\"submit\" name=\"submitsrch\" value=\"Search\"  />
			</form>
			<br />";
					
	if ($_POST['searching'] == "yes") {
		

		//If they did not enter a search term we give them an error 
		if ($_POST['srch'] == "") { 
			$output .= "<p>You forgot to enter a search term</p>"; 
			displayFooter();
			exit;
		} 

		$find = $_POST['srch'];
		
		// We preform a bit of filtering 
		$find = strip_tags($find); 
		$find = trim ($find); 

		//Now we search for our search term, in the field the user specified 
		$data = $conn->query("SELECT * FROM sf_members WHERE $_POST[field] LIKE '%$find%'"); 

		$output .= "<table class='list' cellspacing='0' width='650px'>";
		$output .= "<tr style='background-image:url(/testserver/images/backgrounds/nav_01.jpg)'><td align='center' colspan='7'><strong>Results</strong></td></tr>"; 
		
		//And we display the results 
		while($row = $data->fetch_array()){ 
			$output .= "<tr>
          	  		<td>$row[id]</td>
            		<td>$row[username]</td>
					<td>$row[fname]</td>
            		<td>$row[lname]</td>
            		<td>$row[email]</td>
            		<td>".date('F d Y g:i A', strtotime($row['logged_in']))."</td>
            		<td>".date('F d Y g:i A', strtotime($row['logged_out']))."</td>
                </tr>";
		} 

		//This counts the number or results - and if there wasn't any it gives them a little message explaining that 
		$anymatches=$data->num_rows; 
		if ($anymatches == 0) { 
			$output .= "<tr><td align='center' colspan='7'>Sorry, but we can not find an entry to match your query</td></tr><br><br>"; 
		} 
		
		//And we remind them what they searched for 
		$output .= "<br /><strong>Searched For:</strong> " .$find; 
		$output .= "</table>";
	}
	
	return $output;
}	

function change_password($username, $old_password, $new_password){
	
	$conn = db_connect();
	$result = $conn->query("SELECT * FROM sf_members WHERE username = '$username' AND password = SHA1('$old_password') ");	

	if($result->num_rows <= 0){
		throw new myException("The old password you entered is incorrect. your password could not be changed. <br /><br /> Please try again. <br />");
	} else {
	
	$result = $conn->query("UPDATE sf_members SET password = SHA1('$new_password') WHERE username = '$username'");
	
		if(!$result){
			throw new myException('Password could not be changed.');
		}
	}

	return true;
}

function change_email($username, $old_email, $new_email){
		
		$conn = db_connect();
		$result = $conn->query("SELECT * FROM sf_members WHERE username = '$username'");
		$row = $result->fetch_array();
		if($row['email'] != $old_email){
			throw new myException('Old E-Mail is incorrect. E-Mail could not be changed.');
		}
		
		$result = $conn->query("SELECT * FROM sf_members WHERE email = '$new_email'");
		if($result->num_rows > 0){
			throw new myException ('The New E-mail address you entered is already in use. Please try another e-mail.');
		}
		
		$result = $conn->query("UPDATE sf_members SET email = '$new_email' WHERE username = '$username'");
	
		if(!$result){
			throw new myException('E-Mail could not be changed.');
		}

	return true;
}

function get_random_word($min_length, $max_length){

	// generate a random word
	$word = '';

	// remember to change this path to suit your system
	$dictionary = $_SERVER['DOCUMENT_ROOT'].'/testserver/scripts/dict/words/english.3';  // the ispell dictionary
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

function reset_password($email){
 
// get a random dictionary word b/w 6 and 13 chars in length
	$new_password = get_random_word(6, 13);
  
 	if($new_password==false){
 		throw new myException('Could not generate new password.');
 	}
  
  // add a number  between 0 and 999 to it
  // to make it a slightly better password
 	srand ((double) microtime() * 1000000);
  	$rand_number = rand(0, 999); 
  	$new_password .= $rand_number;
 
  // set user's password to this in database or return false
  	$conn = db_connect();
  	$result = $conn->query( "UPDATE sf_members SET password = SHA1('$new_password') WHERE email = '$email'");
	
	if (!$result){
    	throw new myException('Could not change password.');  // not changed
	} else {
    	return $new_password;  // changed successfully  
	}
}

function notify_password($email, $password){
    $conn = db_connect();
    $result = $conn->query("SELECT * FROM sf_members WHERE email = '$email'");

    if (!$result){
      throw new myException('Could not find email address.');  
    } elseif($result->num_rows==0){
		throw new myException('Could not find email address.');   // email not in db
	} else {
	    $row = $result->fetch_array();
    	$username = $row['username'];
   		$from = "From: support@strikeforcemusic.com \r\n";
    	$mesg ="$username, \r\n\r\n " 
			  ."Your password has been changed to: \r\n\r\n"
			  ."$password \r\n\r\n"
              ."Please change it next time you log in. \r\n\r\n"
			  ."You can goto http://www.strikeforcemusic.com/testserver/login.php to login.\r\n\r\n"
			  ."This is an automated response so you do not need to reply.\r\n"
			  ."Strike Force Music Technical Team";
      
      
      if (mail($email, 'Strike Force Music Login Information', $mesg, $from))
        return true;      
      else
        throw new myException('Could not send email.');
    }
}	

function send_email($subject, $message, $to = false){
    $conn = db_connect();
    $result = $conn->query("SELECT * FROM sf_members");

    if (!$result){
      throw new myException('Could not find email address.');  
    } elseif($result->num_rows==0){
		throw new myException('Could not find email address.');   // email not in db
	} else {
			$from = "From: support@StrikeForceMusic.com \r\n";
			$msg = "email = $email <br> to = $to ";
			
			if(!empty($to)){
				if (mail($to, $subject, $message, $from)){
					return true;      
				} else {
					return false;
				}
			} else {
				while($row = $result->fetch_array()){
					$email = $row['email'];
					mail($email, $subject, $message, $from);
				}
			}
    }
	return true;
}	

function get_posts($post = null){
	
	$conn = db_connect();
	$rowsPerPage = 5;
	$pageNum = 1;
	$nav = '';
	
	if(isset($_GET['pg'])){
	    $pageNum = $_GET['pg'];
	}
	
	$offset = ($pageNum - 1) * $rowsPerPage;
	
	if(isset($post)){
		$result = $conn->query("SELECT * FROM sf_posts WHERE id = '$post' ORDER BY id DESC LIMIT $offset, $rowsPerPage");
	} else {
		$result = $conn->query("SELECT * FROM sf_posts ORDER BY id DESC LIMIT $offset, $rowsPerPage");
	}
		
	$num = $result->num_rows;

	while ($row = $result->fetch_array()){
		$search = $conn->query("SELECT * FROM sf_members WHERE username = '".$row['author']."' ");
		$day = date("F-d-Y ", strtotime($row['time']));
		$time = date("g:i A ", strtotime($row['time']));
		if($pic = $search->fetch_array()){
			$display_pic = "<img class='profile_pic' src='".$pic['profile_pic']."' onclick=\"window.location.href='/testserver/biography.php'\" style='cursor:pointer;'/>";
		} else {
			$display_pic = "";
		}	
		
		if(!$row['title']){
			$display_title = "";
		} else {
			$display_title = "<span class='post_title'>".$row['title']."</span>";
		}
		
		if(!$row['author']){
			$display_author = "";
		} else {
			$display_author = $row['author'].": "; 
		}	
			
		$output = displayPost("auto", "
		<div class='post_date'> Posted on: ".$day."&nbsp;|&nbsp;".$time."</div>
		<div class='post_layout'>".$display_pic."<br /><hr />".$display_title."<hr />
			<div class='post_content'>
				<span class='author'>".$display_author."&nbsp;</span>".$row['content']."
			</div>
		</div>");
	}
	
	$query   = "SELECT COUNT(*) AS numrows FROM sf_posts";
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
	
	echo "<div id='content'><span class='overlay'></span>
	<table class='list' width='100%' cols='5' cellpadding='0' cellspacing='0'>
			<tr >
				
						<td width='10%' style='color:#fff'>".$first."</td> 
						<td width='15%' style='color:#fff'>".$prev."</td>
						<td width='50%' style='color:#fff'>".$nav."</td>
						<td width='15%' style='color:#fff'>".$next."</td>
						<td width='10%' style='color:#fff'>".$last."</td>
					
		</tr></table></div>";
		
	return $output;		
		
}
	
function list_posts(){
	$output = "";

//Display a list of all the posts
	
	$rowsPerPage = 5;
	$pageNum = 1;
	$sort = '';
	$sort_type = 'desc';
	$order = "ORDER BY id desc";
	$self = $_SERVER['PHP_SELF'];
	$nav  = '';
	$search = '';
	$conn = db_connect();
	
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

	//Connect to the database		
	$conn = db_connect();
	
	$sql = "SELECT * FROM sf_posts $order $search LIMIT $offset, $rowsPerPage";
	$result = $conn->query($sql);
	
	$num = $result->num_rows;
	
	$output .= "<div>
	<table class='list' cols='6' cellspacing='0' cellpadding='0' width='680px'>
		<div align='left'><img src='/testserver/images/buttons/add.jpg' onclick=\"window.location.href='$self?action=$action&do=addPost&pg=$pageNum$sort_link'\" alt='Add Post' style='cursor:pointer'></div>
    	<tr style='background-image:url(/testserver/images/backgrounds/nav_01.jpg)'>
			<td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=id&type=$sort_type'>ID</a></strong></span></td>
            <td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=author&type=$sort_type'>Author</a></strong></span></td>
            <td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=title&type=$sort_type'>Title</a></strong></span></td>
            <td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=content&type=$sort_type'>Content</a></strong></span></td>
            <td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=time&type=$sort_type'>Time</a></strong></span></td>
			<td align='center'><span colspan='1' class='link'>&nbsp;</span></td>
       </tr>";
	   
	   
	$result = $conn->query("SELECT * FROM sf_posts $order $search LIMIT $offset, $rowsPerPage");
	while ($row = $result->fetch_array()){	
		$output .= "<tr>
          	  		<td><span><a href='$self?action=$action&do=editPost&postID=$row[id]&pg=$pageNum$sort_link' title='Edit Post'>$row[id]</a></span></td>
            		<td>$row[author]</td>
					<td>$row[title]</td>
            		<td>".neat_trim(strip_tags($row['content']), 50)."</td>
            		<td>".date('F d Y g:i A', strtotime($row['time']))."</td>
					<td><img src='/testserver/images/buttons/delete.jpg' onclick='if(confirm(\"Are you sure you want to delete this post?\")){window.location.href=\"$self?action=$action&do=deletePost&postID=$row[id]&pg=$pageNum$sort_link\"}' alt='Delete Post' style='cursor:pointer;'></td>
                </tr>";
	}
	
	// how many rows we have in database
	$query   = "SELECT COUNT(*) AS numrows FROM sf_posts";
	$result  = $conn->query($query);
	$row     = $result->fetch_array(MYSQL_ASSOC);
	$numrows = $row['numrows'];

	// how many pages we have when using paging?
	$maxPage = ceil($numrows/$rowsPerPage);

	for($page = 1; $page <= $maxPage; $page++){
	
		if ($page == $pageNum){
	      $nav .= " $page "; // no need to create a link to current page
	   	} else {
   	   		$nav .= " <span class='link'><a href=\"$self?action=$action&pg=$page$sort_link\">$page</a></span> ";
  	 	} 
	}	
	
	if ($pageNum > 1){
		$page  = $pageNum - 1;
   		$prev  = " <span class='link'><a href=\"$self?action=$action&pg=$page$sort_link\"> Prev </a></span> ";
   		$first = " <span class='link'><a href=\"$self?action=$action&pg=1$sort_link\"> |<< </a></span> ";
	} else {
  		$prev  = '&nbsp;'; // we're on page one, don't print previous link
   		$first = '&nbsp;'; // nor the first page link
	}

	if ($pageNum < $maxPage){
		$page = $pageNum + 1;
   		$next = " <span class='link'><a href=\"$self?action=$action&pg=$page$sort_link\"> Next </a></span> ";
   		$last = " <span class='link'><a href=\"$self?action=$action&pg=$maxPage$sort_link\"> >>| </a></span> ";
	} else {
   		$next = '&nbsp;'; // we're on the last page, don't print next link
   		$last = '&nbsp;'; // nor the last page link
	}

	// print the navigation link 
	$output .= "</table><table class='list' width='680' cols='5' cellpadding='0' cellspacing='0'>
			<tr style='background: #333333;'>
				
						<td width='10%' style='color:#fff'>".$first."</td> 
						<td width='15%' style='color:#fff'>".$prev."</td>
						<td width='50%' style='color:#fff'>".$nav."</td>
						<td width='15%' style='color:#fff'>".$next."</td>
						<td width='10%' style='color:#fff'>".$last."</td>
					
		</tr>";

	$output .= "</table></div>
			<br />
			<br />
			
			<form name=\"srch\" method=\"post\" action=\"$self?action=$action&pg=$pageNum$sort_link\" onsubmit=\"return checkSearchForm();\">
			Seach for: <input type=\"text\" name=\"srch\" /> in 
			<Select NAME=\"field\">
			<Option VALUE=\"id\">ID</option>
			<Option VALUE=\"author\">Author</option>
			<Option VALUE=\"title\">Title</option>
			<Option VALUE=\"content\">Content</option>
			<Option VALUE=\"time\">Time</option>
			</Select>
			<input type=\"hidden\" name=\"searching\" value=\"yes\" />
			<input type=\"submit\" name=\"submitsrch\" value=\"Search\"  />
			</form>
			<br />";
					
	if ($_POST['searching'] == "yes") {
		

		//If they did not enter a search term we give them an error 
		if ($_POST['srch'] == "") { 
			$output .= "<p>You forgot to enter a search term</p>"; 
			displayFooter();
			exit;
		} 

		$find = $_POST['srch'];
		
		// We preform a bit of filtering 
		$find = strip_tags($find); 
		$find = trim ($find); 

		//Now we search for our search term, in the field the user specified 
		$data = $conn->query("SELECT * FROM sf_posts WHERE $_POST[field] LIKE '%$find%'"); 

		$output .= "<table class='list' cellspacing='0' width='680px'>";
		$output .= "<tr style='background-image:url(/testserver/images/backgrounds/nav_01.jpg)'><td align='center' colspan='5'><strong>Results</strong></td></tr>"; 
		
		//And we display the results 
		while($row = $data->fetch_array()){ 
			$output .= "<tr>
          	  		<td><span><a href='#'>$row[id]</a></span></td>
            		<td>$row[title]</td>
					<td>$row[author]</td>
            		<td>".strip_tags($row['content'])."</td>
            		<td>".date('F d Y g:i A', strtotime($row['time']))."</td>
                </tr>";
		} 

		//This counts the number or results - and if there wasn't any it gives them a little message explaining that 
		$anymatches=$data->num_rows; 
		if ($anymatches == 0) { 
			$output .= "<tr><td align='center' colspan='6'>Sorry, but we can not find an entry to match your query</td></tr><br><br>"; 
		} 
		
		//And we remind them what they searched for 
		$output .= "<br /><strong>Searched For:</strong> " .$find; 
		$output .= "</table>";
	}
	
	//Lets add a post to the database
	if($do == "addPost"){
		if(isset($_GET['status'])){
			$status = $_GET['status'];
			$output .= displayThis('Post Added','');
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
				
					
					$result = $conn->query("INSERT INTO sf_posts (id, author, title, content, time) VALUES (NULL, '$_SESSION[valid_user]', '$v_title','$v_content', NOW())");
					
					if($result){
						$output .= displayThis('', "<meta http-equiv='refresh' content='0;URL=$self?action=$action&do=$do&status=done&pg=$pageNum$sort_link'>");	
						return $output;
					} else {
						throw new myException('Changes to the post could not be saved at this time.');
					}
				} elseif(empty($v_content)) {
					$output .= displayThis("Error", "A Post cannot be added with no content. Please try again.");
				}
		
			}
			
			if(isset($_POST['addPost_cancel'])){
				$output .= displayThis("Cancelled", "Post was not added.");
				return $output;	
			}
				
		}
			
		$output .= displayThis("<div align='left'>Add Post</div>", "
			<div align='left'>
				<form name='addPost' method='post' action='$self?action=$action&do=$do&submit=submitPost&pg=$pageNum$sort_link'>
					Title: <input name='title' type='text' size='120' value='$row[title]'  /><br />
					Content: <textarea class='ckeditor' id='editor1' name='content' cols='90' rows='10'></textarea><br />
					<input align='middle' name='addPost_submit' type='submit' value='Add Post' />
					<input align='middle' name='addPost_cancel' type='submit' value='Cancel' />
				</form>
			</div>");
	}
	
	//Lets edit a post in the database
	if($do == "editPost"){
		if(isset($_GET['postID'])){
			$postID = $_GET['postID'];
		}
		
		if(isset($_GET['status'])){
			$status = $_GET['status'];
			$output .= displayThis('Post Edited','');
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
				
					$update = $conn->query("UPDATE sf_posts SET title='$v_title', content='$v_content' WHERE id='$postID' LIMIT 1");
					 
					if($update){
						$output .= displayThis('', "<meta http-equiv='refresh' content='0;URL=$self?action=$action&do=$do&status=done&postID=$postID&pg=$pageNum$sort_link'>");
					} else {
						throw new myException('Changes to the post could not be saved at this time.');
					}
				} elseif(empty($v_content)) {
					$output .= displayThis("Error", "A Post cannot be edited with no content. Please try again.");
				}
		
			}
			
			if(isset($_POST['editPost_cancel'])){
				$output .= displayThis("Cancelled", "Post was not edited.");
				return $output;	
			}
				
		}
		
		if(!empty($postID)){
				
			$result = $conn->query("SELECT * FROM sf_posts WHERE id = '$postID'"); 
			$row = $result->fetch_array();
				
			$output .= displayThis("<div align='left'>Edit Post</div>", "
				<div align='left'>
					<form name='editPost' method='post' action='$self?action=$action&do=$do&postID=$row[id]&submit=submitPost&pg=$pageNum$sort_link'>
						Post # $row[id] <br />
						Date: ".date('F d Y', strtotime($row['time']))."<br />
						Time: ".date('g:i A', strtotime($row['time']))."<br />
						Author: $row[author]<br /> 
						Title: <input name='title' type='text' size='120' value='$row[title]'  /><br />
						Content: <textarea class='ckeditor' id='editor1' name='content' cols='90' rows='10'>".$row['content']."</textarea><br />
						<input align='middle' name='editPost_submit' type='submit' value='Edit Post' />
						<input align='middle' name='editPost_cancel' type='submit' value='Cancel' />
					</form>
				</div>");
				
		}
	}
	
	//Lets delete a post
	if($do == "deletePost"){
	
		if(isset($_GET['status'])){
			$output .= displayThis('Post Deleted','');
			return $output;
		}
		
		if(isset($_GET['postID'])){
			$postID = $_GET['postID'];
			
			$result = $conn->query("DELETE FROM sf_posts WHERE id = '$postID' LIMIT 1");															
			if($result){
				$output .= displayThis("", "<meta http-equiv='refresh' content='0;URL=$self?action=$action&do=$do&status=done&postID=$postID&pg=$pageNum$sort_link'>");
			} else {
				throw new myException("Changes to the post could not be saved at this time.");
			}
		} else {
			$output .= displayThis("Cancelled", "The post was not deleted");
			return $output;
		}
	}
	
	return $output;
}

function neat_trim($str, $n, $delim='...') { 
   $len = strlen($str); 
   if ($len > $n) { 
       preg_match('/(.{' . $n . '}.*?)\b/', $str, $matches); 
       return rtrim($matches[1]) . $delim; 
   } 
   else { 
       return $str; 
   } 
} 

function rteSafe($strText) {
		//returns safe code for preloading in the RTE
			$tmpString = $strText;
	
		//convert all types of single quotes
			$tmpString = str_replace(chr(145), chr(39), $tmpString);
			$tmpString = str_replace(chr(146), chr(39), $tmpString);
			$tmpString = str_replace("'", "&#39;", $tmpString);
	
		//convert all types of double quotes
			$tmpString = str_replace(chr(147), chr(34), $tmpString);
			$tmpString = str_replace(chr(148), chr(34), $tmpString);
		//	$tmpString = str_replace("\"", "\"", $tmpString);
	
		//replace carriage returns & line feeds
			$tmpString = str_replace(chr(10), " ", $tmpString);
			$tmpString = str_replace(chr(13), " ", $tmpString);
	
			return $tmpString;
	}

function list_slideshows(){
	$output = "";

//Display a list of all the posts
	
	$rowsPerPage = 20;
	$pageNum = 1;
	$sort = '';
	$sort_type = 'desc';
	$order = "ORDER BY `id` desc";
	$self = $_SERVER['PHP_SELF'];
	$nav  = '';
	$search = '';
	$conn = db_connect();
	
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
	
	//If $_GET['sort'] defined, sort results
	if(isset($_GET['sort'])){
		$sort = $_GET['sort'];
		$sort_type = $_GET['type'];
		$sort_link = "&sort=$sort&type=$sort_type";
		
		if ($sort_type == "asc"){
			$sort_type = "desc";
			$order = "ORDER BY `$sort` $sort_type";
		} else if ($sort_type == "desc") {
			$sort_type = "asc";
			$order = "ORDER BY `$sort` $sort_type";
		} 
	}
	
	$offset = ($pageNum - 1) * $rowsPerPage;

	//Connect to the database		
	$sql = "SELECT * FROM sf_slideshows $order $search LIMIT $offset, $rowsPerPage";
	$result = $conn->query($sql);
	
	$num = $result->num_rows;
	
	$output .= "
	<table class='list' cols='6' cellspacing='0' cellpadding='0'  width='680px'>
		<div align='left'><img src='/testserver/images/buttons/add.jpg' onclick=\"window.location.href='$self?action=$action&do=add&pg=$pageNum$sort_link'\" alt='Add Post' style='cursor:pointer'></div>
    	<tr style='color:#666666; background-image:url(/testserver/images/backgrounds/nav_01.jpg)'>
			<td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=id&type=$sort_type'>ID</a></strong></span></td>
			<td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=show_name&type=$sort_type'>Slideshow Name</a></strong></span></td>
            <td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=type&type=$sort_type'>Type</a></strong></span></td>
            <td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=order&type=$sort_type'>Order</a></strong></span></td>
            <td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=url&type=$sort_type'>URL</a></strong></span></td>
			<td align='center'><span colspan='1' class='link'>&nbsp;</span></td>
       </tr>";
	   
	while ($row = $result->fetch_array()){	
		$output .= "<tr>
					<td><a href='$self?action=$action&do=edit&id=$row[id]&pg=$pageNum$sort_link'>$row[id]</a></td>
          	  		<td>$row[show_name]</td>
            		<td>$row[type]</td>
					<td>$row[order]</td>
            		<td>".neat_trim($row['url'], 20)."</td>
					<td><img src='/testserver/images/buttons/delete.jpg' onclick='if(confirm(\"Are you sure you want to delete this slideshow $row[type]?\")){window.location.href=\"$self?action=$action&do=delete&id=$row[id]&pg=$pageNum$sort_link\"}' alt='Delete Post' style='cursor:pointer;'></td>
                </tr>";
	}
	
	// how many rows we have in database
	$query   = "SELECT COUNT(*) AS numrows FROM sf_slideshows";
	$result  = $conn->query($query);
	$row     = $result->fetch_array(MYSQL_ASSOC);
	$numrows = $row['numrows'];

	// how many pages we have when using paging?
	$maxPage = ceil($numrows/$rowsPerPage);

	for($page = 1; $page <= $maxPage; $page++){
	
		if ($page == $pageNum){
	      $nav .= " $page "; // no need to create a link to current page
	   	} else {
   	   		$nav .= " <span class='link'><a href=\"$self?action=$action&pg=$page$sort_link\">$page</a></span> ";
  	 	} 
	}	
	
	if ($pageNum > 1){
		$page  = $pageNum - 1;
   		$prev  = " <span class='link'><a href=\"$self?action=$action&pg=$page$sort_link\"> Prev </a></span> ";
   		$first = " <span class='link'><a href=\"$self?action=$action&pg=1$sort_link\"> |<< </a></span> ";
	} else {
  		$prev  = '&nbsp;'; // we're on page one, don't print previous link
   		$first = '&nbsp;'; // nor the first page link
	}

	if ($pageNum < $maxPage){
		$page = $pageNum + 1;
   		$next = " <span class='link'><a href=\"$self?action=$action&pg=$page$sort_link\"> Next </a></span> ";
   		$last = " <span class='link'><a href=\"$self?action=$action&pg=$maxPage$sort_link\"> >>| </a></span> ";
	} else {
   		$next = '&nbsp;'; // we're on the last page, don't print next link
   		$last = '&nbsp;'; // nor the last page link
	}

	// print the navigation link 
	$output .= "</table><table class='list' width='680' cols='5' cellpadding='0' cellspacing='0'>
			<tr style='background: #333333;'>
				
						<td width='10%' style='color:#fff'>".$first."</td> 
						<td width='15%' style='color:#fff'>".$prev."</td>
						<td width='50%' style='color:#fff'>".$nav."</td>
						<td width='15%' style='color:#fff'>".$next."</td>
						<td width='10%' style='color:#fff'>".$last."</td>			
		</tr>";

	$output .= "</table>
			<br />
			<br />
			
			<form name=\"srch\" method=\"post\" action=\"$self?action=$action&pg=$pageNum$sort_link\" onsubmit=\"return checkSearchForm();\">
			Seach for: <input type=\"text\" name=\"srch\" /> in 
			<Select NAME=\"field\">
			<Option VALUE=\"id\">ID</option>
			<Option VALUE=\"show_name\">Slideshow Name</option>
			<Option VALUE=\"type\">Type</option>
			<Option VALUE=\"order\">Order</option>
			<Option VALUE=\"url\">URL</option>
			</Select>
			<input type=\"hidden\" name=\"searching\" value=\"yes\" />
			<input type=\"submit\" name=\"submitsrch\" value=\"Search\"  />
			</form>
			<br />";
					
	if ($_POST['searching'] == "yes") {
		

		//If they did not enter a search term we give them an error 
		if ($_POST['srch'] == "") { 
			$output .= "<p>You forgot to enter a search term</p>"; 
			displayFooter();
			exit;
		} 

		$find = $_POST['srch'];
		
		// We preform a bit of filtering 
		$find = strip_tags($find); 
		$find = trim ($find); 

		//Now we search for our search term, in the field the user specified 
		$data = $conn->query("SELECT * FROM sf_slideshows WHERE $_POST[field] LIKE '%$find%'"); 

		$output .= "<table class='list' cellspacing='0'>";
		$output .= "<tr style='background-image:url(/testserver/images/backgrounds/nav_01.jpg)'><td align='center' colspan='6'><strong>Results</strong></td></tr>"; 
		
		//And we display the results 
		while($row = $data->fetch_array()){ 
			$output .= "<tr>
					<td><a href='$self?action=$action&do=edit&id=$row[id]&pg=$pageNum$sort_link'>$row[id]</a></td>
          	  		<td>$row[show_name]</td>
            		<td>$row[type]</td>
					<td>$row[order]</td>
            		<td>".neat_trim($row['url'], 20)."</td>
					<td><img src='/testserver/images/buttons/delete.jpg' onclick='if(confirm(\"Are you sure you want to delete this slideshow $row[type]?\")){window.location.href=\"$self?action=$action&do=delete&id=$row[id]&pg=$pageNum$sort_link\"}' alt='Delete Post' style='cursor:pointer;'></td>
                </tr>";
		} 

		//This counts the number or results - and if there wasn't any it gives them a little message explaining that 
		$anymatches=$data->num_rows; 
		if ($anymatches == 0) { 
			$output .= "<tr><td align='center' colspan='6'>Sorry, but we can not find an entry to match your query</td></tr><br><br>"; 
		} 
		
		//And we remind them what they searched for 
		$output .= "<br /><strong>Searched For:</strong> " .$find; 
		$output .= "</table>";
	}
	
	//Lets add a post to the database
	if($do == "add"){
		if(isset($_GET['status'])){
			$status = $_GET['status'];
			$output .= displayThis('Added to Slideshow Database','');
			return $output;
		}
		
		if(isset($_GET['submit'])){
			$submit = $_GET['submit'];
	
			$v_showname = $_POST['showname'];
			$v_width = $_POST['width'];
			$v_height = $_POST['height'];
			$v_speed = $_POST['speed'];
			$v_type = $_POST['type'];
			$v_order = $_POST['order'];
			$v_url = $_POST['url'];
			
			
			if(empty($v_width)){
				$v_width = "700";
			}
			
			if(empty($v_height)){
				$v_height = "467";
			}
			
			if(empty($v_speed)){
				$v_speed = "5";
			}
			
			if(empty($v_order)){
				$v_order = '0';
			}
			
			if(empty($v_url)){
				$v_showname = "&nbsp;";
			}
			
			if(isset($_POST['add_submit'])){
				if(!empty($v_showname)){
				
					
					$result = $conn->query("INSERT INTO sf_slideshows (id, show_name, width, height, speed, `type`, `order`, url, title) VALUES (NULL, '$v_showname', '$v_width', '$v_height','$v_speed', '$v_type','$v_order','$v_url', NULL)");
					
					if($result){
						$output .= displayThis('', "<meta http-equiv='refresh' content='0;URL=$self?action=$action&do=$do&status=done&pg=$pageNum$sort_link'>");	
						return $output;
					} else {
						throw new myException('Changes to the database could not be saved at this time.');
					}
				} elseif(empty($v_showname)) {
					$output .= displayThis("Error", "A Slideshow cannot be added with no Slideshow Name. Please try again.");
				}
		
			}
			
			if(isset($_POST['add_cancel'])){
				$output .= displayThis("Cancelled", "Slideshow was not added.");
				return $output;	
			}
				
		}
			
		$output .= displayThis("<div align='left'>Add to Slideshow</div>", "<div align='left'>
				<form name='add' method='post' action='$self?action=$action&do=$do&submit=submitPost&pg=$pageNum$sort_link'>
					Slideshow Name: <input name='showname' type='text' size='65' value='$row[show_name]'  /><br />
					Width: <input name='width' type='text' size='4' value='$row[width]'  />
					Height: <input name='height' type='text' size='4' value='$row[height]'  /><br />
					Speed: <input name='speed' type='text' size='4' value='$row[speed]'  /><br />
					Type: 	<select name='type'>
								<option value='image'>Image</option>
								<option value='song'>Song</option>
							</select><br />
					Order: <input name='order' type='text' size='10' value='$row[order]'  /><br />
					URL: <input name='url' type='text' size='65' value='$row[url]'  /><br />
					<input align='middle' name='add_submit' type='submit' value='Add to Slideshow' />
					<input align='middle' name='add_cancel' type='submit' value='Cancel' />
				</form>
			</div>");
	}
	
	//Lets edit a post in the database
	if($do == "edit"){
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}
		
		if(isset($_GET['status'])){
			$status = $_GET['status'];
			$output .= displayThis('Database Edited','');
		}
		
		if(isset($_GET['submit'])){
			$submit = $_GET['submit'];
	
			$v_showname = $_POST['showname'];
			$v_width = $_POST['width'];
			$v_height = $_POST['height'];
			$v_speed = $_POST['speed'];
			$v_type = $_POST['type'];
			$v_order = $_POST['order'];
			$v_url = $_POST['url'];
			
			
			if(empty($v_width)){
				$v_width = "700";
			}
			
			if(empty($v_height)){
				$v_height = "467";
			}
			
			if(empty($v_speed)){
				$v_speed = "5";
			}
			
			if(empty($v_order)){
				$v_order = '0';
			}
			
			if(empty($v_url)){
				$v_showname = "&nbsp;";
			}	
			
			if(isset($_POST['edit_submit'])){
				if(!empty($v_showname)){
				
					$update = $conn->query("UPDATE sf_slideshows SET show_name = '$v_showname', width = '$v_width', height = '$v_height', speed = '$v_speed', `type` = '$v_type', `order` = '$v_order', url = '$v_url' WHERE id='$ID' LIMIT 1");
					 
					if($update){
						$output .= displayThis('', "<meta http-equiv='refresh' content='0;URL=$self?action=$action&do=$do&status=done&pg=$pageNum$sort_link'>");	
						return $output;
					} else {
						throw new myException('Changes to the database could not be saved at this time.');
					}
				} elseif(empty($v_showname)) {
					$output .= displayThis("Error", "A Slideshow cannot be added with no Slideshow Name. Please try again.");
				}
		
			}
			
			if(isset($_POST['edit_cancel'])){
				$output .= displayThis("Cancelled", "Post was not edited.");
				return $output;	
			}
				
		}
		
		if(!empty($ID)){
				
			$result = $conn->query("SELECT * FROM sf_slideshows WHERE id = '$ID'"); 
			$row = $result->fetch_array();
			
			if($row['type'] == 'image'){	
				$default_type = "<option value='image'>Image</option>
				<option value='song'>Song</option>";
			} else {
				$default_type =  "<option value='song'>Song</option>
				<option value='image'>Image</option>";
			}
				
			$output .= displayThis("<div align='left'>Edit Slideshow</div>", "
				<div align='left'>
					<form name='edit' method='post' action='$self?action=$action&do=$do&id=$row[id]&submit=submitPost&pg=$pageNum$sort_link'>
						ID# $row[id] <br />
						Slideshow Name: <input name='showname' type='text' size='65' value='$row[show_name]'  /><br />
						Width: <input name='width' type='text' size='4' value='$row[width]'  />
						Height: <input name='height' type='text' size='4' value='$row[height]'  /><br />
						Speed: <input name='speed' type='text' size='2' value='$row[speed]'  /><br />
						Type:&nbsp;	<select name='type'>". $default_type ."</select><br />
						Order: <input name='order' type='text' size='10' value='$row[order]'  /><br />
						URL: <input name='url' type='text' size='65' value='$row[url]'  /><br />
						<input align='middle' name='edit_submit' type='submit' value='Edit Slideshow' />
						<input align='middle' name='edit_cancel' type='submit' value='Cancel' />
					</form>
				</div>");
				
		}
	}
	
	//Lets delete a post
	if($do == "delete"){
	
		if(isset($_GET['status'])){
			$output .= displayThis('Deleted from Slideshow Database','');
			return $output;
		}
		
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
			
			$result = $conn->query("DELETE FROM sf_slideshows WHERE id = '$ID' LIMIT 1");															
			if($result){
				$output .= displayThis("", "<meta http-equiv='refresh' content='0;URL=$self?action=$action&do=$do&status=done&id=$ID&pg=$pageNum$sort_link'>");
			} else {
				throw new myException("Changes to the database could not be saved at this time.");
			}
		} else {
			$output .= displayThis("Cancelled", "The slideshow was not deleted");
			return $output;
		}
	}
	
	return $output;
}

function list_music(){
	$output = "";

//Display a list of all the posts
	
	$rowsPerPage = 20;
	$pageNum = 1;
	$sort = '';
	$sort_type = 'desc';
	$order = "ORDER BY `id` desc";
	$self = $_SERVER['PHP_SELF'];
	$nav  = '';
	$search = '';
	$conn = db_connect();
	
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
	
	//If $_GET['sort'] defined, sort results
	if(isset($_GET['sort'])){
		$sort = $_GET['sort'];
		$sort_type = $_GET['type'];
		$sort_link = "&sort=$sort&type=$sort_type";
		
		if ($sort_type == "asc"){
			$sort_type = "desc";
			$order = "ORDER BY `$sort` $sort_type";
		} else if ($sort_type == "desc") {
			$sort_type = "asc";
			$order = "ORDER BY `$sort` $sort_type";
		} 
	}
	
	$offset = ($pageNum - 1) * $rowsPerPage;

	//Connect to the database		
	$sql = "SELECT * FROM sf_music $order $search LIMIT $offset, $rowsPerPage";
	$result = $conn->query($sql);
	
	$num = $result->num_rows;
	
	$output .= "
	<table class='list' cols='6' cellspacing='0' cellpadding='0'  width='680px'>
		<div align='left'><img src='/testserver/images/buttons/add.jpg' onclick=\"window.location.href='$self?action=$action&do=add&pg=$pageNum$sort_link'\" alt='Add Post' style='cursor:pointer'></div>
    	<tr style='color:#666666; background-image:url(/testserver/images/backgrounds/nav_01.jpg)'>
			<td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=id&type=$sort_type'>ID</a></strong></span></td>
			<td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=album&type=$sort_type'>Album</a></strong></span></td>
            <td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=title&type=$sort_type'>Title</a></strong></span></td>
            <td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=artist&type=$sort_type'>Artist</a></strong></span></td>
            <td align='center'><span colspan='1' class='link'><strong><a href='$self?action=$action&pg=$pageNum&sort=url&type=$sort_type'>URL</a></strong></span></td>
			<td align='center'><span colspan='1' class='link'>&nbsp;</span></td>
       </tr>";
	   
	while ($row = $result->fetch_array()){	
		$output .= "<tr>
					<td><a href='$self?action=$action&do=edit&id=$row[id]&pg=$pageNum$sort_link'>$row[id]</a></td>
          	  		<td>$row[album]</td>
            		<td>$row[title]</td>
					<td>$row[artist]</td>
            		<td>".neat_trim($row['url'], 20)."</td>
					<td><img src='/testserver/images/buttons/delete.jpg' onclick='if(confirm(\"Are you sure you want to delete this slideshow $row[type]?\")){window.location.href=\"$self?action=$action&do=delete&id=$row[id]&pg=$pageNum$sort_link\"}' alt='Delete Post' style='cursor:pointer;'></td>
                </tr>";
	}
	
	// how many rows we have in database
	$query   = "SELECT COUNT(*) AS numrows FROM sf_music";
	$result  = $conn->query($query);
	$row     = $result->fetch_array(MYSQL_ASSOC);
	$numrows = $row['numrows'];

	// how many pages we have when using paging?
	$maxPage = ceil($numrows/$rowsPerPage);

	for($page = 1; $page <= $maxPage; $page++){
	
		if ($page == $pageNum){
	      $nav .= " $page "; // no need to create a link to current page
	   	} else {
   	   		$nav .= " <span class='link'><a href=\"$self?action=$action&pg=$page$sort_link\">$page</a></span> ";
  	 	} 
	}	
	
	if ($pageNum > 1){
		$page  = $pageNum - 1;
   		$prev  = " <span class='link'><a href=\"$self?action=$action&pg=$page$sort_link\"> Prev </a></span> ";
   		$first = " <span class='link'><a href=\"$self?action=$action&pg=1$sort_link\"> |<< </a></span> ";
	} else {
  		$prev  = '&nbsp;'; // we're on page one, don't print previous link
   		$first = '&nbsp;'; // nor the first page link
	}

	if ($pageNum < $maxPage){
		$page = $pageNum + 1;
   		$next = " <span class='link'><a href=\"$self?action=$action&pg=$page$sort_link\"> Next </a></span> ";
   		$last = " <span class='link'><a href=\"$self?action=$action&pg=$maxPage$sort_link\"> >>| </a></span> ";
	} else {
   		$next = '&nbsp;'; // we're on the last page, don't print next link
   		$last = '&nbsp;'; // nor the last page link
	}

	// print the navigation link 
	$output .= "</table><table class='list' width='680' cols='5' cellpadding='0' cellspacing='0'>
			<tr style='background: #333333;'>
				
						<td width='10%' style='color:#fff'>".$first."</td> 
						<td width='15%' style='color:#fff'>".$prev."</td>
						<td width='50%' style='color:#fff'>".$nav."</td>
						<td width='15%' style='color:#fff'>".$next."</td>
						<td width='10%' style='color:#fff'>".$last."</td>			
		</tr>";

	$output .= "</table>
			<br />
			<br />
			
			<form name=\"srch\" method=\"post\" action=\"$self?action=$action&pg=$pageNum$sort_link\" onsubmit=\"return checkSearchForm();\">
			Seach for: <input type=\"text\" name=\"srch\" /> in 
			<Select NAME=\"field\">
			<Option VALUE=\"id\">ID</option>
			<Option VALUE=\"album\">Album</option>
			<Option VALUE=\"title\">Title</option>
			<Option VALUE=\"artist\">Artist</option>
			<Option VALUE=\"url\">URL</option>
			</Select>
			<input type=\"hidden\" name=\"searching\" value=\"yes\" />
			<input type=\"submit\" name=\"submitsrch\" value=\"Search\"  />
			</form>
			<br />";
					
	if ($_POST['searching'] == "yes") {
		

		//If they did not enter a search term we give them an error 
		if ($_POST['srch'] == "") { 
			$output .= "<p>You forgot to enter a search term</p>"; 
			displayFooter();
			exit;
		} 

		$find = $_POST['srch'];
		
		// We preform a bit of filtering 
		$find = strip_tags($find); 
		$find = trim ($find); 

		//Now we search for our search term, in the field the user specified 
		$data = $conn->query("SELECT * FROM sf_music WHERE $_POST[field] LIKE '%$find%'"); 

		$output .= "<table class='list' cellspacing='0'>";
		$output .= "<tr style='background-image:url(/testserver/images/backgrounds/nav_01.jpg)'><td align='center' colspan='6'><strong>Results</strong></td></tr>"; 
		
		//And we display the results 
		while($row = $data->fetch_array()){ 
			$output .= "<tr>
					<td><a href='$self?action=$action&do=edit&id=$row[id]&pg=$pageNum$sort_link'>$row[id]</a></td>
          	  		<td>$row[album]</td>
            		<td>$row[title]</td>
					<td>$row[artist]</td>
            		<td>".neat_trim($row['url'], 20)."</td>
					<td><img src='/testserver/images/buttons/delete.jpg' onclick='if(confirm(\"Are you sure you want to delete this slideshow $row[type]?\")){window.location.href=\"$self?action=$action&do=delete&id=$row[id]&pg=$pageNum$sort_link\"}' alt='Delete Post' style='cursor:pointer;'></td>
                </tr>";
		} 

		//This counts the number or results - and if there wasn't any it gives them a little message explaining that 
		$anymatches=$data->num_rows; 
		if ($anymatches == 0) { 
			$output .= "<tr><td align='center' colspan='6'>Sorry, but we can not find an entry to match your query</td></tr><br><br>"; 
		} 
		
		//And we remind them what they searched for 
		$output .= "<br /><strong>Searched For:</strong> " .$find; 
		$output .= "</table>";
	}
	
	//Lets add a post to the database
	if($do == "add"){
		if(isset($_GET['status'])){
			$status = $_GET['status'];
			$output .= displayThis('Added to Music Database','');
			return $output;
		}
		
		if(isset($_GET['submit'])){
			$submit = $_GET['submit'];
	
			$v_album = $_POST['album'];
			$v_title = $_POST['title'];
			$v_artist = $_POST['artist'];
			$v_url = $_POST['url'];			
			
			if(empty($v_title)){
				$v_title = "&nbsp;";
			}
			if(empty($v_artist)){
				$v_artist = "Strike Force";
			}
			if(empty($v_url)){
				$v_url = "&nbsp;";
			}
			
			if(isset($_POST['add_submit'])){
				if(!empty($v_album)){
				
					
					$result = $conn->query("INSERT INTO sf_music (id, album, `title`, artist, url) VALUES (NULL, '$v_album', '$v_title', '$v_artist','$v_url')");
					
					if($result){
						$output .= displayThis('', "<meta http-equiv='refresh' content='0;URL=$self?action=$action&do=$do&status=done&pg=$pageNum$sort_link'>");	
						return $output;
					} else {
						throw new myException('Changes to the database could not be saved at this time.');
					}
				} elseif(empty($v_album)) {
					$output .= displayThis("Error", "A song cannot be added with no album name. Please try again.");
				}
		
			}
			
			if(isset($_POST['add_cancel'])){
				$output .= displayThis("Cancelled", "Song was not added.");
				return $output;	
			}
				
		}
			
		$output .= displayThis("<div align='center'>Add to Music</div>", "
				<form name='add' method='post' action='$self?action=$action&do=$do&submit=submitPost&pg=$pageNum$sort_link'>
					Album:&nbsp; <input name='album' type='text' size='65' value='$row[album]'  /><br />
					Title:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input name='title' type='text' size='65' value='$row[title]'  /><br />
					Artist:&nbsp;&nbsp;&nbsp; <input name='artist' type='text' size='65' value='$row[artist]'  /><br />
					URL:&nbsp;&nbsp;&nbsp;&nbsp; <input name='url' type='text' size='65' value='$row[url]'  /><br />
					<input align='middle' name='add_submit' type='submit' value='Add to Playlists' />
					<input align='middle' name='add_cancel' type='submit' value='Cancel' />
				</form>");
	}
	
	//Lets edit a post in the database
	if($do == "edit"){
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}
		
		if(isset($_GET['status'])){
			$status = $_GET['status'];
			$output .= displayThis('Music Edited','');
		}
		
		if(isset($_GET['submit'])){
			$submit = $_GET['submit'];
	
			$v_album = $_POST['album'];
			$v_title = $_POST['title'];
			$v_artist = $_POST['artist'];
			$v_url = $_POST['url'];			
			
			if(empty($v_title)){
				$v_title = "&nbsp;";
			}
			if(empty($v_artist)){
				$v_artist = "Strike Force";
			}
			if(empty($v_url)){
				$v_url = "&nbsp;";
			}
			
			if(isset($_POST['edit_submit'])){
				if(!empty($v_album)){
				
					$update = $conn->query("UPDATE sf_music SET album = '$v_album', title = '$v_title', artist = '$v_artist', url = '$v_url' WHERE id='$ID' LIMIT 1");
					 
					if($update){
						$output .= displayThis('', "<meta http-equiv='refresh' content='0;URL=$self?action=$action&do=$do&status=done&pg=$pageNum$sort_link'>");	
						return $output;
					} else {
						throw new myException('Changes to the database could not be saved at this time.');
					}
				} elseif(empty($v_album)) {
					$output .= displayThis("Error", "A song cannot be added with out an album name. Please try again.");
				}
		
			}
			
			if(isset($_POST['edit_cancel'])){
				$output .= displayThis("Cancelled", "Song was not edited.");
				return $output;	
			}
				
		}
		
		if(!empty($ID)){
				
			$result = $conn->query("SELECT * FROM sf_music WHERE id = '$ID'"); 
			$row = $result->fetch_array();
				
			$output .= displayThis("<div align='left'>Edit Song</div>", "
				<div align='left'>
					<form name='edit' method='post' action='$self?action=$action&do=$do&id=$row[id]&submit=submitPost&pg=$pageNum$sort_link'>
						Album:&nbsp; <input name='album' type='text' size='65' value='$row[album]'  /><br />
						Title:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input name='title' type='text' size='65' value='$row[title]'  /><br />
						Artist:&nbsp;&nbsp;&nbsp; <input name='artist' type='text' size='65' value='$row[artist]'  /><br />
						URL:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input name='url' type='text' size='65' value='$row[url]'  /><br />
						<input align='middle' name='edit_submit' type='submit' value='Edit Playlists' />
						<input align='middle' name='edit_cancel' type='submit' value='Cancel' />
					</form>
				</div>");
				
		}
	}
	
	//Lets delete a post
	if($do == "delete"){
	
		if(isset($_GET['status'])){
			$output .= displayThis('Deleted from Playlist Database','');
			return $output;
		}
		
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
			
			$result = $conn->query("DELETE FROM sf_music WHERE id = '$ID' LIMIT 1");															
			if($result){
				$output .= displayThis("", "<meta http-equiv='refresh' content='0;URL=$self?action=$action&do=$do&status=done&id=$ID&pg=$pageNum$sort_link'>");
			} else {
				throw new myException("Changes to the database could not be saved at this time.");
			}
		} else {
			$output .= displayThis("Cancelled", "The playlist was not deleted");
			return $output;
		}
	}
	
	return $output;
}

function get_profile_pic($member) {
//Get the profile picture of $member

	$conn = db_connect();

	$result = $conn->query("SELECT * FROM `sf_members` WHERE username = '".$member."'");
	
	if(!$result){
		$output = "Not a member";
		return $output;
	}
	
	$row = $result->fetch_array();
	$output = "<img src='";
	$output .= $row['profile_pic'];
	$output .= "' height='138px' />";

	return $output;
}
?>