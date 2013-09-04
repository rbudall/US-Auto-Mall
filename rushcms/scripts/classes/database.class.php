<?php

	class Database extends CMS{
		//Class that defines database functions.	
			
			public 	$settings,
					$connection,
					$queryString;
					
			private $dbHost,
					$dbUser,
					$dbPass,
					$dbName;		
			
			//Constructor
			function Database(){
				//Initialize database variables
			
				$this->settings = $this->getSettings();
				
				$this->dbHost = $this->settings['DBHOST'];
				$this->dbUser = $this->settings['DBUSER'];
				$this->dbPass = $this->settings['DBPASS'];
				$this->dbName = $this->settings['DBNAME'];
				
				//Connect to the database
				/*-------------------------
				*** DONT CONNECT TO THE DATABASE WHEN CONSTRUCTING CLASS. USE connect() FUNCTION TO CONNECT TO DATABASE ***
				
								$this->connection = new mysqli($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
								
								if(!$this->connection){
									throw new Exception('Could not connect to the database server. [Error Code 01]');
								}
								
								//Close the connection when not used
								register_shutdown_function(array(&$this, 'close'));	
				*/
			}
			
			public function getLink(){
				$link = @mysql_connect($this->dbHost, $this->dbUser, $this->dbPass);
				if (!$link) {
					die('Could not connect to MySQL server: ' . mysql_error());
				}
				
				return $link;

			}
			
			public function connect(){
			//Connect to the database through the mysqli object.
			//Connecting this way lets you use the mysqli functions such as fetch_array() and query()
				$this->connection = new mysqli($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
				return $this->connection;
			}

			public function close() {
			//Close connection to database
			
				$this->connection->close();
			}
			
			public function create_members_table(){
				$conn = $this->connect();
				$result = $conn->query("SELECT * FROM ".$this->getDBTable('members'));
				
				if(!$result){
					$table = $conn->query("CREATE TABLE ".$this->getDBTable('members')." (
										  `id` int(11) NOT NULL auto_increment,
										  `group` int(11) NOT NULL default '0',
										  `fname` varchar(32) NOT NULL,
										  `lname` varchar(32) NOT NULL,
										  `username` varchar(25) NOT NULL,
										  `password` varchar(40) NOT NULL,
										  `email` varchar(65) NOT NULL,
										  `logged_in` timestamp NOT NULL default '0000-00-00 00:00:00',
										  `logged_out` timestamp NOT NULL default '0000-00-00 00:00:00',
										  `sid` varchar(255) NOT NULL,
										  `profile_pic` varchar(255) NOT NULL,
										  `street_address` varchar(32) NOT NULL,
										  `suburb` varchar(32) NOT NULL,
										  `city` varchar(32) NOT NULL,
										  `state` varchar(32) NOT NULL,
										  `postcode` varchar(32) NOT NULL,
										  `country` varchar(32) NOT NULL,
										  `telephone` varchar(32) NOT NULL,
										  `fax` varchar(32) NOT NULL,
										  `customer_referer` varchar(32) NOT NULL,
										  PRIMARY KEY  (`id`)
										) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;");
				} else {
					throw new Exception ('Table with that name already exisit. Table not created. Try again later.');
				}
				
				return true;
			}
			
			public function create_posts_table(){
				$conn = $this->connect();
				$result = $conn->query("SELECT * FROM ".$this->getDBTable('posts'));
				
				if(!$result){
					$table = $conn->query("CREATE TABLE `".$this->getDBTable('posts')."` (
	 										`id` int(11) NOT NULL AUTO_INCREMENT,
											`author` varchar(65) NOT NULL,
											`title` text NOT NULL,
											`content` text NOT NULL,
											`time` timestamp NULL DEFAULT NULL,
											`category` varchar(32) NOT NULL,
											PRIMARY KEY (`id`)
										) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;");
				} else {
					throw new Exception ('Table with that name already exisit. Table not created. Try again later.');
				}
				
				return true;
			}
			
			public function create_cart_table(){
				$table = "cart";
				$conn = $this->connect();
				$result = $conn->query("SELECT * FROM ".$this->getDBTable($table));
				
				if(!$result){
					$table = $conn->query("CREATE TABLE `".$this->getDBTable($table)."` (
	 										`id` int(11) NOT NULL AUTO_INCREMENT,
											`session_id` text COLLATE latin1_general_ci NOT NULL,
											`product_id` text COLLATE latin1_general_ci NOT NULL,
											`size` varchar(32) COLLATE latin1_general_ci NOT NULL,
											`date` date NOT NULL,
											`qty` varchar(32) COLLATE latin1_general_ci NOT NULL,
											PRIMARY KEY (`id`)
										) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;");
				} else {
					throw new Exception ('Table with that name already exisit. Table not created. Try again later.');
				}
				
				return true;
			}
			
			public function create_categories_table(){
				$table = "categories";
				$conn = $this->connect();
				$result = $conn->query("SELECT * FROM ".$this->getDBTable($table));
				
				if(!$result){
					$table = $conn->query("CREATE TABLE `".$this->getDBTable($table)."` (
	 										`id` int(11) NOT NULL AUTO_INCREMENT,
											`name` varchar(32) COLLATE latin1_general_ci NOT NULL,
											`parent` varchar(32) COLLATE latin1_general_ci NOT NULL,
											PRIMARY KEY (`id`)
										) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;");
				} else {
					throw new Exception ('Table with that name already exisit. Table not created. Try again later.');
				}
				
				return true;
			}
			
			public function create_products_table(){
				$table = "products";
				$conn = $this->connect();
				$result = $conn->query("SELECT * FROM ".$this->getDBTable($table));
				
				if(!$result){
					$table = $conn->query("CREATE TABLE `am_products` (
											`id` int(11) NOT NULL AUTO_INCREMENT,
											`user_id` int(32) NOT NULL,
											`category` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
											`name` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
											`description` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
											`price` float NOT NULL,
											`size` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
											`color` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
											`quantity` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
											`picture` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
											PRIMARY KEY (`id`)
											) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;");
				} else {
					throw new Exception ('Table with that name already exisit. Table not created. Try again later.');
				}
				
				return true;
			}
			
			public function create_members_admin($fname, $lname, $username, $password, $email){
				//Capitalize first letter of name
				$Fname = ucfirst(strtolower($fname));	
				$Lname = ucfirst(strtolower($lname));
			
				//Connect to db
				$conn = $this->connect();
			
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
									  	VALUES (NULL,'3','$Fname','$Lname','$username',SHA1('$password'),'$email')");
				
				if(!$result){
					throw new Exception('Could not register you into the database at this time. Please try again later.');
				}
				
				return true;
			}
			
			public function check_admin(){
			//Check to see it an administrator is logged in
			
				if($this->check_user()){
				//If the user is logged in...
					
					$username = $_SESSION['valid_user'];
				
					$conn = $this->connect();
					
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
				}
			}
			
			public function send_email($subject, $message, $to = false){
				$conn = $this->connect();
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
				return true;
			}
			
		}//Close Database class
        
?>