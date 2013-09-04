<?php
		class CMS{
			
			//Define CMS variables
			public 		$dbSettings,
						$dbTable;
						
			//Constructor
			public function CMS(){
									
			}
			
			//Set CMS Settings			
			public function setSettings($sitename, $url, $root, $dbhost, $dbuser, $dbpass, $dbname, $dbprefix){
				
				$handle = fopen('scripts/config.php','w+');
				
				if($handle){
					$output = "<?php

##################################################################################################
##################################################################################################
##																								##
## RUSH Content Management System (RCMS) Configuration File										##
##																								##
## This file contains some variables that are needed to configure the RCMS. Most of these 		##
## variables are set during installation, but you may also change them here if necessary.		##
##																								##
## ***NOTE: Changes to this file may cause your RCMS to not function properly. Only make		##
##          necessary changes to this file at your own risk.									##
##																								##
##################################################################################################
##################################################################################################


// Define site variables				


	\$SITENAME = '".$sitename."';		
	\$SITE_URL = '".$url."';
	\$SITE_ROOT = '".$root."';


// Define database variables				


	\$DBHOST = '".$dbhost."';		
	\$DBUSER = '".$dbuser."';		
	\$DBPASS = '".$dbpass."';		
	\$DBNAME = '".$dbname."';		
	\$DBPREFIX = '".$dbprefix."';		

?>";
						
					if(!fwrite($handle, $output)){
						die("Could not write to file ".$handle.". Please try again later.");
					} 
				}
				
				fclose($handle);
			}

			//Get CMS Settings	
			public function getSettings(){
				$handle = fopen('rushcms/scripts/config.php','r');
				$file = file_get_contents('rushcms/scripts/config.php');
				
				while(!feof($handle)){
					$line = trim(fgets($handle));
					
					$line = explode("$", $line);
					
					$line = explode("=", $line[1]);
					
					if(($line[0] != '')||($line[1] != '')){
						$variable = trim($line[0]);
						$value = trim(substr($line[1],2,-2));

						$this->dbSettings[$variable] = $value;
					}	
				}					
					
				fclose($handle);
				
				return $this->dbSettings;
			}
			
			public function getSiteVariable($var){
				$variable = $this->getSettings();
				
				return $variable[$var];
			}
			
			public function getDBTable($var){
				$membersTable = $this->getSiteVariable('DBPREFIX').$var;
				
				return $membersTable;
			}
			
			public function check_user(){
			//See is somebody is logged in and notify them if not	
				
				if(isset($_SESSION['valid_user'])){
					return true;
				} else {
				//They are not logged in
					return false;
				}
			}

			
			public function check_admin(){
			//Check to see it an administrator is logged in
			
				if($this->check_user()){
				//If the user is logged in...
					
					$username = $_SESSION['valid_user'];
				
					$db = new Database();
					$conn = $db->connect();
					
					$result = $conn->query("SELECT * FROM ".$this->getDBTable('members')." WHERE username = '$username'");
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
					
					$db->close();
				}
			}
			
			public function send_email($subject, $message, $to = false){
				$db = new Database();
				$conn = $db->connect();
				$result = $conn->query("SELECT * FROM ".$this->getDBTable('members')."");
			
				if (!$result){
				  throw new myException('Could not find email address.');  
				} elseif($result->num_rows==0){
					throw new myException('Could not find email address.');   // email not in db
				} else {
						$from = "From: support@".$this->getSiteVariable('SITENAME')." \r\n";
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
				
				$db->close();
				return true;
			}
			
			public function curPageName() {
				$filename = substr($_SERVER['SCRIPT_NAME'], 1);
				$output = explode('.',$filename);
				
				return ucfirst($output[0]);
			}
			
			public function uploadPic($file, $dest = null){
				if($dest == null)
					$dest = "_images/_uploads/_products/";
				
				$target_path = $dest . basename( $file['name']); 
				$origin = strtolower(basename($file['name']));
				$fulldest = $target_path;
				$filename = $origin;
		
				for ($i=1; file_exists($fulldest); $i++){
					$fileext = (strpos($origin,'.')===false?'':'.'.substr(strrchr($origin, "."), 1));
					$newfilename = substr($origin, 0, strlen($origin)-strlen($fileext)).'['.$i.']'.$fileext;
					$fulldest = $dest.$newfilename;
				} 
				
				if(move_uploaded_file($file['tmp_name'], $fulldest))
					$upload = $fulldest;
				else 
					throw new Exception('Error. Image not uploaded.');
				
				return $upload;	
			}	
				
		}//Close CMS class
?>