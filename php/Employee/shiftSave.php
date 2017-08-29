			<?php 
			include ('connection.php');
			session_start();
			$data=array();
			
			$location="";

			$con = mysqli_connect('localhost','root','','customer_service_experts');
			if (!$con) {
			    die('Could not connect: ' . mysqli_error($con));
			}

			if (isset($_SESSION['location'])) {
					
					$location=$_SESSION['location'];

			}

			if (isset($_SESSION['eId'])) {
			
					$empId=$_SESSION['eId'];
			}


				date_default_timezone_set("America/Puerto_Rico");

				$tiempo=date('Y-m-d ');

			if(@$_POST['pLocal']) {


					$proQty = $_POST['pLocal'];
					$proid = $_POST['proId'];
					$shiftStart = $_POST['shiftStart'];
					
					
					$sql = "UPDATE location_product SET productQty='".$proQty."' WHERE productId='".$proid."' and productLocation='".$location."'";

					$stmt = $conn->prepare($sql);
					$stmt->execute();
					
					$sql2 = "INSERT INTO `customer_service_experts`.`employee_product` ( `empId`, `proId`, `proQty`, `locationId`, `proInOut`,`dateModificated`,`NumberRecords`) VALUES ( '$empId', '$proid', '$proQty', '$location', '0','$tiempo','$shiftStart')";

					$stmt = $conn->prepare($sql2);
					$stmt->execute();

					echo json_encode("Shift Started.");
			
																					
			}

				if (@$_POST['pettyCash']) {
					
					$pettyCashIn=$_POST['pettyCash'];
					$comment= $_POST['comment'];
					$shiftStart = $_POST['shiftStart'];
					

					$sql = "INSERT INTO `customer_service_experts`.`shift_start` ( `empId`,  `pettyCashIn`, `note`,`locationId`,`NumberRecords`,`startTime`) VALUES ( '$empId',   '$pettyCashIn', '$comment', '$location','$shiftStart','$tiempo')";

					$stmt = $conn->prepare($sql);
     			    $stmt->execute();
					

     			    $sql2="UPDATE `customer_service_experts`.`location` SET `inOrOut` = '1' WHERE `location`.`locationId` = '$location'";
					$stmt = $conn->prepare($sql2);
     			    $stmt->execute();     

     			    echo json_encode("Shift Started.");			    

			}

			?>