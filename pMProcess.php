<?php 
	include 'connection.php';

	if(@$_POST['productId']) //Delete selected product
	{
		$productId = $_POST['productId'];

		$productId = stripslashes($productId);
		$productId = mysql_real_escape_string($productId);

		$data = array();
		try 
		{
			$stmt = $conn->prepare("Update location_product Set active='0' Where productId='$productId'");
			$stmt->execute();
			$data['success'] = true;
			echo json_encode($data);
			exit;
		} 
		catch (PDOException $e)
		{
			$data['success'] = false;
			echo json_encode($data);
			exit;
		}
	}

	if(@$_POST['proEId']) //Edit selected product
	{
		$data = array();
		$productEId = $_POST['proEId'];
		$productEName = $_POST['proEName'];
		$productEPrice = $_POST['proEPrice'];
		// $productQuantity = $_POST['proEQty'];
		$productLoc = $_POST['proELoca'];

		$productEId = stripslashes($productEId);
		$productEName = stripslashes($productEName);
		$productEPrice = stripslashes($productEPrice);
		$productLoc = stripslashes($productLoc);

		$productEId = mysql_real_escape_string($productEId);
		$productEName = mysql_real_escape_string($productEName);
		$productEPrice = mysql_real_escape_string($productEPrice);
		$productLoc = mysql_real_escape_string($productLoc);

		try
		{
			$stmt = $conn->prepare("Update location_product Set productLocation='$productLoc', productPrice='$productEPrice' Where productId='$productEId'");
			$stmt->execute();

			$stmt = $conn->prepare("Update product Set productName='$productEName' Where productId='$productEId'");
			$stmt->execute();
			$data['success'] = true;
			echo json_encode($data);
		}
		catch (PDOException $e)
		{
			$data['success'] = false;
			echo json_encode($data);
			exit;
		}

	}

	if(@$_POST['proAddID']) //Add
	{
		$data = array();
		$proAddID = $_POST['proAddID'];
		$proAPrice = $_POST['proAPrice'];
		$proALoca = $_POST['proALoc'];

		$proAddID = stripslashes($proAddID);
		$proAPrice = stripslashes($proAPrice);
		$proALoca = stripslashes($proALoca);

		$proAddID = mysql_real_escape_string($proAddID);
		$proAPrice = mysql_real_escape_string($proAPrice);
		$proALoca = mysql_real_escape_string($proALoca);
		try
		{
			$stmt = $conn->prepare("Insert INTO location_product (productId, productLocation, productPrice) Values ('$proAddID', '$proALoca', '$proAPrice')");
			$stmt->execute();

			$data['success'] = true;
			echo json_encode($data);
			exit;
		}
		catch (PDOException $e)
		{
			$data['err'] = $e->getMessage();
			$data['success'] = false;
			echo json_encode($data);
			exit;
		}
		
	}

	if(@$_POST['proAName']) //Add
	{
		$data = array();
		$proAPrice = $_POST['proAPrice'];
		$proALoca = $_POST['proALoc'];
		$proAName = $_POST['proAName'];

		$proAPrice = stripslashes($proAPrice);
		$proALoca = stripslashes($proALoca);
		$proAName = stripslashes($proAName);

		$proAName = mysql_real_escape_string($proAName);
		$proAPrice = mysql_real_escape_string($proAPrice);
		$proALoca = mysql_real_escape_string($proALoca);

		try
		{
			$stmt = $conn->prepare("Insert INTO product (productName) Values ('$proAName')");
			$stmt->execute();

			$last_id = $conn->lastInsertId();

			$stmt = $conn->prepare("Insert INTO location_product (productId, productLocation, productPrice) Values ('$last_id', '$proALoca', '$proAPrice')");
			$stmt->execute();

			$data['success'] = true;
			echo json_encode($data);
			exit;
		}
		catch (PDOException $e)
		{
			$data['err'] = $e->getMessage();
			$data['success'] = false;
			echo json_encode($data);
			exit;
		}
	}


	if(@$_POST['pNAt'])
	{
		$pNAt = $_POST['pNAt'];
		$pNAt = stripslashes($pNAt);
		$pNAt = mysql_real_escape_string($pNAt);
		$data = array();
		try 
		{
			$stmt = $conn->prepare("Select * FROM product t1 WHERE NOT EXISTS (SELECT 1 FROM location_product t2 WHERE t1.productId = t2.productId AND t2.productLocation = '$pNAt' AND t2.active = 1)");
			$stmt->execute();
			$result = $stmt->fetchAll();
			if($stmt) 
			{
			    foreach($result as $row) 
			    { 
			    	echo '<option value="'.$row['productId'].'">'.$row['productName'].'</option>';
			    }
			}
		} 
		catch (PDOException $e)
		{
			$data['success'] = false;
			echo json_encode($data);
		}
	}

	if(@$_POST['pAt'])
	{
		$pAt = $_POST['pAt'];
		$pAt = stripslashes($pAt);
		$pAt = mysql_real_escape_string($pAt);

		$data = array();
		try 
		{
			$stmt = $conn->prepare("Select p.productName, lp.productId From product p, location_product lp Where lp.active='1' AND lp.productLocation='$pAt' AND lp.productId = p.productId");
			$stmt->execute();
			$result = $stmt->fetchAll();
			if($stmt) 
			{
			    foreach($result as $row) 
			    { 
			    	echo '<option value="'.$row['productId'].'">'.$row['productName'].'</option>';
			    }
			}
		} 
		catch (PDOException $e)
		{
			$data['success'] = false;
			echo json_encode($data);
		}
	}

// //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Need Work
// 	if(@$_POST['refreshProducts']) //Refresh After add and delete
// 	{
// 			$stmt = $conn->prepare("Select * From product Where active='1' Order By productName ASC");																						
// 			$stmt->execute();
// 			$result = $stmt->fetchAll();
// 		    foreach($result as $row) 
// 		    { 
// 		    	echo '<option value="'.$row['productId'].'">'.$row['productName'].'</option>';
// 		    }
// 	}

	if(@$_POST['proId']) //Select product display info in textbox
	{
		$proId = $_POST['proId'];
		$proSt = $_POST['proSt'];

		$proId = stripslashes($proId);
		$proSt = stripslashes($proSt);
		$proId = mysql_real_escape_string($proId);
		$proSt = mysql_real_escape_string($proSt);

		$data = array();
		try 
		{
			$stmt = $conn->prepare("Select p.productName, lp.productPrice, lp.productLocation From product p, location_product lp Where lp.productLocation='$proSt' AND p.productId= '$proId' AND lp.productId= '$proId'");
			$stmt->execute();
			$result = $stmt->fetchAll();
			foreach($result as $row) 
		    { 
		    	$data['pName'] = $row['productName'];
		    	$data['pPrice'] = $row['productPrice'];
		    	$data['pLocation'] = $row['productLocation'];
		    	// $data['pQty'] = $row['productQty'];
		    }

			$data['success'] = true;
			echo json_encode($data);
			exit;
		} 
		catch (PDOException $e)
		{
			$data['success'] = false;
			echo json_encode($data);
			exit;
		}
	}
?>