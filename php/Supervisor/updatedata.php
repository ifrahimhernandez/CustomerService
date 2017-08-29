<?php
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
session_start();
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

$con = mysqli_connect('localhost','root','','customer_service_experts');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

 $employeId="";  
 $empLocation="";
 $shift_number=""; 
if (isset($_SESSION['eId'] )) {
  $employeId=$_SESSION['eId'];
  $empLocation=$_SESSION['empLocation'];
  $shift_number=$_SESSION['shift'];
  
}


if(isset($_GET['id'])){


$nm = $_POST['nm'];
$stQ = $_POST['stQ'];
$enQ = $_POST['enQ'];
$date = $_POST['date'];
$starId = $_POST['starId'];
$endingId = $_POST['endingId'];
$id = $_GET['id'];
$t=false;
$code8 ="";


$sql17="SELECT productId FROM product where productName='$nm'";
              $result17 = mysqli_query($con,$sql17);
            
                while($row17 = mysqli_fetch_array($result17)){
                  $code8 = $row17["productId"];
                }

$stmt3 = $conn->prepare("INSERT INTO `customer_service_experts`.`supervisor_changes_products` ( `empId`, `locationId`, `shift_number`,  `productId`, `product_name`, `pro_starting`, `pro_ending`) VALUES ( '$employeId', '$empLocation', '$shift_number', '$code8','$nm', '$stQ', '$enQ')");

$stmt = $conn->prepare("UPDATE `customer_service_experts`.`employee_product` SET `proQty` = '$stQ' WHERE `mainId` = '$starId'");
$stmt2 = $conn->prepare("UPDATE `customer_service_experts`.`employee_product` SET `proQty` = '$enQ' WHERE `mainId` = '$endingId'");
//put location 

if ($stmt->execute() && $stmt2->execute() && $stmt3->execute()) {
  $t=true;
}
if($t==true){
?>
<div class="alert alert-success alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Success!</strong> 
</div>
<?php
} else{
?>
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Error!</strong> 
</div>
<?php
}
}else{
?> 
<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Warning!</strong> 
</div>
<?php
}
?>