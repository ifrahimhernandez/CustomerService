<?php 
	include 'connection.php';

	if(@$_POST['idLoc']) //Select store and display items
	{
		$data = array();
		$idLoc = $_POST['idLoc'];

		$idLoc = stripslashes($idLoc);
		$idLoc = mysql_real_escape_string($idLoc);

		try 
		{
			$stmt = $conn->prepare("Select p.productName, lp.productId From product p, location_product lp Where lp.productLocation = '$idLoc' AND lp.productId = p.productId And lp.active = '1'");																						
			$stmt->execute();
			$result = $stmt->fetchAll();
			if(!$result)
			{
				echo '<option selected disabled>'.'Add products to this store'.'</option>';
			}
			else
			{
				echo '<option selected disabled>'.'Select a product'.'</option>';
		    	foreach($result as $row) 
			  	{ 
					echo '<option value="'.$row['productId'].'">'.$row['productName'].'</option>';
			   	}
		   	}
		   	$data['success'] = true;
			echo json_encode($data);
			exit;
		}
		catch(PDOException $e)
		{
			$data['success'] = false;
			echo json_encode($data);
			exit;
		}
	}

	if(@$_POST['dIPID']) //Select item display cost
	{
		$data = array();
		$pId = $_POST['dIPID'];
		$dILoc = $_POST['dILoc'];


		$pId = stripslashes($pId);
		$dILoc = stripslashes($dILoc);
		$pId = mysql_real_escape_string($pId);	
		$dILoc = mysql_real_escape_string($dILoc);	

		try 
		{
			$stmt = $conn->prepare("Select productPrice From location_product Where productId='$pId' AND productLocation='$dILoc' AND active='1'"); 																						
			$stmt->execute();
			$result = $stmt->fetchAll();

	    	foreach($result as $row) 
		  	{ 
				$data['price'] = $row['productPrice'];
				// $data['qty'] = $row['productQty'];
		   	}
		   	$data['success'] = true;
			echo json_encode($data);
			exit;
		}
		catch(PDOException $e)
		{
			$data['success'] = false;
			echo json_encode($data);
			exit;
		}
	}

	if(@$_POST['rNum']) // Verify Delivery Ref Number
	{
		$data = array();
		$rNum = $_POST['rNum'];

		$rNum = stripslashes($rNum);
		$rNum = mysql_real_escape_string($rNum);

		try 
		{
			$stmt = $conn->prepare("Select * From delivery Where deliveryNumRef ='$rNum'"); 																						
			$stmt->execute();
			$result = $stmt->fetchAll();

			if(!$result)
			{
				$data['match'] = false;
			}
			else
			{
				$data['match'] = true;
			}
			echo json_encode($data);
			exit();
		}
		catch(PDOException $e)
		{
			$data['msn'] = "Exception";
			$data['match'] = false;
			echo json_encode($data);
			exit();
		}
	}

	if(@$_POST['refNum']) //Store delivery
	{
		$data = array();
		$storeId = $_POST['storeId'];
		$refNum = $_POST['refNum'];
		$date = $_POST['date'];
		$note = $_POST['note'];
		$products = $_POST['products'];
		$newDate = date("Y-m-d", strtotime($date));
		
		$storeId = stripslashes($storeId);
		$refNum = stripslashes($refNum);
		$date = stripslashes($date);
		$note = stripslashes($note);

		$products = array_map('stripslashes', $products);
		$products = array_map('mysql_real_escape_string', $products);

		$storeId = mysql_real_escape_string($storeId);
		$refNum = mysql_real_escape_string($refNum);
		$date = mysql_real_escape_string($date);
		$note = mysql_real_escape_string($note);

		try 
		{
			$stmt = $conn->prepare("Insert INTO delivery (deliveryNumRef, deliveryDate, locationId, note) Values ('$refNum', '$newDate', '$storeId', '$note')"); 																						
			$stmt->execute();

			foreach ($products as $key => $value) 
			{
				$stmt = $conn->prepare("Insert INTO delivery_product (deliveryNumRef, productId, productQty) Values ('$refNum', '$key', '$value')"); 																						
				$stmt->execute();
			}
			$data['success'] = true;
			echo json_encode($data);
			exit;
		}
		catch(PDOException $e)
		{
			$data['success'] = false;
			echo json_encode($data);
			exit;
		}
	}

?>