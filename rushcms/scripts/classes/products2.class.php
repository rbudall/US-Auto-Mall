<?php

class Products extends CMS{


	//Define Variables
	var $var;
			
	//Constructor
	public function Products(){
		setlocale(LC_MONETARY, 'en_US');
	}
	
	public function showProducts($id = null, $category = null){
		
		$db = new Database();
		$display = new Display();
		
		//Connect to the database
		$conn = $db->connect();
		
		$tablename = 'products';
		$Tablename = ucfirst($tablename);
		$dbTable = $this->getDBTable($tablename);
		
		$indexNum = 1;
		$pageNum = 1;
		$rowIndex = 0;
		$nav = '';
		
		//Get and set all variables from URL
		if(isset($_GET)){			
			foreach($_GET as $key => &$value)
				$$key = $value;
			
			if(isset($index))
				$indexNum = $index;
			if(isset($pg))
				$pageNum = $pg;			
		}
			
		$output = "<section id='products'>
			
						<h1>Products</h1>";
	
		$rowsPerPage = 12;
		$offset = ($pageNum - 1) * $rowsPerPage;
				
		//Get Products from database
		$result = $conn->query("SELECT * FROM $dbTable ORDER BY id DESC LIMIT $offset, $rowsPerPage");	
		
		if(isset($id))
			$result = $conn->query("SELECT * FROM $dbTable WHERE id = $id LIMIT 1");
		else if(isset($category))
			$result = $conn->query("SELECT * FROM $dbTable WHERE category = '$category' ORDER BY id DESC LIMIT $offset, $rowsPerPage");					
		
		//Calculate the number of products retrived from the database								
		$numrows = $result->num_rows;
				
		//If there are no entries in the database...
		if($numrows < 1){
			$output .= "<h2>No products were found in the database.</h2>";	
		}
		
		//Lets go through the table and show the products...
		while ($row = $result->fetch_array()){
			$rowIndex++;
			
			$mysizes = explode(",",$row['size']);
			$mycolors = explode(",",ucfirst($row['color']));
			
			//Show one product at a time plus product details...
			if(isset($id)){
				$output .= "<div id=\"item_details\">					
								<form action=\"cart.php?action=addTo&pid=$row[id]\" method=\"post\">
									<img src='$row[picture]' />
									<div id='product_details'>
										<h3>Name:</h3> <p>$row[name]</p> <br />
										<h4>Sizes:</h4>
										<select name='on0' value='size'>";
								
				foreach($mysizes as $cur_size)
					$output .= "			<option name=\"os0\" value=\"Size $cur_size\">$cur_size</option>";	
												
				$output .= "			</select><br />
										<h4>Color:</h4> <p>$row[color]</p> <br />
										<h4>Quantity:</h4> <p>$row[quantity]</p> <br />
										<br />
										<div id='product_description'>
											<h4>Description:</h4>
											<p>$row[description]</p>
										</div><br />
										<h4>Price:</h4> <p>\$$row[price]</p>
										<div id='product_addToCart'>
											<input type=\"hidden\" name=\"cmd\" value=\"_s-xclick\">
											<input type='hidden' name='status' value='submit'>
											<input type=\"image\" src=\"rushcms/images/buttons/cart/cart_add.png\" border=\"0\" name=\"submit\" alt=\"Make payments with PayPal - it\'s fast, free and secure!\">
											<input type=\"hidden\" name=\"encrypted\" value=\"-----BEGIN PKCS7-----\n$encryptedButton\n-----END PKCS7-----\">
										</div>
									</div> <!-- end #product_details -->
								</form>
							</div>";
						
			} else {
			
				$counter = $rowIndex;
				
				if($pageNum > 1)																	
					$counter =  ($offset) + $rowIndex;
							
					$output .= "<div class=\"item\" id=\"item_$row[id]\" onclick=\"window.location.href='$self?view=$view&action=$action&do=viewProducts&id=$row[id]&index=$counter&pg=$pageNum$sort_link'\">
									<img src=\"$row[picture]\" />
									<hgroup>
										<h3 id=\"item_name\">$row[name]</h3><br />
										<h4>Make</h4><br />
										<h4>Model</h4><br />
										<h4>Year</h4><br />
										<h4>".$display->neat_trim($row['description'], 30)."</h4><br />
										<h3>Price: ".money_format('%(#10n',$row['price'])."</h3>
									</hgroup>
								</div>";
				}
			}
		
			$maxPage = ceil($numrows/$rowsPerPage);
		
			for($page = 1; $page <= $maxPage; $page++){
				if ($page == $pageNum)
					$nav .= " $page "; // no need to create a link to current page
				else 
					$nav .= " <a href=\"$self?view=$view&pg=$page\">$page</a> ";
			}
				
			if ($pageNum > 1){
				$page  = $pageNum - 1;
				$prev  = " <a href=\"$self?view=$view&pg=$page\"> Prev </a>< ";
				$first = " <a href=\"$self?view=$view&pg=1\"> |<< </a> ";
			} else {
				$prev  = '&nbsp;'; // we're on page one, don't print previous link
				$first = '&nbsp;'; // nor the first page link
			}
		
			if ($pageNum < $maxPage){
				$page = $pageNum + 1;
				$next = " <a href=\"$self?view=$view&pg=$page\"> Next </a> ";
				$last = " <a href=\"$self?view=$view&pg=$maxPage\"> >>| </a> ";
			} else {
				$next = '&nbsp;'; // we're on the last page, don't print next link
				$last = '&nbsp;'; // nor the last page link
			}

			$output .= "<div class='clear'></div>
						<div class='pg_nav'>
							<table cols='5' width=' 100%' cellpadding='0' cellspacing='0'>
								<tr >		
									<td align='center' colspan='1' >&nbsp;".$first."&nbsp;</td> 
									<td align='center' colspan='1' >&nbsp;".$prev."&nbsp;</td>
									<td align='center' colspan='1' >&nbsp;".$nav."&nbsp;</td>
									<td align='center' colspan='1' >&nbsp;".$next."&nbsp;</td>
									<td align='center' colspan='1' >&nbsp;".$last."&nbsp;</td>
								</tr>
							</table>
						</div> <!-- end .pg_nav -->";
			
			$output .= "</section> <!-- end #products -->";		
	
			return $output;		
		}
			
	public function showProductsManager(){ 
		
		$db = new Database();
		$display = new Display();
		
		$rowsPerPage = 12;
		$pageNum = 1;
		
		$sort = '';
		$sort_type = 'desc';
		$order = "ORDER BY id desc";
		
		$self = $_SERVER['PHP_SELF'];
		$nav  = '';
		$search = '';
		
		//Connect to the database...
		$conn = $db->connect();
		$tablename = "products";
		$Tablename = ucfirst($tablename);
		
		//Get and set all variables from URL...
		if(isset($_GET)){			
			foreach($_GET as $key => &$value)
				$$key = $value;
		
			if(isset($pg))
				$pageNum = $pg;			
		}	
		
		$output .= $display->listTable($tablename, $rowsPerPage);
		
		//Lets add a product to the database
		if($do == "add$Tablename"){
			
			//If new producted was just added...
			if(isset($status)){
				$output .= "$Tablename Added";
				return $output;
			}
			
			//If new product was cancelled
			if(isset($_POST["add$Tablename"."_cancel"])){
				$output .= "$Tablename was not added.";
				return $output;	
			}
			
			//If form was submitted, check input and upload to database
			if(isset($submit)){
				
				foreach($_POST as $key => &$value){
					$new_key = 'v_'.$key;
					$$new_key = $value;	
				}
				
				$v_picture = $_FILES['picture'];
				
				if(!empty($v_name)){
					//If picture was submitted...
					if($_FILES['picture']['size'] > 0){
				
						$upload = $this->uploadPic($_FILES['picture'], "_images/_uploads/_products/");
						
						$result = $conn->query("INSERT INTO ".$this->getDBTable($tablename)." (id, name, category, picture, size, color, price, quantity, description) VALUES (NULL, '$v_name', '$v_category','$upload', '$v_size', '$v_color', '$v_price', '$v_quantity', '$v_description')");
						
						if($result){
							$output .= "<meta http-equiv='refresh' content='1;URL=$self?view=$view&action=$action&do=$do&status=done&pg=$pageNum$sort_link'>";	
							return $output;
						} else 
							throw new Exception("Changes to the $Tablename could not be saved at this time.");
						
					} else {
						//If Picture was not submitted...
						$v_picture = "&nbsp;";
						
						$result = $conn->query("INSERT INTO ".$this->getDBTable($tablename)." (id, name, category, picture, size, color, price, quantity, description) VALUES (NULL, '$v_name', '$v_category','$v_picture', '$v_size', '$v_color', '$v_price', '$v_quantity', '$v_description')");
						
						if($result){
							$output .= "<meta http-equiv='refresh' content='1;URL=$self?view=$view&action=$action&do=$do&status=done&pg=$pageNum$sort_link'>";	
							return $output;
						} else 
							throw new Exception("Changes to the $Tablename could not be saved at this time.");
						
					}
				} else {
					//If no name was inputted...
					$output .= "$Tablename cannot be added with no name. Please try again.";
					return $output;
				}
				
			}
			
			//Show add products form
			$output .= "<div id='add$Tablename'>					
							<form name='add$Tablename' method='post' enctype='multipart/form-data' action='$self?view=$view&action=$action&do=$do&submit=submit$Tablename&pg=$pageNum$sort_link'>
								".$display->showAddProducts()."
							</form>
						</div>";
		}
		
		//Lets edit a product in the database
		if($do == "edit$Tablename"){
			
			//If the product was edited successfully...
			if(isset($status)){
				$output .= "$Tablename Edited";
				return $output;
			}
			
			//If edit cancelled, tell user...
			if(isset($_POST["edit$Tablename"."_cancel"])){
				$output .= "$Tablename was not edited.";
				return $output;	
			}
			
			//Changes have been made to a product and need to be updated in the database...
			if(isset($submit)){
				foreach($_POST as $key => &$value){
					$new_key = "\$v_".$key;
					$$new_key = $value;				
				}
				
				$v_picture = $_FILES['picture'];
				
				if($v_picture['size'] > 0){ 
					
					$upload = $this->uploadPic($_FILES['picture'], "_images/_uploads/_products/");
					
					$update = $conn->query("UPDATE ".$this->getDBTable($tablename)." SET name='$v_name', category='$v_category', size='$v_size', color='$v_color', price='$v_price', quantity='$v_quantity', description='$v_description', picture = '$upload' WHERE id='$id' LIMIT 1");
					
					if($update){
						$output .= "<meta http-equiv='refresh' content='1;URL=$self?view=$view&action=$action&do=edit$Tablename&status=done&id=$id&index=$index&pg=$pageNum$sort_link'>";
						return $output;
					} else 
						throw new Exception("Changes to the $Tablename could not be saved at this time.");
					
				}
			}
			
			//If product was deleted then refresh page...
			if(isset($id)){	
				$result = $conn->query("SELECT COUNT(*) AS numrows FROM ".$this->getDBTable($tablename)." WHERE id='$id' LIMIT 1");
				$row = $result->fetch_array();
				$numrows = $row['numrows'];
				
				if($numrows < 1){
					$output = "<meta http-equiv='refresh' content=\"1;url=$self?view=$view&action=$action&do=delete$Tablename&status=done&index=$index&pg=$pageNum$sort_link\">";
					return $output;
				}
			}
						
			//Lets edit this product...
			if(isset($id)){
					
				$result = $conn->query("SELECT * FROM ".$this->getDBTable($tablename)." WHERE id = '$id'"); 
				$row = $result->fetch_array();
				
				$offset = ($pageNum - 1) * $rowsPerPage;
				
				$output .= "<div id='edit$Tablename'>
						
							<div id='edit_item'>
								<form name='edit$Tablename' method='post' enctype='multipart/form-data' action='$self?view=$view&action=$action&do=$do&id=$row[id]&submit=submit$Tablename&index=$index&pg=$pageNum$sort_link'>
									<div>ID #$row[id]</div>
									
									<table>
										<tr>
											<td valign='top'>
												<div id='edit_products_pic'><img src=\"$row[picture]\" /></div>
											</td>	
											<td valign='top'>
												<div class='input_name'>Category: </div>
												<div class='input_field'>
													<select name='category'>";
												
				$cat_result = $conn->query("SELECT * FROM ".$this->getDBTable("categories"));

				while ($cat_row = $cat_result->fetch_array()){
					
					$cat_name = ucfirst($cat_row['name']);
					
					if($cat_name == ucfirst($row['category'])){
						$output .= "<option selected='selected' name='$row[category]'  value=\"".ucfirst($row['category'])."\" >".ucfirst($row['category'])."</option>";
					} else {
						$output .= "<option name='$cat_name'  value=\"$cat_name\" >$cat_name</option>";
					}
				}					
	
												
												
						$output .=	"		</select>
										</div>
										
										<div class='input_name'>Name: </div>
										<div class='input_field'><input name='name' type='text' value=\"$row[name]\" /></div>
										
										<div class='input_name'>Size: </div>
										<div class='input_field'><input name='size' type='text' value=\"$row[size]\" /></div>
										 
										<div class='input_name'>Price: </div>
										<div class='input_field'><input name='price' type='text' value=\"$row[price]\" /></div>										
									</td>
									<td>
										<div class='input_name'>Color: </div>
										<div class='input_field'><input name='color' type='text' value=\"$row[color]\" /></div>
										
										<div class='input_name'>Quantity: </div>
										<div class='input_field'><input name='quantity' type='text' value=\"$row[quantity]\" /></div>
									</td>
								</tr>
								<tr>
									<td colspan='3'>
										<div class='input_name'>Picture: </div>$row[picture]
										<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"2097152\" />
										<input type='hidden' name='pic_url' value='$row[picture]' />
										<div class='input_field'><input type='file' name='picture' /></div>
									</td>
								</tr>
							</table>
							
							<div class='input_name'>Description: </div>
							<div id='textbox'>
								<textarea class='ckeditor' id='editor1' name='description' cols='80' rows='10'>$row[description]</textarea>
							</div>
							<div class='clear'></div>	
							<input name='edit$Tablename"."_submit' type='submit' value='Edit $Tablename' />
							<input name='edit$Tablename"."_cancel' type='submit' value='Cancel' />
							
							</form>
						</div>
					</div>";
					
					$result = $conn->query("SELECT COUNT(*) AS numrows FROM ".$this->getDBTable($tablename)." ORDER BY `id` DESC");
					$row = $result->fetch_array(MYSQL_ASSOC);
					$numrows = $row['numrows'];
					
					$result2  = $conn->query("SELECT id FROM ".$this->getDBTable($tablename)." ORDER BY `id` DESC ");

					if($offset == 0)
						$my_index = $index;

					for($rowIndex = 1;$row = $result2->fetch_row();$rowIndex++){

						if($rowIndex == ($my_index - 1))
							$prevID = $row[0];
						
						if($rowIndex == ($my_index + 1))
							$nextID = $row[0];
						

						if(($my_index == (($rowsPerPage*($pageNum-1)) + 1)) && ($my_index > 1)){		
							$pg = $pageNum - 1;
	
							$prevRow = $my_index - 1;
							$prev  = " <a href=\"$self?view=$view&action=$action&do=edit$Tablename&id=$prevID&index=$prevRow&pg=$pg$sort_link\"> Prev </a> ";
						} elseif ($index > 1){
							$pg = $pageNum;
							$prevRow  = $my_index - 1;
							$prev  = " <a href=\"$self?view=$view&action=$action&do=edit$Tablename&id=$prevID&index=$prevRow&pg=$pg$sort_link\"> Prev </a> ";
						} else  {
							$prev  = '&nbsp;'; // we're on page one, don't print previous link
						}
					
						if(is_int($index/$rowsPerPage) && ($index + $offset) != $numrows ){		
							$pg = $pageNum + 1;
							$nextRow = $my_index + 1;
							$next = " <a href=\"$self?view=$view&action=$action&do=edit$Tablename&id=$nextID&index=$nextRow&pg=$pg$sort_link\"> Next </a> ";
						} elseif ($index < ($numrows)){
							$pg = $pageNum;
							$nextRow = $my_index + 1;
							$next = " <a href=\"$self?view=$view&action=$action&do=edit$Tablename&id=$nextID&index=$nextRow&pg=$pg$sort_link\"> Next </a> ";
						} else  {
							$next = '&nbsp;'; // we're on the last page, don't print next link
						}
					}
					
					$output .= "<div class='clear'></div>
									<div class='products_nav'>
										<table width='100%' cols='3' cellpadding='0' cellspacing='0'>
											<tr >		
												<td width='33%'>&nbsp;".$prev."&nbsp;</td>
												<td width='33%'>&nbsp;".$nav."&nbsp;</td>
												<td width='33%'>&nbsp;".$next."&nbsp;</td>
											</tr>
										</table>
									</div>";
					
			}
		}
		
		//Lets delete a product
		if($do == "delete$Tablename"){

			if(isset($status)){
				$output .= "$Tablename Deleted";
				return $output;
			}
			
			if(isset($id)){
				$result = $conn->query("DELETE FROM ".$this->getDBTable($tablename)." WHERE id = '$id' LIMIT 1");
				$output = "<meta http-equiv='refresh' content=\"1;url=$self?view=$view&action=$action&do=delete$Tablename&status=done&index=$index&pg=$pageNum$sort_link\">";
				return $output;
			} else {
				$output .= "The post was not deleted";
			}
		}

		return $output;
	 }
	
	public function showCategoriesManager(){ 
		
		$db = new Database();
		$display = new Display();
		
		$rowsPerPage = 12;
		$pageNum = 1;
		
		$sort = '';
		$sort_type = 'desc';
		$order = "ORDER BY id desc";
		
		$self = $_SERVER['PHP_SELF'];
		$nav  = '';
		$search = '';
		
		//Connect to the database
		$conn = $db->connect();
		$tablename = "categories";
		$Tablename = ucfirst($tablename);	
		
		//Get all the variables from the URL
		if(isset($_GET)){			
			foreach($_GET as $key => &$value)
				$$key = $value;
		
			if(isset($pg))
				$pageNum = $pg;			
		}	
		
		$output = $display->listTable($tablename, $rowsPerPage);
		
		//Lets add a post to the database
		if($do == "add$Tablename"){
			//If producted was just added...
			if(isset($status)){
				$output .= "$Tablename Added";
				return $output;
			}
			
			//If add category was cancelled...
			if(isset($_POST["add$Tablename"."_cancel"])){
				$output .= "$Tablename was not added.";
				return $output;	
			}
			
			//If form was submitted, check input and upload to database
			if(isset($submit)){
				foreach($_POST as $key => &$value){
					$new_key = "\$v_".$key;
					$$new_key = $value;				
				}	
			
				if(isset($_POST["add$Tablename"."_submit"])){
					if(!empty($v_name)){

						$result = $conn->query("INSERT INTO ".$this->getDBTable($tablename)." (id, name, parent) VALUES (NULL, '$v_name', '$v_parent')");
							
						if($result){
							$output .= "<meta http-equiv='refresh' content='0;URL=$self?view=$view&action=$action&do=$do&status=done&pg=$pageNum$sort_link'>";	
							return $output;
						} else 
							throw new Exception("Changes to the $Tablename could not be saved at this time.");
					} 
				}
			}
	
			$output .= "<div id='add$Tablename' align='left'>
							
							<div id='item_details'>
								<form name='add$Tablename' method='post' enctype='multipart/form-data' action='$self?view=$view&action=$action&do=$do&submit=submit$Tablename&pg=$pageNum$sort_link'>
									<div class='input_name'>Name: </div>
									<div class='input_field'><input name='name' type='text' value=\"\" /></div>
									<br />								
									<div class='input_name'>Parent: </div>
									<div class='input_field'>
										<select name='parent'>
											<option name='' value=\"\" >&nbsp;</option>";
												
			$categories = $conn->query("SELECT * FROM ".$this->getDBTable("categories"));
			
			while ($cat_row = $categories->fetch_array()){
				$cat_name = ucfirst($cat_row['name']);										
				$output .= "<option name='$cat_name'  value=\"$cat_name\" >$cat_name</option>";										
			}					
																
			$output .=	"		</select>
								</div>
									<br />
									<input align='middle' name='add$Tablename"."_submit' type='submit' value='Add $Tablename' />
									<input align='middle' name='add$Tablename"."_cancel' type='submit' value='Cancel' />
								</form>
							</div>
						</div>";
		}
		
		//Lets edit a product in the database
		if($do == "edit$Tablename"){
			
			//If the product was edited successfully...
			if(isset($status)){
				$output .= "$Tablename Edited";
				return $output;
			}
			
			//Changes have been made to a product and need to be updated in the database...
			if(isset($submit)){
	
				foreach($_POST as $key => &$value){
					$new_key = "\$v_".$key;
					$$new_key = $value;				
				}
				
				//Update the database with any values that have changed...
				if(isset($_POST["edit$Tablename"."_submit"])){
					if(!empty($v_name)){
					
						$update = $conn->query("UPDATE ".$this->getDBTable($tablename)." SET name='$v_name', parent='$v_parent', id='$v_id' WHERE id='$id' LIMIT 1");
						 
						if($update){
							$output .= "<meta http-equiv='refresh' content='1;URL=$self?view=$view&action=$action&do=edit$Tablename&status=done&id=$id&index=$index&pg=$pageNum$sort_link'>";
							return $output;
						} else 
							throw new Exception("Changes to the $Tablename could not be saved at this time.");
						
					} else if(empty($v_name)) 
						$output .= "A $Tablename cannot be edited with no name. Please try again.";					
				}
				
				//If edit cancelled, tell user...
				if(isset($_POST["edit$Tablename"."_cancel"])){
					$output .= "$Tablename was not edited.";
					return $output;	
				}
					
			}
			
			//If product was deleted then refresh page...
			if(isset($id)){				
				$result = $conn->query("SELECT COUNT(*) AS numrows FROM ".$this->getDBTable($tablename)." WHERE id='$id' LIMIT 1");
				$row = $result->fetch_array();
				$numrows = $row['numrows'];
				
				if($numrows < 1) {
					$output .= "<meta http-equiv='refresh' content=\"1;url=$self?view=$view&action=$action&do=delete$Tablename&status=done&index=$index&pg=$pageNum$sort_link\">";
					return $output;
				}
			}
			
			//Lets edit this product...
			if(!empty($id)){
					
				$result = $conn->query("SELECT * FROM ".$this->getDBTable($tablename)." WHERE id = '$id'"); 
				$row = $result->fetch_array();
				
				$offset = ($pageNum) * $rowsPerPage;
			
				if($pageNum < 2)
					$offset = ($pageNum - 1) * $rowsPerPage;
				
				$output .= "
					<div id='edit$Tablename' align='left'>
						
							<div id='edit_item'>
								<form name='edit$Tablename' method='post' enctype='multipart/form-data' action='$self?view=$view&action=$action&do=$do&id=$row[id]&submit=submit$Tablename&index=$index&pg=$pageNum$sort_link'>
									<div>ID #$row[id]</div>
									<input name='id' type='hidden'  value=\"$row[id]\"  />
									
									<div class='input_name'>Name: </div>
									<div class='input_field'><input name='name' type='text'  value=\"$row[name]\"  /></div>
									<br />							
									<div class='input_name'>Parent: </div>
									<div class='input_field'>
										<select name='parent'>
											<option name=''  value=\"\" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
												
				$categories = $conn->query("SELECT * FROM ".$this->getDBTable("categories"));
	
				
				while ($cat_row = $categories->fetch_array()){
					$cat_name = ucfirst($cat_row['name']);
										
					if($cat_name == ucfirst($row['parent']))
						$output .= "<option selected='selected' name='$row[parent]'  value=\"".ucfirst($row['parent'])."\" >".ucfirst($row['parent'])."</option>";
					else 
						$output .= "<option name='$cat_name'  value=\"$cat_name\" >$cat_name</option>";
				}					
									
				$output .=	"			</select>
									</div>
									<br />		
									<input name='edit$Tablename"."_submit' type='submit' value='Edit $Tablename' />
									<input name='edit$Tablename"."_cancel' type='submit' value='Cancel' />
								
								</form>
							</div>
						</div>";
					
				$result = $conn->query("SELECT COUNT(*) AS numrows FROM ".$this->getDBTable($tablename)." ORDER BY `id` DESC");
				$row = $result->fetch_array();
				$numrows = $row['numrows'];
				
				$result2  = $conn->query("SELECT id FROM ".$this->getDBTable($tablename)." ORDER BY `id` DESC ");

				if($offset == 0)
						$my_index = $index;

					for($rowIndex = 1;$row = $result2->fetch_row();$rowIndex++){

						if($rowIndex == ($my_index - 1))
							$prevID = $row[0];
						
						if($rowIndex == ($my_index + 1))
							$nextID = $row[0];
						

						if(($my_index == (($rowsPerPage*($pageNum-1)) + 1)) && ($my_index > 1)){		
							$pg = $pageNum - 1;
	
							$prevRow = $my_index - 1;
							$prev  = " <a href=\"$self?view=$view&action=$action&do=edit$Tablename&id=$prevID&index=$prevRow&pg=$pg$sort_link\"> Prev </a> ";
						} elseif ($index > 1){
							$pg = $pageNum;
							$prevRow  = $my_index - 1;
							$prev  = " <a href=\"$self?view=$view&action=$action&do=edit$Tablename&id=$prevID&index=$prevRow&pg=$pg$sort_link\"> Prev </a> ";
						} else  {
							$prev  = '&nbsp;'; // we're on page one, don't print previous link
						}
					
						if(is_int($index/$rowsPerPage) && ($index + $offset) != $numrows ){		
							$pg = $pageNum + 1;
							$nextRow = $my_index + 1;
							$next = " <a href=\"$self?view=$view&action=$action&do=edit$Tablename&id=$nextID&index=$nextRow&pg=$pg$sort_link\"> Next </a> ";
						} elseif ($index < ($numrows)){
							$pg = $pageNum;
							$nextRow = $my_index + 1;
							$next = " <a href=\"$self?view=$view&action=$action&do=edit$Tablename&id=$nextID&index=$nextRow&pg=$pg$sort_link\"> Next </a> ";
						} else  {
							$next = '&nbsp;'; // we're on the last page, don't print next link
						}
					}
					
				$output .= "<div class='clear'></div>
								<div class='products_nav'>
									<table width='100%' cols='3' cellpadding='0' cellspacing='0'>
										<tr >		
											<td width='33%'>&nbsp;".$prev."&nbsp;</td>
											<td width='33%'>&nbsp;".$nav."&nbsp;</td>
											<td width='33%'>&nbsp;".$next."&nbsp;</td>
										</tr>
									</table>
								</div>";
					
			}
		}
		
		//Lets delete a post
		if($do == "delete$Tablename"){

			if(isset($status)){
				$output .= "$Tablename Deleted";
				return $output;
			}
			
			if(isset($id))
				$result = $conn->query("DELETE FROM ".$this->getDBTable($tablename)." WHERE id = '$id' LIMIT 1");
			else 
				$output .= "The $tablename was not deleted";
		}

		return $output;
	} 
	
}//Close Products Class

?>