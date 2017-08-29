<?php
	include 'connection.php';

	if(@$_POST['locId']) //When a location is selected displays promo from that location
	{
		$locId = $_POST['locId'];
		$locId = stripslashes($locId);
		$locId = mysql_real_escape_string($locId);

		$stmt = $conn->prepare("Select p.promotionName, p.promotionId From promotion p, location_promotion lp Where lp.locationId = '$locId' AND lp.promotionId = p.promotionId AND lp.active='1'");																						
		$stmt->execute();
		$result = $stmt->fetchAll();

	    foreach($result as $row) 
	    { 
	    	echo '<option value="'.$row['promotionId'].'">'.$row['promotionName'].'</option>';
	    }
	}

	if(@$_POST['deleteId']) //Delete promotion
	{
		$data = array();
		$deleteId = $_POST['deleteId'];
		$deleteId = stripslashes($deleteId);
		$deleteId = mysql_real_escape_string($deleteId);

		$promoLocation = $_POST['promoLocation'];
		$promoLocation = stripslashes($promoLocation);
		$promoLocation = mysql_real_escape_string($promoLocation);

		try {
				$stmt = $conn->prepare("Update location_promotion set active='0' Where promotionId='$deleteId' And locationId='$promoLocation'");																						
				$stmt->execute();

				$data['success'] = true;
				echo json_encode($data);
			}
		catch(PDOException $e)
		{
			$data['success'] = false;
			echo json_encode($data);
			exit;
		}
	}

	if(@$_POST['promoSelectedId']) //Select a promo and display its info
	{
		$data = array();
		$selecId = $_POST['promoSelectedId'];
		$selecId = stripslashes($selecId);
		$selecId = mysql_real_escape_string($selecId);

		$promoLocation = $_POST['promoLocation'];
		$promoLocation = stripslashes($promoLocation);
		$promoLocation = mysql_real_escape_string($promoLocation);

		try {
				$stmt = $conn->prepare("Select p.promotionName, p.productId, p.neededQty, lp.productPromotion From promotion p, location_promotion lp Where lp.active = '1' AND '$selecId' = lp.promotionId AND p.promotionId = '$selecId' And lp.locationId = '$promoLocation'");																						
				$stmt->execute();
				$result = $stmt->fetchAll();

				foreach($result as $row) 
			    { 
			    	$data['promotionName'] = $row['promotionName'];
			    	$data['productId'] = $row['productId'];
			    	$data['neededQty'] = $row['neededQty'];
			    	$data['productPromotion'] = $row['productPromotion'];
			    }
				$data['success'] = true;
				echo json_encode($data);
			}
		catch(PDOException $e)
		{
			$data['success'] = false;
			echo json_encode($data);
			exit;
		}
	}

	if(@$_POST['promoAdd'] == '1') //Add a new promo
	{	
		$data = array();
		$errors = array();

		$promoName = $_POST['promoName'];
		$promoProductNeeded = $_POST['promoProNeeded'];
		$promoQtyNeeded = $_POST['promoQtyNeeded'];
		$promoAtPromotion = $_POST['promoInPromo'];
		$promoLocal = $_POST['promoLocalize'];

		$promoName = stripslashes($promoName);
		$promoProductNeeded = stripslashes($promoProductNeeded);
		$promoQtyNeeded = stripslashes($promoQtyNeeded);
		$promoAtPromotion = stripslashes($promoAtPromotion);
		$promoLocal = stripslashes($promoLocal);

		$promoName = mysql_real_escape_string($promoName);
		$promoProductNeeded = mysql_real_escape_string($promoProductNeeded);
		$promoQtyNeeded = mysql_real_escape_string($promoQtyNeeded);
		$promoAtPromotion = mysql_real_escape_string($promoAtPromotion);
		$promoLocal = mysql_real_escape_string($promoLocal);

		try {
			$stmt = $conn->prepare("Insert Into promotion (promotionName, productId, neededQty) Values ('$promoName','$promoProductNeeded','$promoQtyNeeded')");													
			$stmt->execute();

			$last_id = $conn->lastInsertId();

			$stmt = $conn->prepare("Insert Into location_promotion (promotionId, locationId, productPromotion) Values ('$last_id','$promoLocal','$promoAtPromotion')");													
			$stmt->execute();
			
			$data['success'] = true;
			$data['message'] = "The promotion: '$promoName' has being added!";
			echo json_encode($data);
		}
		catch(PDOException $e)
		{
		 	$errors['exp'] = 'Error: Adding Promotion!';
		 	$data['errors'] = $errors;
			$data['success'] = false;
			echo json_encode($data);
			exit;
		}
	}

	// if(@$_POST['promoAssign'] == '1') //Assing a promotion to location
	// {
	// 	$data = array();
	// 	$errors = array();

	// 	$promoLocation = $_POST['promoLocation'];
	// 	$promoId = $_POST['promoId'];

	// 	$promoLocation = stripslashes($promoLocation);
	// 	$promoId = stripslashes($promoId);
	// 	$promoLocation = mysql_real_escape_string($promoLocation);
	// 	$promoId = mysql_real_escape_string($promoId);

	// 	if(empty($promoId))
	// 	{
	// 		$errors['promo'] = 'Please select a Promotion!';
	// 	}
	// 	if(empty($promoLocation))
	// 	{
	// 		$errors['location'] = 'Please select a Location!';
	// 	}
	// 	if(!empty($errors))
	// 	{
	// 		$data['errors'] = $errors;
	// 		$data['success'] = false;
	// 		echo json_encode($data);
	// 		exit;
	// 	}

	// 	try {
	// 		$stmt = $conn->prepare("Insert Into location_promotion (promotionId, locationId) Values ('$promoId', '$promoLocation')");													
	// 		$stmt->execute();
			
	// 		$data['success'] = true;
	// 		$data['message'] = "Promotion added to store!";
	// 		echo json_encode($data);
	// 	}
	// 	catch(PDOException $e)
	// 	{
	// 	 	$errors['exp'] = 'Error: Assigning Promotion!';
	// 	 	$data['errors'] = $errors;
	// 		$data['success'] = false;
	// 		echo json_encode($data);
	// 		exit;
	// 	}
	// }

	// if(@$_POST['promoRemove']) //Delete a promo
	// {
	// 	$data = array();
	// 	$errors = array();

	// 	$promoLocation = $_POST['promoLocation'];
	// 	$promoId = $_POST['promoId'];
	// 	$promoLocation = stripslashes($promoLocation);
	// 	$promoId = stripslashes($promoId);
	// 	$promoLocation = mysql_real_escape_string($promoLocation);
	// 	$promoId = mysql_real_escape_string($promoId);
		
	// 	if(empty($promoId))
	// 	{
	// 		$errors['promo'] = 'Please select a Promotion!';
	// 	}
	// 	if(empty($promoLocation))
	// 	{
	// 		$errors['location'] = 'Please select a Location!';
	// 	}
	// 	if(!empty($errors))
	// 	{
	// 		$data['errors'] = $errors;
	// 		$data['success'] = false;
	// 		echo json_encode($data);
	// 		exit;
	// 	}
	// 	try {
	// 		$stmt = $conn->prepare("Update location_promotion Set active='0' Where promotionId='$promoId' And locationId='$promoLocation'");													
	// 		$stmt->execute();
			
	// 		$data['success'] = true;
	// 		$data['message'] = "Promotion removed from store!";
	// 		echo json_encode($data);
	// 	}
	// 	catch(PDOException $e)
	// 	{
	// 	 	$errors['exp'] = 'Error: Removing Promotion!';
	// 	 	$data['errors'] = $errors;
	// 		$data['success'] = false;
	// 		echo json_encode($data);
	// 		exit;
	// 	}
	// }
?>