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




 $employeId="";  
 $empLocation="";
 $shift_number=""; 
if (isset($_SESSION['eId'] )) {
  $employeId=$_SESSION['eId'];
  $empLocation=$_SESSION['empLocation'];
  $shift_number=$_SESSION['shift'];
  
}

if(isset($_GET['id'])){



$stQ = $_POST['stQ'];
$enQ = $_POST['enQ'];
$id = $_GET['id'];
$t=false;

$stmt3 = $conn->prepare("INSERT INTO `customer_service_experts`.`supervisor _changes_pettycash` ( `empId`, `locationId`, `shift_number`,  `petty_in`, `petty_out`) VALUES ( '$employeId', '$empLocation', '$shift_number', '$stQ', '$enQ');");
$stmt = $conn->prepare("UPDATE `customer_service_experts`.`shift_start` SET `pettyCashIn` = '$stQ' WHERE `id` = '$id'");
$stmt2 = $conn->prepare("UPDATE `customer_service_experts`.`shift_end` SET `pettyCashOut` = '$enQ' WHERE `shiftStartId` = '$id'");
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