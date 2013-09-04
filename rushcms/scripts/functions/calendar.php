<?php

############################################################
############################################################
####													####
####	Weekly Scheduling Calender						####
####	By: Rashan Budall								####
####	Created: July 3, 2006							####
####	Last Updated: July 19, 2006						####
####	Purpose: 										####
####		Creates a table displaying the current 		####
####	week starting from today going 6 days forwards  ####	
####  	with appropriate fields to click on to schedule ####
####	an event on that date and time.					####
####		Scheduled events will turn red and will		####
####	only be accessable by administrators. If a user ####
####	is logged in and has an event scheduled, the 	####
####	block will turn green for that time. When an	####
####	administrator clicks on a scheduled event, he 	####
####	will be able to view the event that's scheduled	####
####	for that time. 									####
####													####
############################################################
############################################################

session_start();

require_once($_SERVER['DOCUMENT_ROOT'].'/testserver/scripts/functions/sf_fns.php');

	$username = $_SESSION['valid_user'];

	$conn = db_connect();
	$result = $conn->query("SELECT * FROM sf_members WHERE username = '$username'");
	$row = $result->fetch_object();
	$group = $row->group;

##############################################
##############################################
##											##
##	  		If user is an admin				##
##											##
##############################################
##############################################
	
	
	
	if($group >= 3){

		if((isset($_GET['action']))&&(isset($_SESSION['getevent']))&&(isset($_SESSION['getduration']))&&(isset($_SESSION['gettype']))){	
		
			$getevent = $_SESSION['geteventid'];
			$gettime = $_SESSION['gettime'];
			$getaction = $_GET['action'];
			$getduration = $_SESSION['getduration'];
			$getuserid = $_SESSION['getuserid'];
			$gettype = $_SESSION['gettype'];
			$ef = $_SESSION['ef'];
			$et = $_SESSION['et'];
			$it = $_SESSION['it'];
			$id = $_SESSION['id'];
			
			if($getaction == "reschedule"){
				echo "<p>Re-Schedule Event $getevent at $gettime<p>";				
			} 
			
		 }
	}
	
	$page = $_GET['page'];
						
	if(!isset($page)){
		$page = 'calendar';
	}
	
	$result = $conn->query("SELECT * FROM $page WHERE paid='0'");
	
	if($result > 0){
		$result = $conn->query("DELETE FROM $page WHERE paid='0'");
	}
	
//Set initial date variables
	
	$idate = getdate();
	$iday = $idate['mday'];
	
//Goto Next or Previous Week	
	if ($_POST['submit']) { 

		$month_num = $_POST['month_num'];
		$year = $_POST['year'];
		$day_now = $_POST['day_now'];

		if ($_POST['submit'] == "<- Previous Week") { 
			$month_day = $day_now - 7; 
		} else { 
			$month_day = $day_now + 7; 
		} 

		$date = getdate(mktime(0,0,0,$month_num,$month_day,$year)); 

	} else { 
		$date = getdate(); 
	}
	

	

//Set date variables

	$month_name = $date['month'];
	$month_num = $date['mon'];
	$month_day = $date['mday'];
	$year = $date['year'];
	$end_year = getdate(mktime(0,0,0,12,31,$year));
		 
//Get last day of the month

	$cont = true; 
	$today = 27; 
	while (($today <= 32) && ($cont)) { 
		$date_today = getdate(mktime(0,0,0,$month_num,$today,$year)); 

		if ($date_today['mon'] != $month_num) { 
			$lastday = $today - 1; 
			$cont = false; 
		} 

		$today++; 
	} 
	
//Start writing table key	
	
	echo" 
	<table cellspacing=6px><tr><td>
		<table height=3 width=3 bordercolor=black border=1 bgcolor=#CCCCCC><tr><td/></tr></table>
			</td><td>Available Appointments</td><td>	
		<table height=3 width=3 bordercolor=black border=1 bgcolor=#990000><tr><td/></tr></table>
			</td><td>Unavailable Time</td><td>	
		<table height=3 width=3 bordercolor=black border=1 bgcolor=#00CC00><tr><td/></tr></table>
			</td><td>Your Appointment</td></tr>
	</table>";

//Start of table	
	echo "<table border=1 bordercolordark=#cccccc bordercolorlight=#ffffff bgcolor=#5599a2 width=500px>
			<tr><td align=center bgcolor=#666699 width=62.5px><font color=#ffffff>Times</font></td>";

//Make Date Headers
	for(($i = $month_day);(($i <= ($month_day + 6))); $i++){				
	
	//If end of the month, restart dates	
		if($i > $lastday){	
			
						
		//If December and year changes
			
			if($month_num == 12){
				$year = $year + 1;				
			}
			
			$d = 1;
			$day = 0;
			
			for($d, $i, $day;($d <= (($month_day + 6) - $lastday) && ($i <= ($month_day + 6)));$d++,$i++,$day++){
				if($month_num == 12){
					$wday = getdate(mktime(0,0,0,($month_num+1),$day,$year));
				}else{
					$wday = getdate(mktime(0,0,0,($month_num+1),($day+1),$year));
				}
				$hday = getdate(mktime(0,0,0,($month_num+1),$d,$year));
				echo "<td align=center bgcolor=#666699 width=62.5px><font size=-2 color=#ffffff>$wday[weekday]<br>$hday[month] $d, $year</font></td>";
			}
		
		} elseif($i <= $lastday){
			$hday = getdate(mktime(0,0,0,$month_num,$i,$year));
			echo "<td align=center bgcolor=#666699 width=62.5px><font size=-2 color=#ffffff>$hday[weekday]<br>$hday[month] $i, $hday[year]</font></td>";
		}
	} 

	echo "</tr>";

//Check if Am or PM
	for(($ap = 1); $ap <= 2; $ap++){
		if($ap == 1){
			$tod = "am";
		} else {
			$tod = "pm";
		}
	
	//Do time list	
	/*	if($ap == 1){
		
			$conn = db_connect();
			$cal_hours = $conn->query("SELECT * FROM config");
			$time_results = $cal_hours->fetch_array();
			$t = $time_results['cal_start'];			//Start Hour
			$end = $time_results['mid1'];
		} elseif($ap == 2){
			$t = $time_results['mid2'];
			$end = $time_results['cal_end'];		//End Hour	
				
		}
	*/		
		if($ap == 1){
		
			$conn = db_connect();
			$t = 1;			//Start Hour
			$end = 12;
		} elseif($ap == 2){
			$t = 1;
			$end = 12;		//End Hour	
				
		}
	
		for($t; $t <= $end; $t++){
		
		//Make sure you start with 12pm then 12am
		
			if(($ap == 1) && ($t == 12)){
				$tod = "pm";
			} elseif(($ap == 2) && ($t == 12)){
				$tod = "am";
			}
		
		//Print time 
		
			echo"<tr><td align=center><font size=-1 color=#ffffff>$t:00$tod</font></td>";
		
		//Make sure year is current year
		
			$year = $date['year'];
		
		//Make sure months are in MySQL format
		
			if ( intval($month_num) < 10) { 
				$new_month_num = "0$month_num"; 
			} elseif (intval($month_num) >= 10) { 
				$new_month_num = $month_num; 
			}
		
		//Change month number in eventid when months roll over
		
			if($i > $lastday){
				$next_month = $month_num + 1;
			}
			
			for(($i = $month_day);$i <= ($month_day + 6); $i++){
				if($i > $lastday){
					
				//Make sure years roll over
					
					if($month_num == 12){
						$year = $year + 1;
						$next_month = "01";
					}
					
	
					$d = 1;
				
				//Do Table blocks
				
					for($d, $i;($d <= (($month_day + 6) - $lastday) && ($i <= ($month_day + 6)));$d++,$i++){
						if ( intval($d) < 10) { 
							$new_day = "0$d"; 
						} elseif (intval($d) >= 10) { 
							$new_day = $d; 
						} 
						
						if($tod == "pm"){
							$time = $t + 12;
						} else {
							$time = $t;
						}
						
						if(($tod == "pm") && ($t == 12)){
							$time = 12;
						} elseif(($tod == "am") && ($t == 12)){
							$time = 24;
						}
					
						$link_date = "$year-$next_month-$new_day?time=$time:00"; 
					
						$check_date = "$year-$next_month-$new_day $time:00:00";
						
						$page = $_GET['page'];
						
						if(!isset($page)){
							$page = 'calendar';
						} 
						
						$conn = db_connect();
						/*$result = $conn->query("SELECT * FROM $page WHERE received = '$check_date'");
						$row = $result->fetch_row();*/
					
						$username = $_SESSION['valid_user'];
						
						if($row > 0){
							/*$result = $conn->query("SELECT * FROM $page WHERE received = '$check_date'");
							$row = $result->fetch_array();
							*/$username = $_SESSION['valid_user'];
						
						//Check to see if member is an administrator
						
							$group = $conn->query("SELECT * FROM sf_members WHERE username = '$username'");
							$group = $group->fetch_object();
							$group = $group->group;
						//See users sessions		
							if($group['username'] == $username){
								if($group >= 3){
									/*$result = $conn->query("SELECT paid FROM $page WHERE `received` = '$check_date'");
									$row = $result->fetch_object();
									$paid = $row->paid;
									
									if($paid > 0){
										echo "<a href=/scripts/admin/scheduled.php?page=$page&eventid=$link_date target=_parent><td bgcolor=#00CC00>&nbsp;</td></a>";
									}else{
										*/echo "<a href=/scripts/admin/scheduled.php?page=$page&eventid=$link_date target=_parent><td bgcolor=#FFFF99>&nbsp;</td></a>";
										
								} else {
									echo "<td bgcolor=#00CC00>&nbsp;</td>";
								}								
							} else {
								if($group >= 3){
									/*$result = $conn->query("SELECT paid FROM $page WHERE `received` = '$check_date'");
									$row = $result->fetch_object();
									$paid = $row->paid;
									
									if($paid > 0){
										echo "<a href=/scripts/admin/scheduled.php?page=$page&eventid=$link_date target=_parent><td bgcolor=#990000>&nbsp;</td></a>";
									}else{
										*/echo "<a href=/scripts/admin/scheduled.php?page=$page&eventid=$link_date target=_parent><td bgcolor=#FF9900>&nbsp;</td></a>";
									
								} else {
									echo "<td bgcolor=#990000>&nbsp;</td>";
								}
							}
						} else {
							if($getaction == "reschedule"){
								echo "<a href=/scripts/calendar/add_event.php?page=$page&userid=$getuserid&date=$link_date&duration=$getduration&type=$gettype&id=$id&it=$it&et=$et&ef=$ef target=_parent><td bgcolor=#CCCCCC>&nbsp;</td></a>";
							} else {
								echo "<a href=/scripts/calendar/scheduler.php?page=$page&eventid=$link_date target=_parent><td bgcolor=#CCCCCC>&nbsp;</td></a>";
							}
						}
					}
			
				} elseif($i <= $lastday){ 
				
								
					if ( intval($i) < 10) { 
						$new_day = "0$i"; 
					} elseif (intval($i) >= 10) { 
						$new_day = $i; 
					}
					
					if($tod == "pm"){
						$time = $t + 12;
					} else {
						$time = $t;
					}
					
					if(($tod == "pm") && ($t == 12)){
						$time = 12;
					} elseif(($tod == "am") && ($t == 12)){
						$time = 24;
					}
			
					$link_date = "$year-$new_month_num-$new_day?time=$time:00"; 
					
					$check_date = "$year-$new_month_num-$new_day $time:00:00";
					
					$page = $_GET['page'];
						
					if(!isset($page)){
						$page = 'calendar';
					} 
						
					/*$result = $conn->query("SELECT * FROM $page WHERE received = '$check_date'");
					$row = $result->fetch_row();
					
					$username = $_SESSION['valid_user'];
							
					if($row > 0){
						$result = $conn->query("SELECT * FROM $page WHERE received = '$check_date'");
						$row = $result->fetch_array();
						$username = $_SESSION['valid_user'];
					*/	
					//Check to see if member is an administrator	
						
						$group = $conn->query("SELECT * FROM sf_members WHERE username = '$username'");
						$group = $group->fetch_object();
						$group = $group->group;
						
						if($group['username'] == $username){
							if($group >= 3){
								/*$result = $conn->query("SELECT paid FROM $page WHERE `received` = '$check_date'");
								$row = $result->fetch_object();
								$paid = $row->paid;
									
								if($paid > 0){
									echo "<a href=/scripts/admin/scheduled.php?page=$page&eventid=$link_date target=_parent><td bgcolor=#00CC00>&nbsp;</td></a>";
								}else{
									*/echo "<a href=/scripts/admin/scheduled.php?page=$page&eventid=$link_date target=_parent><td bgcolor=#FFFF99>&nbsp;</td></a>";
								
							} else {
								echo "<td bgcolor=#00CC00>&nbsp;</td>";
							}								
						} else {
							if($group >= 3){
								/*$result = $conn->query("SELECT paid FROM $page WHERE `received` = '$check_date'");
								$row = $result->fetch_object();
								$paid = $row->paid;
									
								if($paid > 0){
									echo "<a href=/scripts/admin/scheduled.php?page=$page&eventid=$link_date target=_parent><td bgcolor=#990000>&nbsp;</td></a>";
								}else{
									*/echo "<a href=/scripts/admin/scheduled.php?page=$page&eventid=$link_date target=_parent><td bgcolor=#FF9900>&nbsp;</td></a>";
								
							} else {
								echo "<td bgcolor=#990000>&nbsp;</td>";
							}
						}
					} else {
						if($getaction == "reschedule"){
								echo "<a href=/scripts/calendar/add_event.php?page=$page&userid=$getuserid&date=$link_date&duration=$getduration&type=$gettype&id=$id&it=$it&et=$et&ef=$ef target=_parent><td bgcolor=#CCCCCC>&nbsp;</td></a>";
							} else {
								echo "<a href=/scripts/calendar/scheduler.php?page=$page&eventid=$link_date target=_parent><td bgcolor=#CCCCCC>&nbsp;</td></a>";
							}
					}
					
				}
			}
		} 
	
	
	
//Do Next and Previous Weeks
	
	echo "</table>
				<form method=POST action=$_SERVER[PHP_SELF]>
					<input type=\"hidden\" name=\"day_now\" value=\"$month_day\">
					<input type=\"hidden\" name=\"month_num\" value=\"$month_num\"> 
					<input type=\"hidden\" name=\"year\" value=\"$year\">
				<table width=500px>";
	if($month_day == $iday){
		echo "
					<td align=right>
						<input name=submit type=submit value=\"Next Week ->\">
					</td>";
	} else {			
		echo"		
					<td align=left>
						<input name=submit type=submit value=\"<- Previous Week\" >
					</td>
					<td align=right>
						<input name=submit type=submit value=\"Next Week ->\">
					</td>";
	}
	
	echo"	
				</table>
				</form>";				
?>