<table class="table table-bordered table-hover" >
	
  <thead>
	  <tr>
	    <th></th>
	    <th>Amount </th>
	    <th>Action</th>
	  </tr>
	</thead>
	<tbody>
<?php
session_start();
$con = mysqli_connect('localhost','root','','customer_service_experts');
              if (!$con) {
              die('Could not connect: ' . mysqli_error($con));
              }

if (isset($_GET["shift"])) {
               $_SESSION['shift']=$_GET["shift"];
              }              

if (isset($_GET["date"])) {
  $date=$_GET["date"];
  $_SESSION['date']=$date;
  $shift=$_SESSION['shift'];
$empLocation=$_SESSION['empLocation'];

include "config.php";
$w = mysqli_query($con,"SELECT * FROM `bankdrop` where time_executed= '$date' and CompleteDeposit='1' ORDER BY `id` DESC LIMIT 1"); //Por el tiempo buscarlo

  $bankdrop="";  
          
                while($row18 = mysqli_fetch_array($w)){
                  $bankdrop= $row18["bankDrop"];
                  
                }

                if ($bankdrop==null) {
                  # code...
                  $bankdrop=0;

                }




$Expences="";
$BankDeposit="";
$creditSales="";


  $res="SELECT * FROM `shift_end` where   dateModified='$date' and locationId='$empLocation' and NumberRecords='$shift' and readyOrNot='1' ORDER BY `bankBag` DESC"; //Por el tiempo buscarlo
          $result18 = mysqli_query($con,$res);    

while ($row = mysqli_fetch_array($result18)) {

           
              $Expences=$row['expenses'];
              $BankDeposit=$row['bankDeposit'];
              $creditSales=$row['creditSales'];               
              

            

?>
    
	 
<tr>
      <td style="width: 50%">Expences</td>
      <td style="width: 25%"><?php echo  $Expences; ?></td>
      <td style="width: 25%">
      <a class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModal<?php echo $row['bankBag']; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
      </td>

<!-- Modal -->
<div class="modal fade" id="myModal<?php echo $row['bankBag']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?php echo $row['bankBag']; ?>" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel<?php echo $row['bankBag']; ?>">Edit Data</h4>
      </div>
      <div class="modal-body">

<form>
  
  
  <div class="form-group">
    <label for="pn">Amount</label>

    <input type='number' required min='0' step='0.01' data-bind='value:replyNumber' class='form-control'  aria-describedby='basic-addon1' id="Expences" value="<?php echo $Expences; ?>" >

   <input type="hidden" id="date" value= "<?php echo $_SESSION['date']; ?>"> 
  </div>
  
</form>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="npi"  onclick="updateother('<?php echo $row['bankBag']; ?>')" class="btn btn-primary" data-dismiss="modal">Save changes</button>
      </div>
    </div>
  </div>
</div>
      
      </td>
    </tr>





<tr>
      <td style="width: 50%">Bank Deposit</td>
      <td style="width: 25%"><?php echo  $BankDeposit; ?></td>
      <td style="width: 25%">
      <a class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModal<?php echo $row['bankBag']; ?>bank"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
      </td>

<!-- Modal -->
<div class="modal fade" id="myModal<?php echo $row['bankBag']; ?>bank" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?php echo $row['bankBag']; ?>bank" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel<?php echo $row['bankBag']; ?>bank">Edit Data</h4>
      </div>
      <div class="modal-body">

<form>
  
  
  <div class="form-group">
    <label for="pn">Amount</label>
   
       <input type='number' required min='0' step='0.01' data-bind='value:replyNumber' class='form-control'  aria-describedby='basic-addon1' id="BankDeposit" value="<?php echo $BankDeposit; ?>">
 
   <input type="hidden" id="date" value= "<?php echo $_SESSION['date']; ?>"> 
  </div>
  
</form>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="npi"  onclick="updateother('<?php echo $row['bankBag']; ?>')" class="btn btn-primary" data-dismiss="modal">Save changes</button>
      </div>
    </div>
  </div>
</div>
      
      </td>
    </tr>



<tr>
      <td style="width: 50%">Credit Sales</td>
      <td style="width: 25%"><?php echo  $creditSales; ?></td>
      <td style="width: 25%">
      <a class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModal<?php echo $row['bankBag']; ?>Credit"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
      </td>

<!-- Modal -->
<div class="modal fade" id="myModal<?php echo $row['bankBag']; ?>Credit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?php echo $row['bankBag']; ?>Credit" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel<?php echo $row['bankBag']; ?>Credit">Edit Data</h4>
      </div>
      <div class="modal-body">

<form>
  
  
  <div class="form-group">
    <label for="pn">Amount</label>
       <input type='number' required min='0' step='0.01' data-bind='value:replyNumber' class='form-control'  aria-describedby='basic-addon1' id="CreditSales" value="<?php echo $creditSales; ?>">
    
   <input type="hidden" id="date" value= "<?php echo $_SESSION['date']; ?>"> 
  </div>
  
</form>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="npi"  onclick="updateother('<?php echo $row['bankBag']; ?>')" class="btn btn-primary" data-dismiss="modal">Save changes</button>
      </div>
    </div>
  </div>
</div>
      
      </td>
    </tr>



<tr>
      <td style="width: 50%">Drop</td>
      <td style="width: 25%"><?php echo  $bankdrop; ?></td>
      <td style="width: 25%">
      <a class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModal<?php echo $row['bankBag']; ?>drop"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
      </td>

<!-- Modal -->
<div class="modal fade" id="myModal<?php echo $row['bankBag']; ?>drop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?php echo $row['bankBag']; ?>drop" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel<?php echo $row['bankBag']; ?>drop">Edit Data</h4>
      </div>
      <div class="modal-body">

<form>
  
  
  <div class="form-group">
    <label for="pn">Amount</label>

        <input type='number' required min='0' step='0.01' data-bind='value:replyNumber' class='form-control'  aria-describedby='basic-addon1' id="Drop" value="<?php echo $bankdrop; ?>">
   
   <input type="hidden" id="date" value= "<?php echo $_SESSION['date']; ?>"> 
  </div>
  
</form>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="npi"  onclick="updateother('<?php echo $row['bankBag']; ?>')" class="btn btn-primary" data-dismiss="modal">Save changes</button>
      </div>
    </div>
  </div>
</div>
      
      </td>
    </tr>
    
<?php


}
// if (!$row) {
//     printf("Error: %s\n", mysqli_error($con));
//     exit();
// }

}
?>
	</tbody>
      </table>