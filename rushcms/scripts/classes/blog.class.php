<?php

class Blog extends CMS{
	
	//Define Variables
	var $var;
			
	//Constructor
	public function Blog(){
		$tablename = "posts";
		$Tablename = ucfirst($tablename);
	}

	public function showBlog($id = null, $category = null){
		
		$display = new Display();
		$db = new Database();

		$conn = $db->connect();
		$tablename = 'posts';
		$Tablename = ucfirst($tablename);
		$dbTable = $this->getDBTable($tablename);
		
		$rowsPerPage = 5;
		$indexNum = 1;
		$pageNum = 1;
		$rowIndex = 0;
		$nav = '';
		
		if(isset($_GET['index']))
			$indexNum = $_GET['index'];
			
		if(isset($_GET['pg']))
			$pageNum = $_GET['pg'];	
		
		$offset = ($pageNum - 1) * $rowsPerPage;
		
		$result = $conn->query("SELECT * FROM $dbTable ORDER BY id DESC LIMIT $offset, $rowsPerPage");
		
		if(isset($id))
			$result = $conn->query("SELECT * FROM $dbTable WHERE id = $id LIMIT 1");
		
		if(isset($category))
			$result = $conn->query("SELECT * FROM $dbTable WHERE category = '$category' ORDER BY id DESC LIMIT $offset, $rowsPerPage");
				
		$numrows = $result->num_rows;
				
		$output .= "<div id='blog'>";
		
		//If there are no entries in the database...
		if($numrows < 1){
			$output .= "
					<div class='post'>
						
						<div>
							<div class='post_date'> 
								Posted on: ".date("M j, Y ")." | ".date("g:i A ")." 
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
					
			$output .= "
					</div>";	
					
			return $output;		
		}
		
		//Lets go through the table and show the products...
		while ($row = $result->fetch_array()){
			
			$rowIndex++;
			
			$search = $conn->query("SELECT * FROM ".$this->getDBTable('members')." WHERE username = '".$row['author']."' ");
			$day = date("M j, Y ", strtotime($row['time']));
			$time = date("g:i A ", strtotime($row['time']));
			
			if($pic = $search->fetch_array()){
				$display_pic = "<img class='profile_pic' src='".$pic['profile_pic']."' onclick=\"window.location.href='#'\" style='cursor:pointer;'/>";
			} else {
				$display_pic = "<img class='profile_pic' src='rushcms/images/profile/profile_pic.jpg' onclick=\"window.location.href='#'\" style='cursor:pointer;'/>";
			}	
			
			if(!$row['title']){
				$display_title = "";
			} else {
				$display_title = "<div class='post_title'>".stripslashes($row['title'])."</div>";
			}
			
			if(!$row['author']){
				$display_author = "<div id='author' class='author'>&nbsp</div>";
			} else {
				$display_author = "<div id='author' class='author'>".$row['author']."</div>&nbsp"; 
			}
			
			//Show one product at a time plus product details...
			if(isset($id)){
				$output .= "<div class='post'>
								
									
								<div id='post_left'>
									<div id='post_id'>
									".$display_pic."
									".$display_author."
									<div class='post_date'> 
										".$day."<br />
										".$time."
									</div>
									</div>
									
								</div>
								
								<div id='post_right'>
									
									
										".$display_title."
									
									
									<br />
									<br />
								
									<div id='post_content' class='post_content'>
										".$row['content']."
									</div>
								</div>
								
								<div class='post_min_height'></div>
							</div>";

				$rowID  = $conn->query("SELECT id FROM $dbTable ORDER BY `id` DESC");
				
				$rowIndex = 0;
				
				while($row = $rowID->fetch_row()){
					$rowIndex++;
					
					if($rowIndex == ($indexNum - 1))
						$prevID = $row[0];
					
					if($rowIndex == ($indexNum + 1))
						$nextID = $row[0];
				}

				if ($indexNum > 1){
					$prevPage  = $indexNum - 1;
					$prev  = " <span class='link'><a href=\"$self?action=$action&do=view$Tablename&id=$prevID&index=$prevPage&pg=$pageNum$sort_link\"> Prev </a></span> ";
				} else {
					$prev  = '&nbsp;'; // we're on page one, don't print previous link
				}
			
				if ($indexNum < $rowIndex){
					$nextPage = $indexNum + 1;
					$next = " <span class='link'><a href=\"$self?action=$action&do=view$Tablename&id=$nextID&index=$nextPage&pg=$pageNum$sort_link\"> Next </a></span> ";
				} else {
					$next = '&nbsp;'; // we're on the last page, don't print next link
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
					
				$output .= "</div>";	
				
				return $output;	
						
			} else {
			
				$counter = $rowIndex;
				
				if($pageNum > 1)																	
					$counter =  ($offset) + $rowIndex;
											
				$output .= "<div class='post'>
								
									
								<div id='post_left'>
									<div id='post_id'>
									".$display_pic."
									".$display_author."
									
									<div class='post_date'> 
										".$day."<br />
										".$time."
									</div>
									</div>
								</div>
								
								<div id='post_right'>
									
									
										".$display_title."
								
									
									<br />
									<br />
								
									<div id='post_content' class='post_content'>
										".$row['content']."
									</div>
								</div>
								
								<div class='post_min_height'></div>
							</div>";
			}
			
		}
		
		$output .= "</div>";

		//Lets start working on the pagination...
		$result  = $conn->query("SELECT COUNT(*) AS numrows FROM $dbTable");
		
		if(isset($category))
			$result = $conn->query("SELECT COUNT(*) AS numrows FROM $dbTable WHERE category = '$category'");
		
		$row     = $result->fetch_array(MYSQL_ASSOC);
		$numrows = $row['numrows'];
		
		$maxPage = ceil($numrows/$rowsPerPage);
	
		for($page = 1; $page <= $maxPage; $page++){
		
			if ($page == $pageNum){
			  $nav .= "<span class='current'>$page</span>"; // no need to create a link to current page
			} else {
				$nav .= " <span class='link'><a href=\"$self?pg=$page\">$page</a></span> ";
			} 
		}	
		
		if ($pageNum > 1){
			$page  = $pageNum - 1;
			$prev  = " <span class='link'><a href=\"$self?pg=$page\"> Prev </a></span> ";
			//$first = " <span class='link'><a href=\"$self?pg=1\"> |<< </a></span> ";
		} else {
			$prev  = '&nbsp;'; // we're on page one, don't print previous link
			$first = '&nbsp;'; // nor the first page link
		}
	
		if ($pageNum < $maxPage){
			$page = $pageNum + 1;
			$next = " <span class='link'><a href=\"$self?pg=$page\"> Next </a></span> ";
			//$last = " <span class='link'><a href=\"$self?pg=$maxPage\"> >>| </a></span> ";
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
		$tablename = "posts";
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
		if($do == "add$Tablename"){
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
		
		//Lets delete a post
		if($do == "delete$Tablename"){

			if(isset($_GET['status'])){
				$output .= 'Post Deleted';
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
				$output .= "The post was not deleted";
				return $output;
			}
		}
		
		return $output;
	}
	
}
            
?>