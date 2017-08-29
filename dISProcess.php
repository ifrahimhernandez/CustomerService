<?php
	include 'connection.php';
	Session_start();

	if(@$_POST['inputNum'])
	{
		$data = array();
		$dIRN = $_POST['inputNum'];
		$dIRN = stripslashes($dIRN);
		$dIRN = mysql_real_escape_string($dIRN);

		$data['success'] = true;
		try 
		{
			$stmt = $conn->prepare("Select dp.productQty, p.productId, p.productName From product p, delivery_product dp Where dp.deliveryNumRef = '$dIRN' AND dp.productId = p.productId");																						
			$stmt->execute();
			$result = $stmt->fetchAll();

			if(!$result)
			{
				echo '<div>Sorry no match found!</div>';
			}
			else
			{
				echo '<p>Products recieved:</p> <p>Enter the number of damaged items of each product and a short note</p>';
		    	foreach($result as $row) 
			  	{
			  		echo '<div><input type="number" id="'.$row['productId'].'" min="0" max="'.$row['productQty'].'" class="form-control addEmpFields" style="width: 80%;" placeholder="'.$row['productName'].' damaged"><label> x '.$row['productQty'].'</label> <textarea id="'.$row['productId'].'" class="form-control addEmpFields" style="width: 80%;" rows="1" placeholder="'.$row['productName'].' note"></textarea><br></div>';
			   	}
			   	echo '<input type="button" class="btn btn-default pull-right addEmpBtn" style="margin-right:20%;" id="dISSubmit" value="input">';
		   	}
		}
		catch(PDOException $e)
		{
			$data['success'] = false;
			echo json_encode($data);
			exit;
		}
	}

	if(@$_POST['ids']) //Save delivery data
	{
		$data = array();
		$deliveredIds = $_POST['ids'];
		$deliveredNote = $_POST['not'];
		$delRefNum = $_POST['dnr'];
		$substract = 0;
		$location = "";

		$deliveredIds = stripslashes($deliveredIds);
		$deliveredNote = stripslashes($deliveredNote);
		$delRefNum = stripslashes($delRefNum);

		$deliveredIds = mysql_real_escape_string($deliveredIds);
		$deliveredNote = mysql_real_escape_string($deliveredNote);
		$delRefNum = mysql_real_escape_string($delRefNum);

		try 
		{
			$stmt = $conn->prepare("Update delivery Set empId='".$_SESSION['eId']."' Where deliveryNumRef='$delRefNum'");																					
			$stmt->execute(); //Register who recieve the inventory

			foreach($deliveredIds as $key => $value) //The quantity
			{
				$stmt = $conn->prepare("Update delivery_product Set defect='$value' Where deliveryNumRef='$delRefNum' AND productId='$key'");																					
				$stmt->execute();

				$stmt = $conn->prepare("Select dp.productQty, d.locationId From delivery d, delivery_product dp Where dp.deliveryNumRef='$delRefNum' AND dp.productId='$key' AND d.deliveryNumRef='$delRefNum'");																					
				$stmt->execute();
				$result = $stmt->fetchAll();

				foreach($result as $row)
				{
					$location = $row['locationId'];
					$substract = $row['productQty'] - $value;
				}
				$stmt = $conn->prepare("Update location_product Set productQty = productQty + '$substract' Where productLocation='$location' AND productId='$key'");																					
				$stmt->execute();

			}
			foreach($deliveredNote as $key => $value) //The note
			{
				$stmt = $conn->prepare("Update delivery_product Set note='$value' Where deliveryNumRef='$delRefNum' AND productId='$key'");																						
				$stmt->execute();
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

?>