<?php

class Products extends CMS{


	//Define Variables
	var $var;
			
	//Constructor
	public function Products(){
		
	}
	
	public function showProducts($id = null, $category = null){
		
		$db = new Database();
		$display = new Display();
		$paypal = &new PayPal();
		
		//$live = 'https://www.paypal.com/cgi-bin/webscr';
		//$sandbox = 'https://www.sandbox.paypal.com/cgi-bin/webscr';  

		$conn = $db->connect();
		
		$tablename = 'products';
		$Tablename = ucfirst($tablename);
		$dbTable = $this->getDBTable($tablename);
		
		$rowsPerPage = 12;
		$indexNum = 1;
		$pageNum = 1;
		$rowIndex = 0;
		$nav = '';
			
		if(isset($_GET['index']))
			$indexNum = $_GET['index'];
			
		if(isset($_GET['pg']))
			$pageNum = $_GET['pg'];	
			
		if(isset($_GET['view']))
			$view = $_GET['view'];	
		
		$offset = ($pageNum - 1) * $rowsPerPage;
		
		$result = $conn->query("SELECT * FROM $dbTable ORDER BY id DESC LIMIT $offset, $rowsPerPage");
		
		if(isset($id))
			$result = $conn->query("SELECT * FROM $dbTable WHERE id = $id LIMIT 1");
		
		if(isset($category))
			$result = $conn->query("SELECT * FROM $dbTable WHERE category = '$category' ORDER BY id DESC LIMIT $offset, $rowsPerPage");
				
		$numrows = $result->num_rows;
		
		$viewCart = array("cmd" => "_cart",
					"display" => "1",
					"business" => "soaccessoboutique@yahoo.com",
		);
		
		//$viewCartButton = $paypal->encryptButton($viewCart);
				
		$output .= "
					<div id='products_viewCart'>
					  		<img onclick=\"window.location='cart.php'\" src=\"rushcms/images/buttons/cart/cart_view.png\" style='cursor:pointer' />
					</div>
					
					<div id='products'>
			
						<h3>Products</h3>";
		
		//If there are no entries in the database...
		if($numrows < 1){
			$output .= "
					<div class='products'>
					
						<div class='overlay_products'></div>
			 
						<div class='products_layout'>
							<div id='products_layout_overlay_top'>
								<div id='products_layout_overlay_middle'>
									<div align='center' class='post_content'>
										
										No products were found in the database.
									
									</div>
								</div>
							 </div>
						</div>
						
						<div class='post_min_height'></div>
						
					</div>";
					
			$output .= "
					</div>";	
					
			return $output;		
		}
		
		//Lets go through the table and show the products...
		while ($row = $result->fetch_array()){
			$rowIndex++;
			
			$parameters = array("cmd" => "_cart",
					"add" => "1",
					"business" => "soaccessoboutique@yahoo.com",
					"item_name" => "$row[name]",
					"item_number" => "SAO-$row[id]",
					"amount" => "$row[price]",
					"no_shipping" => "1",
			);
		
		  	//$encryptedButton = $paypal->encryptButton($parameters);
			
			$mysizes = explode(",",$row['size']);
			$mycolors = explode(",",ucfirst($row['color']));
			
			//Show one product at a time plus product details...$live
			if(isset($id) || isset($_GET['id'])){
				$output .= "
							<form action=\"cart.php?action=addTo&pid=$row[id]\" method=\"post\">
								<div class='products_large' style='float: left;'>
									
									<div class='overlay_products'></div>
									
									<div id='products_large'>
										 <div id='products_layout_overlay_top'>
											<div id='products_layout_overlay_middle'>	
												<img src='$row[picture]' />
											</div>
										</div>
									 </div>
									
									<div class='post_min_height'></div>
								
								</div>
								
								<div id='product_details' align='left'>
									
									<strong>Name:</strong> $row[name]
									<br />
									<strong>Sizes:</strong>
										<select name='on0' value='size'>";
								
				foreach($mysizes as $cur_size)
					$output .= "<option name=\"os0\" value=\"Size $cur_size\">$cur_size</option>";	
												
				$output .= "		
										</select>
									<br />
									<strong>Color:</strong> $row[color]
									<br />
									<strong>Price:</strong> \$$row[price]
									<br />								
									<strong>Quantity:</strong> $row[quantity]
									<br />
									<br />
									<strong>Description:</strong>
										<div id='product_description'>
											$row[description]
										</div>
										<div id='product_addToCart'>
												<input type=\"hidden\" name=\"cmd\" value=\"_s-xclick\">
												<input type='hidden' name='status' value='submit'>
												<input type=\"image\" src=\"../rushcms/images/buttons/cart/cart_add.png\" border=\"0\" name=\"submit\" alt=\"Make payments with PayPal - it\'s fast, free and secure!\">
												<input type=\"hidden\" name=\"encrypted\" value=\"-----BEGIN PKCS7-----\n$encryptedButton\n-----END PKCS7-----\">
										</div>
									</div>
								</form>";

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
					$prev  = " <span class='link'><a href=\"$self?view=$view&action=$action&do=view$Tablename&id=$prevID&index=$prevPage&pg=$pageNum$sort_link\"> Prev </a></span> ";
				} else {
					$prev  = '&nbsp;'; // we're on page one, don't print previous link
				}
			
				if ($indexNum < $rowIndex){
					$nextPage = $indexNum + 1;
					$next = " <span class='link'><a href=\"$self?view=$view&action=$action&do=view$Tablename&id=$nextID&index=$nextPage&pg=$pageNum$sort_link\"> Next </a></span> ";
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
											
				$output .= "
							<div id='list_products'>
								<div class='products' onclick=\"window.location.href='$self?view=$view&action=$action&do=viewProducts&id=$row[id]&index=$counter&pg=$pageNum$sort_link'\" onmouseover='document.getElementById(\"product_$row[id]\").style.backgroundColor=\"#FFF\"' onmouseout='document.getElementById(\"product_$row[id]\").style.backgroundColor=\"#000\"'>
									<div class='overlay_products' id=\"product_$row[id]\"></div>
						 
									<div class='products_layout'>
										 <div id='products_layout_overlay_top'>
											<div id='products_layout_overlay_middle'>
												<img src=\"$row[picture]\" />
											</div>
										 </div>
									</div>
							
									<div class='products_min_height'></div>
								</div>
							
								<div id='products_name' onclick=\"window.location.href='$self?view=$view&action=$action&do=viewProducts&id=$row[id]&index=$counter&pg=$pageNum$sort_link'\" onmouseover='document.getElementById(\"product_$row[id]\").style.backgroundColor=\"#FFF\"' onmouseout='document.getElementById(\"product_$row[id]\").style.backgroundColor=\"#000\"'>
									".$display->neat_trim(($row['name']), 20)."<br />
									$$row[price]<br />
								</div>
						
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
			  $nav .= " $page "; // no need to create a link to current page
			} else {
				$nav .= " <span class='link'><a href=\"$self?view=$view&pg=$page\">$page</a></span> ";
			} 
		}	
		
		if ($pageNum > 1){
			$page  = $pageNum - 1;
			$prev  = " <span class='link'><a href=\"$self?view=$view&pg=$page\"> Prev </a></span> ";
			$first = " <span class='link'><a href=\"$self?view=$view&pg=1\"> |<< </a></span> ";
		} else {
			$prev  = '&nbsp;'; // we're on page one, don't print previous link
			$first = '&nbsp;'; // nor the first page link
		}
	
		if ($pageNum < $maxPage){
			$page = $pageNum + 1;
			$next = " <span class='link'><a href=\"$self?view=$view&pg=$page\"> Next </a></span> ";
			$last = " <span class='link'><a href=\"$self?view=$view&pg=$maxPage\"> >>| </a></span> ";
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
		
		$conn = $db->connect();
		$tablename = "products";
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
		
		$output .= $display->listTable($tablename, $rowsPerPage);
		
		//Lets add a post to the database
		if($do == "add$Tablename"){
			//If producted was just added...
			if(isset($_GET['status'])){
				$status = $_GET['status'];
				$output .= "$Tablename Added";
				return $output;
			}
			
			//If form was submitted, check input and upload to database
			if(isset($_GET['submit'])){
				$submit = $_GET['submit'];
		
				$v_name = $_POST['name'];
				$v_category = $_POST['category'];
				$v_picture = $_FILES['picture'];
				$v_size = $_POST['size'];
				$v_color = $_POST['color'];
				$v_price = $_POST['price'];
				$v_quantity = $_POST['quantity'];
				$v_description = $_POST['description'];
					
			
				if(isset($_POST["add$Tablename_submit"])){
					if(!empty($v_name)){
						
						if($_FILES['picture']['size'] > 0){
					
							$upload = $this->uploadPic($_FILES['picture'], "_images/_uploads/_products/");
							
							if($upload) {
								$result = $conn->query("INSERT INTO ".$this->getDBTable($tablename)." (id, name, category, picture, size, color, price, quantity, description) VALUES (NULL, '$v_name', '$v_category','$upload', '$v_size', '$v_color', '$v_price', '$v_quantity', '$v_description')");
							} else {
								throw new Exception("There was an error uploading the file, please try again!");
							}
							
							if($result){
								$output .= "<meta http-equiv='refresh' content='0;URL=$self?view=$view&action=$action&do=$do&status=done&pg=$pageNum$sort_link'>";	
								return $output;
							} else {
								throw new Exception("Changes to the $Tablename could not be saved at this time.");
							}
						} else {
							$v_picture = "&nbsp;";
							$result = $conn->query("INSERT INTO ".$this->getDBTable($tablename)." (id, name, category, picture, size, color, price, quantity, description) VALUES (NULL, '$v_name', '$v_category','$v_picture', '$v_size', '$v_color', '$v_price', '$v_quantity', '$v_description')");
							
							if($result){
								$output .= "<meta http-equiv='refresh' content='0;URL=$self?view=$view&action=$action&do=$do&status=done&pg=$pageNum$sort_link'>";	
								return $output;
							} else {
								throw new Exception("Changes to the $Tablename could not be saved at this time.");
							}
						}
					} else if(empty($v_content)) {
						$output .= "A $Tablename cannot be added with no content. Please try again.";
					}
				}
				
				if(isset($_POST["add$Tablename_cancel"])){
					$output .= "$Tablename was not added.";
					return $output;	
				}
			}
			$output .= "
						<div id='add$Tablename' align='left'>
							
							<div id='content'>
								<form name='add$Tablename' method='post' enctype='multipart/form-data' action='$self?view=$view&action=$action&do=$do&submit=submit$Tablename&pg=$pageNum$sort_link'>
									".$display->showAddProduct()."
								</form>
							</div>
						</div>";
		}
		
		//Lets edit a product in the database
		if($do == "edit$Tablename"){
			
			//If the product was edited successfully...
			if(isset($_GET['status'])){
				$status = $_GET['status'];
				$output .= "$Tablename Edited";
				return $output;
			}
			
			//Changes have been made to a product and need to be updated in the database...
			if(isset($_GET['submit'])){
				$submit = $_GET['submit'];
		
				$v_name = $_POST['name'];
				$v_category = $_POST['category'];
				$v_picture = $_FILES['picture'];
				$v_size = $_POST['size'];
				$v_color = $_POST['color'];
				$v_price = $_POST['price'];
				$v_quantity = $_POST['quantity'];
				$v_description = $_POST['description'];
				
				if(!empty($v_picture)){ 
					
					$upload = $this->uploadPic($_FILES['picture'], "_images/_uploads/_products/");
							
					$v_picture = ", picture = '$upload'";	
				}else{
					$v_picture = '';
				}
				
				//Update the database with any values that have changed...
				if(isset($_POST["edit$Tablename_submit"])){
					if(!empty($v_name)){
					
						$update = $conn->query("UPDATE ".$this->getDBTable($tablename)." SET name='$v_name', category='$v_category', size='$v_size', color='$v_color', price='$v_price', quantity='$v_quantity', description='$v_description' $v_picture WHERE id='$id' LIMIT 1");
						 
						if($update){
							$output .= "<meta http-equiv='refresh' content='0;URL=$self?view=$view&action=$action&do=edit$Tablename&status=done&id=$id&index=$index&pg=$pageNum$sort_link'>";
							return $output;
						} else {
							throw new Exception("Changes to the $Tablename could not be saved at this time.");
						}
					} else if(empty($v_name)) {
						$output .= "A $Tablename cannot be edited with no name. Please try again.";
					}
			
				}
				
				//If edit cancelled, tell user...
				if(isset($_POST["edit$Tablename_cancel"])){
					$output .= "$Tablename was not edited.";
					return $output;	
				}
					
			}
			
			//If product was deleted then refresh page...
			if(isset($_GET['id'])){
				$id = $_GET['id'];
				
				$result  = $conn->query("SELECT COUNT(*) AS numrows FROM ".$this->getDBTable($tablename)." WHERE id='$id' LIMIT 1");
				$row     = $result->fetch_array(MYSQL_ASSOC);
				$numrows = $row['numrows'];
				
				if($numrows < 1)
					echo "<meta http-equiv='refresh' content=\"0;url=$self?view=$view&action=$action&do=delete$Tablename&status=done&index=$index&pg=$pageNum$sort_link\">";
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
						
							<div id='content'>
								<form name='edit$Tablename' method='post' enctype='multipart/form-data' action='$self?view=$view&action=$action&do=$do&id=$row[id]&submit=submit$Tablename&index=$index&pg=$pageNum$sort_link'>
									<div>ID #$row[id]</div>
									
									<table>
									<tr>
										<td valign='top'>
											<div id='edit_products_pic'> <img src=\"$row[picture]\" width='100%' height='100%'/></div>
										</td>	
										<td valign='top'>
											<div class='input_name'>Category: </div>
											<div class='input_field'>
												<select name='category'>";
												
							$categories = $conn->query("SELECT * FROM ".$this->getDBTable("categories"));

							
							while ($cat_row = $categories->fetch_array()){
								
								$cat_name = ucfirst($cat_row['name']);
								
								if($cat_name == ucfirst($row['category'])){
									$output .= "<option selected='selected' name='$row[category]'  value=\"".ucfirst($row['category'])."\" >".ucfirst($row['category'])."</option>";
								} else {
									$output .= "<option name='$cat_name'  value=\"$cat_name\" >$cat_name</option>";
								}
								
							}					
												
												/*	<option selected='selected' name='$row[category]'  value=\"".ucfirst($row['category'])."\" >".ucfirst($row['category'])."</option>
													<option disabled='disabled'>---------------</option>
													<option name='new'  value=\"New\" >New</option>
													<option name='shoes'  value=\"Shoes\" >Shoes</option>
													<option name='clothes'  value=\"Clothes\" >Clothes</option>
													<option name='accessories'  value=\"Accessories\" >Accessories</option>
													<option name='jewelry'  value=\"Jewelry\" >Jewelry</option>
													<option name='sale'  value=\"Sale\" >Sale</option>
												*/
												
												
							$output .=	"		</select>
											</div>
											<br />
											<div class='input_name'>Name: </div>
											<div class='input_field'><input name='name' type='text' value=\"$row[name]\"  /></div>
											<br />
											<br />
											<div class='input_name'>Size: </div>
											<div class='input_field'><input name='size' type='text'  value=\"$row[size]\"  /></div>
											<br />
											<div class='input_name'>Price: </div>
											<div class='input_field'><input name='price' type='text'  value=\"$row[price]\" onfocus=\"if (value == '$row[price]'){value=''}\" onblur=\"if (value == ''){value='$row[price]'}\"   /></div>
											<br />
											<br />
											
										</td>
										<td valign='top'>
											<br />
											<br />
											<br />
											<div class='input_name'>Color: </div>
											<div class='input_field'><input name='color' type='text'  value=\"$row[color]\"  /></div>
											<br />
											<div class='input_name'>Quantity: </div>
											<div class='input_field'><input name='quantity' type='text'  value=\"$row[quantity]\" onfocus=\"if (value == '$row[quantity]'){value=''}\" onblur=\"if (value == ''){value='$row[quantity]'}\"  /></div>
											<br />
										</td>
										
									</tr>
									<tr>
										<td colspan='3'>
											<br />
											<div class='input_name'>Picture: </div>$row[picture]
											<div class='input_field'><input type='file' name='picture' /><input type='hidden' name='pic_url' value='$row[picture]' /></div>
											<br />
										</td>
									</tr>
								</table>
		
								<br />
								
								<div class='input_name'>Description: </div>
								<div id='textbox'>
									<textarea class='ckeditor' id='editor1' name='description' cols='80' rows='10'>$row[description]</textarea>
								</div>
								
								<br />
								
								<input align='middle' name='edit$Tablename_submit' type='submit' value='Edit $Tablename' />
								<input align='middle' name='edit$Tablename_cancel' type='submit' value='Cancel' />
								
								</form>
							</div>
						</div>";
					
					$result  = $conn->query("SELECT COUNT(*) AS numrows FROM ".$this->getDBTable($tablename)." ORDER BY `id` DESC");
					$row     = $result->fetch_array(MYSQL_ASSOC);
					$numrows = $row['numrows'];
					
					$result2  = $conn->query("SELECT id FROM ".$this->getDBTable($tablename)." ORDER BY `id` DESC ");
			
					$my_index = ($index - $offset) + $offset;

					if($offset == 0)
						$my_index = $index;
					
					$rowIndex = 0;
					
					while($row = $result2->fetch_row()){
						$rowIndex++;
							
							
						if($rowIndex == ($my_index - 1))
							$prevID = $row[0];
						
						if($rowIndex == ($my_index + 1))
							$nextID = $row[0];
						
						

						if($my_index == (($rowsPerPage*($pageNum-1)) + 1)){		
							$pg = $pageNum - 1;
	
							$prevRow = $my_index - 1;
							$prev  = " <span class='link'><a href=\"$self?view=$view&action=$action&do=edit$Tablename&id=$prevID&index=$prevRow&pg=$pg$sort_link\"> Prev </a></span> ";
						} elseif ($index > 1){
							$pg = $pageNum;
							$prevRow  = $index - 1;
							$prev  = " <span class='link'><a href=\"$self?view=$view&action=$action&do=edit$Tablename&id=$prevID&index=$prevRow&pg=$pg$sort_link\"> Prev </a></span> ";
						} else  {
							$prev  = '&nbsp;'; // we're on page one, don't print previous link
						}
					
						if(is_int($index/$rowsPerPage) && ($index + $offset) != $numrows ){		
							$pg = $pageNum + 1;
							$nextRow = $index + 1;
							$next = " <span class='link'><a href=\"$self?view=$view&action=$action&do=edit$Tablename&id=$nextID&index=$nextRow&pg=$pg$sort_link\"> Next </a></span> ";
						} elseif ($index < $numrows){
							$pg = $pageNum;
							$nextRow = $index + 1;
							$next = " <span class='link'><a href=\"$self?view=$view&action=$action&do=edit$Tablename&id=$nextID&index=$nextRow&pg=$pg$sort_link\"> Next </a></span> ";
						} else  {
							$next = '&nbsp;'; // we're on the last page, don't print next link
						}
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
				$output .= "$Tablename Deleted";
				return $output;
			}
			
			if(isset($_GET['id'])){
				$id = $_GET['id'];
				
				$result = $conn->query("DELETE FROM ".$this->getDBTable($tablename)." WHERE id = '$id' LIMIT 1");
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
		
		$conn = $db->connect();
		$tablename = "categories";
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
		
		$output .= $display->listTable($tablename, $rowsPerPage);
		
		//Lets add a post to the database
		if($do == "add$Tablename"){
			//If producted was just added...
			if(isset($_GET['status'])){
				$status = $_GET['status'];
				$output .= "$Tablename Added";
				return $output;
			}
			
			//If form was submitted, check input and upload to database
			if(isset($_GET['submit'])){
				$submit = $_GET['submit'];
		
				$v_name = $_POST['name'];
				$v_parent = $_POST['parent'];
				$v_id = $_POST['id'];

					
			
				if(isset($_POST["add$Tablename_submit"])){
					if(!empty($v_name)){
						
						
							$result = $conn->query("INSERT INTO ".$this->getDBTable($tablename)." (id, name, parent) VALUES (NULL, '$v_name', '$v_parent')");
							
							if($result){
								$output .= "<meta http-equiv='refresh' content='0;URL=$self?view=$view&action=$action&do=$do&status=done&pg=$pageNum$sort_link'>";	
								return $output;
							} else {
								throw new Exception("Changes to the $Tablename could not be saved at this time.");
							}
						
					} 
				}
				
				if(isset($_POST["add$Tablename_cancel"])){
					$output .= "$Tablename was not added.";
					return $output;	
				}
			}
			$output .= "
						<div id='add$Tablename' align='left'>
							
							<div id='content'>
								<form name='add$Tablename' method='post' enctype='multipart/form-data' action='$self?view=$view&action=$action&do=$do&submit=submit$Tablename&pg=$pageNum$sort_link'>
									<div class='input_name'>Name: </div>
									<div class='input_field'><input name='name' type='text'  value=\"\"  /></div>
									
								
									<br />
									
									<div class='input_name'>Parent: </div>
									<div class='input_field'>
										<select name='parent'>
											<option name=''  value=\"\" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
												
			$categories = $conn->query("SELECT * FROM ".$this->getDBTable("categories"));

			
			while ($cat_row = $categories->fetch_array()){
				
				$cat_name = ucfirst($cat_row['name']);
											
				$output .= "<option name='$cat_name'  value=\"$cat_name\" >$cat_name</option>";
										
			}					
								
								
			$output .=	"		</select>
								</div>
									<br />

								
									<input align='middle' name='add$Tablename_submit' type='submit' value='Add $Tablename' />
									<input align='middle' name='add$Tablename_cancel' type='submit' value='Cancel' />
								
								</form>
							</div>
						</div>";
		}
		
		//Lets edit a product in the database
		if($do == "edit$Tablename"){
			
			//If the product was edited successfully...
			if(isset($_GET['status'])){
				$status = $_GET['status'];
				$output .= "$Tablename Edited";
				return $output;
			}
			
			//Changes have been made to a product and need to be updated in the database...
			if(isset($_GET['submit'])){
				$submit = $_GET['submit'];
		
				$v_id = $_POST['id'];
				$v_name = $_POST['name'];
				$v_parent = $_POST['parent'];
				
				//Update the database with any values that have changed...
				if(isset($_POST["edit$Tablename_submit"])){
					if(!empty($v_name)){
					
						$update = $conn->query("UPDATE ".$this->getDBTable($tablename)." SET name='$v_name', parent='$v_parent', id='$v_id' WHERE id='$id' LIMIT 1");
						 
						if($update){
							$output .= "<meta http-equiv='refresh' content='0;URL=$self?view=$view&action=$action&do=edit$Tablename&status=done&id=$id&index=$index&pg=$pageNum$sort_link'>";
							return $output;
						} else {
							throw new Exception("Changes to the $Tablename could not be saved at this time.");
						}
					} else if(empty($v_name)) {
						$output .= "A $Tablename cannot be edited with no name. Please try again.";
					}
			
				}
				
				//If edit cancelled, tell user...
				if(isset($_POST["edit$Tablename_cancel"])){
					$output .= "$Tablename was not edited.";
					return $output;	
				}
					
			}
			
			//If product was deleted then refresh page...
			if(isset($_GET['id'])){
				$id = $_GET['id'];
				
				$result  = $conn->query("SELECT COUNT(*) AS numrows FROM ".$this->getDBTable($tablename)." WHERE id='$id' LIMIT 1");
				$row     = $result->fetch_array(MYSQL_ASSOC);
				$numrows = $row['numrows'];
				
				if($numrows < 1)
					echo "<meta http-equiv='refresh' content=\"0;url=$self?view=$view&action=$action&do=delete$Tablename&status=done&index=$index&pg=$pageNum$sort_link\">";
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
						
							<div id='content'>
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
					
					if($cat_name == ucfirst($row['parent'])){
						$output .= "<option selected='selected' name='$row[parent]'  value=\"".ucfirst($row['parent'])."\" >".ucfirst($row['parent'])."</option>";
					} else {
						$output .= "<option name='$cat_name'  value=\"$cat_name\" >$cat_name</option>";
					}
					
				}					
									
				$output .=	"		</select>
								</div>
									
									<br />

								
								<input align='middle' name='edit$Tablename_submit' type='submit' value='Edit $Tablename' />
								<input align='middle' name='edit$Tablename_cancel' type='submit' value='Cancel' />
								
								</form>
							</div>
						</div>";
					
					$result  = $conn->query("SELECT COUNT(*) AS numrows FROM ".$this->getDBTable($tablename)." ORDER BY `id` DESC");
					$row     = $result->fetch_array(MYSQL_ASSOC);
					$numrows = $row['numrows'];
					
					$result2  = $conn->query("SELECT id FROM ".$this->getDBTable($tablename)." ORDER BY `id` DESC ");
			
					$my_index = ($index - $offset) + $offset;

					if($offset == 0)
						$my_index = $index;
					
					$rowIndex = 0;
					
					while($row = $result2->fetch_row()){
						$rowIndex++;
							
							
						if($rowIndex == ($my_index - 1))
							$prevID = $row[0];
						
						if($rowIndex == ($my_index + 1))
							$nextID = $row[0];
						
						

						if($my_index == (($rowsPerPage*($pageNum-1)) + 1)){		
							$pg = $pageNum - 1;
	
							$prevRow = $my_index - 1;
							$prev  = " <span class='link'><a href=\"$self?view=$view&action=$action&do=edit$Tablename&id=$prevID&index=$prevRow&pg=$pg$sort_link\"> Prev </a></span> ";
						} elseif ($index > 1){
							$pg = $pageNum;
							$prevRow  = $index - 1;
							$prev  = " <span class='link'><a href=\"$self?view=$view&action=$action&do=edit$Tablename&id=$prevID&index=$prevRow&pg=$pg$sort_link\"> Prev </a></span> ";
						} else  {
							$prev  = '&nbsp;'; // we're on page one, don't print previous link
						}
					
						if(is_int($index/$rowsPerPage) && ($index + $offset) != $numrows ){		
							$pg = $pageNum + 1;
							$nextRow = $index + 1;
							$next = " <span class='link'><a href=\"$self?view=$view&action=$action&do=edit$Tablename&id=$nextID&index=$nextRow&pg=$pg$sort_link\"> Next </a></span> ";
						} elseif ($index < $numrows){
							$pg = $pageNum;
							$nextRow = $index + 1;
							$next = " <span class='link'><a href=\"$self?view=$view&action=$action&do=edit$Tablename&id=$nextID&index=$nextRow&pg=$pg$sort_link\"> Next </a></span> ";
						} else  {
							$next = '&nbsp;'; // we're on the last page, don't print next link
						}
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
				$output .= "$Tablename Deleted";
				return $output;
			}
			
			if(isset($_GET['id'])){
				$id = $_GET['id'];
				
				$result = $conn->query("DELETE FROM ".$this->getDBTable($tablename)." WHERE id = '$id' LIMIT 1");
			} else {
				$output .= "The $tablename was not deleted";
			}
		}

		return $output;
	} 
	
}//Close Products Class

?>