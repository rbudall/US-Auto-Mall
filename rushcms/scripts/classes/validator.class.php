<?php
class Validator extends CMS{
			
			//Define variables
			var $errors;
			
			//Constructor
			public function Validator(){
				//Code goes here
			}
			
			public function database($var1, $var2, $var3, $var4){
				$t_var1 = trim($var1);
				$t_var2 = trim($var2);
				$t_var3 = trim($var3);
				
				$conn = mysql_connect($t_var1, $t_var2, $t_var3);
				
				if(!$conn){
					$this->errors .= "*Could not connect to the database. Please check your information below and try again. <br /> If you still have problems connecting, contact your webmaster or web host provider. <br />";
					return false;
				}
				
				if(!mysql_select_db($var4, $conn)){
					$this->errors .= "* ".$var4." is not a valid database name. Please try again. If you still need help, contact your webmaster or web host provider for assistance.";
					return false;
				}
			}
			
			public function fname($var){
				$t_var = trim($var);
				
				if(($t_var == '')||($t_var == 'First Name')){
					$this->errors .= "*Please enter your first name. <br />";
					return false;	
				}
			}
			
			public function lname($var){
				$t_var = trim($var);
				
				if(($t_var == '')||($t_var == 'Last Name')){
					$this->errors .= "*Please enter your last name. <br />";
					return false;	
				}
			}
			
			public function password($var1, $var2){
				$t_var1 = trim($var1);
				$t_var2 = trim($var2);
				
				if($t_var1 == ''){
					$this->errors .= "*Please enter your password. <br />";
					return false;	
				}
				
				if($t_var2 == ''){
					$this->errors .= "*Please re-enter your password. <br />";
					return false;	
				}
				
				if(strlen($t_var1) < 6){
					$this->errors .= "*Your password needs to be at least 6 characters long. <br />";
					return false;
				}
				
				if($t_var1 != $t_var2){
					$this->errors .= "*The passwords you enter do not match. Please try again. <br />";
					return false;
				}
			}
			
			public function email($var1, $var2){
				$t_var1 = trim($var1);
				$t_var2 = trim($var2);
				
				if($t_var1 == ''){
					$this->errors .= "*Please enter your E-Mail. <br />";
					return false;	
				}
				
				if($t_var2 == ''){
					$this->errors .= "*Please re-enter your E-Mail. <br />";
					return false;	
				}
				
				if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $t_var1)){
					$this->errors .= "*The E-Mail you entered is invalid. Please enter a valid E-Mail address. <br />";
					return false;
				} else if($t_var1 != $t_var2){
					$this->errors .= "*The E-Mail you enter does not match. Please try again. <br />";
					return false;
				}
			}
			
		}//Close Validator class
?>