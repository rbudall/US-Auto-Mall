<?php

class Store extends CMS{

	//Define Variables
	var $var;
			
	//Constructor
	public function Store(){
	
	}
	
	public function showCart($session_id){
		$db = new Database();
		$paypal = new PayPal();
		$conn = $db->connect();
		$subtotal = 0;
		$total = 0;
		$count = 0;
		
		$live = 'https://www.paypal.com/cgi-bin/webscr';
		
		$tablename = "cart";
		$dbTable = $this->getDBTable($tablename);
		
		setlocale(LC_MONETARY, 'en_US');
		
		if(isset($_POST['submit'])){
			
			if($_POST['submit'] == "Continue Shopping"){
				
			}
			
			for($i = 1; $i <= $_POST['count']; $i++){
			
				$org_qty = $_POST["old_qty_$i"];
				$new_qty = $_POST["new_qty_$i"];
			
				$my_qty = $org_qty;
			
				if($org_qty != $new_qty){
					$my_qty = $new_qty;
					$update = $conn->query("UPDATE $dbTable SET qty = '$my_qty' WHERE id = '".$_POST["id_$i"]."'");
				}
				
				if($my_qty == 0)
					$delete = $conn->query("DELETE FROM $dbTable WHERE id = '".$_POST["id_$i"]."' LIMIT 1");	
			}
		}
		
		if(isset($_GET["action"])){
			
			if(isset($_GET['id']))
				$id = $_GET["id"];
			
			if($_GET['action'] == "delete")	{
				$delete = $conn->query("DELETE FROM $dbTable WHERE id = '$id' LIMIT 1");			
			}
		}
		
		$result = $conn->query("SELECT * FROM $dbTable WHERE session_id = '$session_id'");
		$numrows = $result->num_rows;
		
		$output .= "
					<form id='cart_form' name='cart_form' action='$_SERVER[PHP_SELF]' method='post'>
						<table id='cart_table' cols='5' cellspacing='0px' cellpadding='2px'>
							<tr id='cart_table_title'>
								<td width='300px'>Description</td>
								<td>Price</td>
								<td width='50px'>Quantity</td>
								<td align='right'>Amount</td>
								<td width='100px'>&nbsp;</td>
							</tr>";
						
		if($numrows > 0){
			
			$parameters = array("cmd" => "_cart",
					"upload" => "1",
					"business" => "soaccessoboutique@yahoo.com",
					"image_url" => "https://www.soaccesso.com/images/logo/salogo1_medium.jpg",
			);
			
			
			while($row = $result->fetch_array()){
				$srch_tbl = "products";
				$srch_dbTable = $this->getDBTable($srch_tbl);
				$search = $conn->query("SELECT * FROM $srch_dbTable WHERE id = '$row[product_id]'");
				$srch_row = $search->fetch_array();
				$amount = $srch_row['price'] * $row['qty'];
				$subtotal += $amount;
				$shipping += (5.00 * $row['qty']);
				$tax = 0.07;
				$total = ($subtotal * (1+$tax)) + $shipping;
				$count++;
				
				$parameters["item_name_$count"] = "$srch_row[name]";
				$parameters["item_number_$count"] = "SAO-$row[product_id]";
				$parameters["on".($count-1)] = "size";
				$parameters["os".($count-1)] = "$row[size]";
				$parameters["amount_$count"] = "$srch_row[price]";
				$parameters["quantity_$count"] = "$row[qty]";
				$parameters["shipping_$count"] = (5.00 * $row['qty']);
								
				
				$output .= "<tr bgcolor='#9999FF'>
								<td align='left' width='300px'>
									<div id='cart_desc' style='position:relative; margin-left: 10px;'>
										<a href=''>$srch_row[name]</a><br />
										<div id='cart_desc_small' style='font-size: small; left: 10px; position: relative;'> 
											Item # SAO-$row[product_id]<br />
										 	$row[size] 
										</div>
									</div>	
								</td>
								<td>\$$srch_row[price]</td>
								<td width='50px' align='center'>
									<input name='new_qty_$count' type='text' size='1' value='$row[qty]' >&nbsp;
									<input name='old_qty_$count' type='hidden' value='$row[qty]' >
									<input name='id_$count' type='hidden' value='$row[id]' >
									<input name='count' type='hidden' value='$count' >
								</td>
								<td align='right'>". money_format('%n', $amount) ."</td>
								<td width='100px'>
									<input type='button' name='delete' value='Delete' onclick='window.location.href=\"/cart.php?action=delete&id=$row[id]\"' />									
								</td>
							</tr>
							";	
			}
			
			$parameters["tax_cart"] = ($subtotal*$tax);
			
			$encryptedButton = $paypal->encryptButton($parameters);
			
			$output .= "<tr><td colspan='5' style='background: #999;'>&nbsp;</td></tr>";
			$output .= "<tr><td colspan='3' align='right'>Sub-Total: </td><td align='right'>". money_format('%n', $subtotal) ."</td><td>&nbsp;</td></tr>";
			$output .= "<tr><td colspan='3' align='right'>Tax: </td><td align='right'>". money_format('%n', (($subtotal*$tax))) ."</td><td>&nbsp;</td></tr>";
			$output .= "<tr><td colspan='3' align='right'>Shipping: </td><td align='right'>". money_format('%n', $shipping) ."</td><td>&nbsp;</td></tr>";
			$output .= "<tr><td colspan='3' align='right'>Total: </td><td align='right'>". money_format('%n', $total) ."</td><td>&nbsp;</td></tr>";
			$output .= "<tr><td colspan='3' align='right'></td><td>&nbsp;</td><td><input type='submit' name='submit' value='Update Cart'></td></tr>";
			$output .= "</form>

						<div style='display: table-row'>
							<div style='position: relative; float: left;' >
								<input type='button' name='goBack' value='Continue Shopping' onclick='history.go(-1)'>&nbsp;
							</div>
							
							<div style='position: relative; float: left;'>
								<form method='post' action='$live' enctype='multipart/form-data'>
									<input type=\"hidden\" name=\"cmd\" value=\"_s-xclick\">
									<input type=\"hidden\" name=\"encrypted\" value=\"-----BEGIN PKCS7-----\n$encryptedButton\n-----END PKCS7-----\">
									<input type='submit' name='submit' value='Checkout'>
								</form>
							</div>
						</div>

						</table>";
						
		} else {
			$output .= "<tr><td colspan='5'>There are no items in your cart.</td></tr>
						</table>";
		}
		
		$output .= "</form>";
					
					
		
		return $output;
	}
	
	public function addToCart($product_id, $size, $qty){
		$db = new Database();
		
		$conn = $db->connect();
		
		$sid = session_id();
		
		$tablename = "products";
		$dbTable = $this->getDBTable($tablename);
		$dbCartName = $this->getDBTable("cart");
		
		$result = $conn->query("SELECT * FROM $dbTable WHERE id = '$product_id'");
		$numrows = $result->num_rows;
		
		$srch_cart = $conn->query("SELECT * FROM $dbCartName WHERE session_id = '$sid'");
		
		while($srch_cart_row = $srch_cart->fetch_array()){
			if(($srch_cart_row['product_id'] == $product_id) && ($srch_cart_row['size'] == $size)){
				$new_qty = 	$srch_cart_row['qty'] + $qty;
				$update = $conn->query("UPDATE $dbCartName SET qty = '$new_qty' WHERE id = '$srch_cart_row[id]'");
				return;
			}
		}
		
		if($numrows > 0){
			$row = $result->fetch_array();
			$add = $conn->query("INSERT INTO $dbCartName (id, product_id, session_id, qty, date, size) VALUES (null, '$product_id', '$sid', '$qty', NOW(), '$size')");
		} else {
			$output .= "Error. Item could not be found.";
		}
		
		return $output;
		
	}

}

?>