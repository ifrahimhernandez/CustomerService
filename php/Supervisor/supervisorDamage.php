<?php
session_start();
 
 $servername = "localhost";
$dbUsername = "root";
$dbPassword = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=customer_service_experts", $dbUsername, $dbPassword);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully"; 
    }
catch(PDOException $e)
    {
    //echo "Connection failed: " . $e->getMessage();
    }

 $empLocation="";
 
if (isset($_SESSION['empLocation'] )) {
  $employeId=$_SESSION['eId'];
  $empLocation=$_SESSION['empLocation'];
 
  
}

if(isset($_GET['quantity'])){

$quantity=$_GET['quantity'];
$productsId=$_GET['productsId'];
$comment=$_GET['comment'];
$total=0;

			  $con = mysqli_connect('localhost','root','','customer_service_experts');
              if (!$con) {
              die('Could not connect: ' . mysqli_error($con));
              }

             $sql="SELECT * FROM `location_product` where productId='$productsId' and productLocation='$empLocation'"; 

             $result18 = mysqli_query($con,$sql);
            
                while($row18 = mysqli_fetch_array($result18)){

                	$productPrice= $row18["productPrice"];
                }

$total=floatval($productPrice)*$quantity;

$stmt3 = $conn->prepare("INSERT INTO `customer_service_experts`.`supervisor_damage_product` ( `empId`, `locationId`, `productId`,  `proQtyDamaged`, `proCost`, `note`) VALUES ( '$employeId', '$empLocation', '$productsId', '$quantity', '$total', '$comment')"); 

$stmt3->execute();



echo "Damaged Products Summited.";
}


?>