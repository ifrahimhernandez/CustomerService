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

$date = $_POST['date'];
$Drop = $_POST['Drop'];
$CreditSales = $_POST['CreditSales'];
$BankDeposit = $_POST['BankDeposit'];
$Expences = $_POST['Expences'];
$id = $_GET['id'];
$t=false;


$stmt3 = $conn->prepare("INSERT INTO `customer_service_experts`.`supervisor_changes_other` ( `empId`, `locationId`, `shift_number`,  `expences`, `bank_deposit`, `credit_sales`, `bank_drop`) VALUES ( '$employeId', '$empLocation', '$shift_number', '$Expences',  '$BankDeposit', '$CreditSales', '$Drop');");
$stmt = $conn->prepare("UPDATE `customer_service_experts`.`bankdrop` SET `bankDrop` = '$Drop' WHERE `time_executed` = '$date' and CompleteDeposit='1' ORDER BY `id` DESC LIMIT 1");
$stmt2 = $conn->prepare("UPDATE `customer_service_experts`.`shift_end` SET `creditSales` = '$CreditSales', `bankDeposit` = '$BankDeposit', `expenses` = '$Expences' WHERE `bankBag` = '$id'");
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