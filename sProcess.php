<?php
	include 'connection.php';
	session_start();

	if(@$_POST['inputId']) //Get the product price and qty left in the db
	{
		$data = array();
		$product = $_POST['inputId'];
		$product = stripslashes($product);
		$product = mysql_real_escape_string($product);

		try
		{
			$stmt = $conn->prepare("Select productPrice, productQty From location_product Where productId='$product' And productLocation = ".$_SESSION['eLoc']."");																						
			$stmt->execute();
			$result = $stmt->fetchAll();
			foreach($result as $row) 
			{ 
				$data['price'] = $row['productPrice'];
				$data['qty'] = $row['productQty'];
			}
			$data['success'] = true;
			echo json_encode($data);
		}
		catch(PDOException $e)
		{
			$data['success'] = false;
			echo json_encode($data);
		}	
	}

	if(@$_POST['calPrice']) //Get the price to make calculations in the subtotal and total
	{
		$data = array();
		$calPrice = $_POST['calPrice'];
		$calPrice = stripslashes($calPrice);
		$calPrice = mysql_real_escape_string($calPrice);
		try
		{
			$stmt = $conn->prepare("Select productPrice From location_product Where productId='$calPrice' And productLocation = ".$_SESSION['eLoc']."");																						
			$stmt->execute();
			$result = $stmt->fetchAll();
			foreach($result as $row) 
			{ 
				$data['price'] = $row['productPrice'];
			}
			$data['success'] = true;
			echo json_encode($data);
		}
		catch(PDOException $e)
		{
			$data['success'] = false;
			echo json_encode($data);
		}	
	}

	if(@$_POST['promoID']) //Verify numbers required to apply promotion
	{
		$data = array();
		$promoId = $_POST['promoID'];
		$promoId = stripslashes($promoId);
		$promoId = mysql_real_escape_string($promoId);
		try
		{
			$stmt = $conn->prepare("Select p.productId, p.promotionId, p.neededQty, lp.productPromotion, pro.productName, lpro.productPrice From product pro, promotion p, location_promotion lp, location_product lpro Where p.promotionId = '$promoId' And '$promoId' = lp.promotionId And pro.productId = lp.productPromotion AND lp.productPromotion = lpro.productId And lp.locationId = ".$_SESSION['eLoc']." And ".$_SESSION['eLoc']." = lpro.productLocation");																						
			$stmt->execute();
			$result = $stmt->fetchAll();
			foreach($result as $row) 
			{ 
				$data['pId'] = $row['productId']; // product in bag
				$data['pNe'] = $row['neededQty']; // qty nedded of this product
				$data['pPr'] = $row['productPromotion']; // product to be free or discount
				$data['pNa'] = $row['productName']; // product name
				$data['pVa'] = $row['productPrice']; // product Price
				$data['promoID'] = $row['promotionId']; //promotion ID
			}
			$data['success'] = true;
			echo json_encode($data);
		}
		catch(PDOException $e)
		{
			$data['success'] = false;
			echo json_encode($data);
		}	
	}

	if(@$_POST['pIden'] && @$_POST['pQty']) //Pay, Save sale
	{
		$data = array();
		$sub = $_POST['sSub'];
		$pro = $_POST['sPro'];
		$tot = $_POST['sTot'];
		$ids = $_POST['pIden'];
		$qtys = $_POST['pQty'];

		$sub = stripslashes($sub);
		$pro = stripslashes($pro);
		$tot = stripslashes($tot);
		
		$ids = array_map('stripslashes', $ids);
		$qtys = array_map('stripslashes', $qtys);

		$ids = array_map('mysql_real_escape_string', $ids);		
		$qtys = array_map('mysql_real_escape_string', $qtys);
		
		$sub = mysql_real_escape_string($sub);
		$pro = mysql_real_escape_string($pro);
		$tot = mysql_real_escape_string($tot);
		$tot = $sub * 1.1;
		try
		{
			$stmt = $conn->prepare("Insert into sales (saleEmp, subtotal, promoTotal, salesTotal) Values (".$_SESSION['eId'].", '$sub', '$pro', '$tot')");																						
			$stmt->execute();
			$last_id = $conn->lastInsertId();
			$data['tId'] = $last_id;

			if($stmt)
			{   
				$arr_length = count($ids);
				for($i = 0; $i < $arr_length; $i++)
				{
					if($qtys[$i] != 'Promotion')
					{
						$stmt = $conn->prepare("Insert into location_sale (idSale, productId, productQty) Values ('$last_id ', '$ids[$i]', '$qtys[$i]')");																						
						$stmt->execute();
						//Substract Qty product
						$stmt = $conn->prepare("Update location_product Set productQty = productQty - $qtys[$i] Where productId = '$ids[$i]' AND productLocation=".$_SESSION['eLoc']."");																						
						$stmt->execute();
					}
					else
					{

						$promoID = substr($ids[$i], strpos($ids[$i], "#") + 1);
						$promotionItem = substr($ids[$i], strpos($ids[$i], "#"));
						$stmt = $conn->prepare("Insert into location_sale (idSale, productId, productQty, promo) Values ('$last_id ', '$ids[$i]', '1', '$promoID')");	//Put all promos in one line																					
						$stmt->execute();
						//Substract promo Qty product
						$stmt = $conn->prepare("Update location_product Set productQty = productQty - 1 Where productId = '$promotionItem'");																					
						$stmt->execute();
					}
				}
				$data['success'] = true;
				echo json_encode($data);
			}
			else
			{
				$data['success'] = false;
				echo json_encode($data);
			}
		}
		catch(PDOException $e)
		{
			$data['success'] = false;
			echo json_encode($data);
		}	
	}

	if(@$_POST['pId']) //Print Ticket
	{
		$id=$_POST['pId'];
		$id = stripslashes($id);
		$id = mysql_real_escape_string($id);
		$promotions = 0;
		$subTotal = 0;
		$taxes = 0.10;
		$total = 0;

		$stmt = $conn->prepare("Select lp.productPrice, p.productName, ls.productQty, ls.promo FROM location_product lp, sales s, location_sale ls, product p WHERE s.locationId = lp.productLocation And lp.productId = ls.productId And s.saleId = ls.idSale And s.saleId = $id And ls.idSale = $id AND ls.productId = p.productId");																						
		$stmt->execute();
		$result = $stmt->fetchAll();

		echo '<div class="print-img"><img id="logo" alt="" src="./images/logo.png"/></div>
			<div class="print-center"><strong>The Customer Service Experts</strong></div>
			<div class="print-space"></div>
			<div class="print-center">Tel: 1(800)969-7820</div>
			<div class="print-space"></div>
			<div class="print-space"></div>
			<div class="print-item-name">Purchase ID: '.$id.'</div>
			<div class="print-space"></div>
			<div class="print-item-name">Date: '.date("m/d/Y").'</div>
			<div class="print-space"></div>
			<div class="print-item-name">Cashier: '.$_SESSION['eName'] .' '. $_SESSION['eLastname'].'</div>
			<div class="print-space"></div>
			<div class="print-item-name"><strong>Item(s)</strong></div><div class="print-item-price"><strong>Price</strong></div>
			<div class="print-space"></div>';  

	    foreach($result as $row) 
	    { 
	    	if($row['promo'] == 1)
	    	{
	    		$promotions = doubleval($row['productPrice'] + $promotions);
	    		
	    		echo '<div class="print-item-name">'.$row['productName'].'(p)'.'</div><div class="print-item-price">$'.number_format($row['productPrice'],2).'</div>
	    		<div class="print-space"></div>';

	    	}else
	    	{
	    		$subTotal = doubleval($row['productPrice']) + $subTotal;
	    		echo '<div class="print-item-name">'.$row['productName'].'('.$row['productQty'].')'.'</div><div class="print-item-price">$'.number_format($row['productPrice'],2).'</div>
	    		<div class="print-space"></div>';
	    	}
	    	$total = $subTotal + $taxes * $subTotal;
	    }
	    echo '<div class="print-space"></div>
	    	<hr/>
			<div class="print-space"></div>';
			if($promotions != 0)
			{
				echo '<div class="print-item-name">Promotions:</div><div class="print-item-price">$'.number_format($promotions,2).'</div>';
			}
			else
			{
				echo '<div class="print-item-name">Promotions:</div><div class="print-item-price">$0.00</div>';
			}
			echo '<div class="print-space"></div>
				<div class="print-item-name">Sub Total:</div><div class="print-item-price">$'.number_format($subTotal,2).'</div>
				<div class="print-space"></div>
				<div class="print-item-name">Tax:</div><div class="print-item-price">$'.number_format($taxes,2).'</div>
				<div class="print-space"></div>
				<div class="print-item-name">Total</div><div class="print-item-price">$'. number_format($total,2).'</div>
				<div class="print-space"></div>
				<div class="print-space"></div>
				<div class="print-center">Thank you for coming.</div>
				<div class="print-center">We hope you will visit again.</div>';
	}

	if(@$_POST['IDPurchase']) //Void options
	{
		$id=$_POST['IDPurchase'];
		$id = stripslashes($id);
		$id = mysql_real_escape_string($id);

		$stmt = $conn->prepare("Select ls.productId, ls.productQty, ls.promo, ls.void, p.productName From location_sale ls, product p Where ls.productId = p.productId And ls.idSale = $id");																						
		$stmt->execute();
		$result = $stmt->fetchAll();
		if($result)
		{
			echo '<option id='.$id.' selected disabled>Products in Purchase</option>';
			foreach($result as $row) 
		    { 
		    	if($row['productQty'] > $row['void'])
		    	{
					if($row['promo'] == 1)
					{
						echo '<option id='.$row['productId'].'>'.$row['productName'].' (p)</option>';
					}
					else
					{
		    			echo '<option id='.$row['productId'].'>'.$row['productName'].' ('.($row['productQty']-$row['void']).')</option>';
		    		}
		    	}
		    }	
		}
		else
		{
			echo 'false';
		}
	}

	if(@$_POST['iID'])
	{
		$data = array();
		$selectedProId= $_POST['iID'];
		$selectedProId = stripslashes($selectedProId);
		$selectedProId = mysql_real_escape_string($selectedProId);

		try{
			$stmt = $conn->prepare("Select productPrice From location_product Where productId = $selectedProId");																							
			$stmt->execute();
			$result = $stmt->fetchAll();
			foreach($result as $row) 
			{ 
				$data['pPrice'] = $row['productPrice']; // product in bag
			}

			$data['success'] = true;
			echo json_encode($data);
		}
		catch(PDOException $e)
		{ 
			$data['success'] = false;
			echo json_encode($data);
		}
	}

	if(@$_POST['voidPID'] && @$_POST['voidSID'] && @$_POST['voidQTY'])
	{	
		$data = array();
		$voidPID = $_POST['voidPID'];
		$voidPID = stripslashes($voidPID);
		$voidPID = mysql_escape_string($voidPID); 

		$voidSID = $_POST['voidSID'];
		$voidSID = stripslashes($voidSID);
		$voidSID = mysql_escape_string($voidSID);

		$voidQTY = $_POST['voidQTY'];
		$voidQTY = stripslashes($voidQTY);
		$voidQTY = mysql_escape_string($voidQTY);

		$voidTot = $_POST['voidTot'];
		$voidTot = stripslashes($voidTot);
		$voidTot = mysql_escape_string($voidTot);

		$voidPPri = $_POST['voidPPri'];
		$voidPPri = stripslashes($voidPPri);
		$voidPPri = mysql_escape_string($voidPPri);

		try {
			if($voidQTY == 'p')
			{
				$stmt = $conn->prepare("Update location_sale Set void = 1 Where productId = $voidPID And idSale = $voidSID And promo=1");																					
				$stmt->execute();
			}
			else
			{
				$stmt = $conn->prepare("Update location_sale Set void= void + $voidQTY Where productId = $voidPID And idSale = $voidSID AND promo=0");																						
				$stmt->execute();
				$stmt = $conn->prepare("Update sales Set subtotal = subtotal - $voidPPri Where saleId = $voidSID");																						
				$stmt->execute();
				$stmt = $conn->prepare("Update sales Set salesTotal = salesTotal - $voidTot Where saleId = $voidSID");																						
				$stmt->execute();
			}
			$data['success'] = true;
			echo json_encode($data);
		} 
		catch (PDOException $e) 
		{
			$data['success'] = false;
			echo json_encode($data);
		}

	}
?>