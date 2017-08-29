<?php 
			include "connection.php";
			session_start();
			$data=array();
			$location="";

			if (isset($_SESSION['location'])) {
				$location=$_SESSION['location'];

			}

			if (isset($_SESSION['eId'])) {
				
				$empId=$_SESSION['eId'];
			}

			 date_default_timezone_set("America/Puerto_Rico");
              
             	 $tiempo=date('Y-m-d ');

		if (@$_POST['valorProE']) {
														//Ending Shift Products
				$valorProE=$_POST['valorProE'];
				$idProE=$_POST['proIdE'];
				$EndShiftCount=$_POST['EndShiftCount'];
					

				


			$sql = "UPDATE location_product SET endingProQty='".$valorProE."' WHERE productId='".$idProE."' and productLocation='".$location."' and active='1'";

			$stmt = $conn->prepare($sql);
			$stmt->execute();
			
			$sql2 = "INSERT INTO `customer_service_experts`.`employee_product` ( `readyOrNot`,`empId`, `proId`, `proQty`, `locationId`, `proInOut`,`dateModificated`,`NumberRecords`) VALUES ( '1','$empId', '$idProE', '$valorProE', '$location', '1','$tiempo','$EndShiftCount')";

			$stmt = $conn->prepare($sql2);
			$stmt->execute();

			$sql30 = "UPDATE `customer_service_experts`.`employee_product` SET `readyOrNot` = '1' WHERE `employee_product`.`numberRecords` = '$EndShiftCount' and `readyOrNot` = '0'";

			$stmt = $conn->prepare($sql30);
			$stmt->execute();

			}


			if (@$_POST['expenses']) {
														//Ending Shift expences,ect
				$expenses=$_POST['expenses'];
				$pettyCashOut=$_POST['pettyCash'];
				$comment= $_POST['comment'];
				$bankDeposit=$_POST['bankDeposit'];	
				$creditSales=$_POST['creditSales'];	
				$EndShiftCount=$_POST['EndShiftCount'];


			$idshiftstart ="";
			$bankdrop="";

			$con = mysqli_connect('localhost','root','','customer_service_experts');
			if (!$con) {
			    die('Could not connect: ' . mysqli_error($con));
			}

				$que="SELECT `id` FROM `shift_start` where locationId= '$location' ORDER BY `shift_start`.`id` DESC LIMIT 1";



		    $result28 = mysqli_query($con,$que);

            while($row28 = mysqli_fetch_array($result28)){
                $idshiftstart = $row28["id"];
                }


			$sql2 = "SELECT * FROM `bankdrop` where CompleteDeposit='0' and LocationId='$location' ORDER BY `bankdrop`.`CompleteDeposit` ASC LIMIT 1";

		    $result2 = mysqli_query($con,$sql2);

            while($row = mysqli_fetch_array($result2)){
                $bankdrop = $row["bankDrop"];
                }

             if ($bankdrop!=null) {
                	
               
            $deposit=$bankdrop+$bankDeposit;

           

            
							        if ($deposit>=50) {
							        	//Update shift
							        	 
							        
							        	$y=	"UPDATE `customer_service_experts`.`bankdrop` SET `CompleteDeposit` = '1' WHERE CompleteDeposit= '0' and LocationId='$location' ORDER BY id LIMIT 1 ";						

										$stmt1 = $conn->prepare($y);
							        	$stmt1->execute();	


							        	$q="INSERT INTO `customer_service_experts`.`shift_end` (`creditSales`,`readyOrNot`,`empId`, `pettyCashOut`, `bankDeposit`, `expenses`,  `endNote`,`locationId`,`shiftStartId`,`NumberRecords`,`dateModified`) VALUES ('$creditSales','1','$empId', '$pettyCashOut', '$deposit', '$expenses',  '$comment','$location','$idshiftstart','$EndShiftCount','$tiempo')";

							        	$stmt = $conn->prepare($q);
							        	$stmt->execute();
												
										$sql3 = "UPDATE `customer_service_experts`.`shift_start` SET `readyOrNot` = '1' WHERE `shift_start`.`NumberRecords` = '$EndShiftCount' and `readyOrNot` = '0'";

										$stmt = $conn->prepare($sql3);
							        	$stmt->execute();

							        	$sql2="UPDATE `customer_service_experts`.`location` SET `inOrOut` = '0' WHERE `location`.`locationId` = '$location'";
										$stmt = $conn->prepare($sql2);
										    $stmt->execute();   

										    echo json_encode("Shift Ended");

							        } else {
							        	
							        	//insert into drop 
							        	
							        	
							        			$sqle="UPDATE `customer_service_experts`.`bankdrop` SET `bankDrop` = '$deposit' WHERE CompleteDeposit= '0' and LocationId='$location'";
													$stmt3 = $conn->prepare($sqle);
													$stmt3->execute();

										         $k="INSERT INTO `customer_service_experts`.`shift_end` (`creditSales`,`readyOrNot`,`empId`, `pettyCashOut`, `bankDeposit`, `expenses`,  `endNote`,`locationId`,`shiftStartId`,`NumberRecords`,`dateModified`) VALUES ('$creditSales','1','$empId', '$pettyCashOut', '0', '$expenses',  '$comment','$location','$idshiftstart','$EndShiftCount','$tiempo')";


												$stmt2 = $conn->prepare($k);
												$stmt2->execute();

												$sql3 = "UPDATE `customer_service_experts`.`shift_start` SET `readyOrNot` = '1' WHERE `shift_start`.`NumberRecords` = '$EndShiftCount'";

												$stmt = $conn->prepare($sql3);
												$stmt->execute();

												$sql2="UPDATE `customer_service_experts`.`location` SET `inOrOut` = '0' WHERE `location`.`locationId` = '$location'";
												$stmt = $conn->prepare($sql2);
							     			    $stmt->execute(); 
							     			    echo json_encode("Shift Ended");  

							        }
            
				}else{

						if ($bankDeposit<50) {
							
							$sqle="INSERT INTO `customer_service_experts`.`bankdrop` ( `bankDrop`, `CompleteDeposit`, `LocationId`,`time_executed`) VALUES ( '$bankDeposit',  '0', '$location','$tiempo')";
							$stmt3 = $conn->prepare($sqle);
							$stmt3->execute();

							$k="INSERT INTO `customer_service_experts`.`shift_end` (`creditSales`,`readyOrNot`,`empId`, `pettyCashOut`, `expenses`,  `endNote`,`locationId`,`shiftStartId`,`NumberRecords`,`dateModified`) VALUES ('$creditSales','1','$empId', '$pettyCashOut', '$expenses',  '$comment','$location','$idshiftstart','$EndShiftCount','$tiempo')";
							
							$stmt2 = $conn->prepare($k);
							$stmt2->execute();

							$sql3 = "UPDATE `customer_service_experts`.`shift_start` SET `readyOrNot` = '1' WHERE `shift_start`.`NumberRecords` = '$EndShiftCount'";

							$stmt = $conn->prepare($sql3);
							$stmt->execute();

							$sql2="UPDATE `customer_service_experts`.`location` SET `inOrOut` = '0' WHERE `location`.`locationId` = '$location'";
							$stmt = $conn->prepare($sql2);
		     			    $stmt->execute();   
		     			    echo json_encode("Shift Ended");

						} else{

							$q="INSERT INTO `customer_service_experts`.`shift_end` (`creditSales`,`readyOrNot`,`empId`, `pettyCashOut`, `bankDeposit`, `expenses`,  `endNote`,`locationId`,`shiftStartId`,`NumberRecords`,`dateModified`) VALUES ('$creditSales','1','$empId', '$pettyCashOut', '$deposit', '$expenses',  '$comment','$location','$idshiftstart','$EndShiftCount','$tiempo')";

			            	$stmt = $conn->prepare($q);
			            	$stmt->execute();

			            	$sql3 = "UPDATE `customer_service_experts`.`shift_start` SET `readyOrNot` = '1' WHERE `shift_start`.`NumberRecords` = '$EndShiftCount'";

							$stmt = $conn->prepare($sql3);
							$stmt->execute();

			            	$sql2="UPDATE `customer_service_experts`.`location` SET `inOrOut` = '0' WHERE `location`.`locationId` = '$location'";
							$stmt = $conn->prepare($sql2);
		     			    $stmt->execute();  

		     			    echo json_encode("Shift Ended");

						}
						

				}
			

			}
?>