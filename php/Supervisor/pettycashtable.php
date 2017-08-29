<table class="table table-bordered table-hover">
	
  <thead>
	  <tr>
	    <th></th>
	    <th>Starting </th>
	    <th>Ending </th>
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
$res = mysqli_query($con,"SELECT * FROM `shift_start` where   startTime='$date' and locationId='$empLocation' and NumberRecords='$shift' and readyOrNot='1' ORDER BY `id` DESC"); //Por el tiempo buscarlo

$shift_endPettycash="";
$shift_startPettycash="";

  $w="SELECT * FROM `shift_end` where   dateModified='$date' and locationId='$empLocation' and NumberRecords='$shift' and readyOrNot='1' ORDER BY `bankBag` DESC"; //Por el tiempo buscarlo
              $result18 = mysqli_query($con,$w);
            
                while($row18 = mysqli_fetch_array($result18)){
                  $shift_endPettycash= $row18["pettyCashOut"];
                  
                }

while ($row = mysqli_fetch_array($res)) {

           
              $shift_startPettycash=$row['pettyCashIn'];   

?>
    
	  <tr>
	    <td style="width: 25%">Petty Cash</td>
	    <td style="width: 25%"><?php echo  $shift_startPettycash; ?></td>
      <td style="width: 25%"><?php  echo $shift_endPettycash; ?></td>
      <td style="width: 25%">
	    <a class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModal<?php echo $row['id']; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
	    </td>

<!-- Modal -->
<div class="modal fade" id="myModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel<?php echo $row['id']; ?>">Edit Data</h4>
      </div>
      <div class="modal-body">

<form>
  
  <div class="form-group">
    <label for="gd">Starting Qty</label>
        <input type='number' required min='0' step="0.01" data-bind='value:replyNumber' class='form-control'  aria-describedby='basic-addon1' id="stQ<?php echo $row['id']; ?>" value="<?php echo $shift_startPettycash; ?>" >
  </div>
  <div class="form-group">
    <label for="pn">Ending Qty</label>
             <input type='number' required min='0' step="0.01" data-bind='value:replyNumber' class='form-control'  aria-describedby='basic-addon1' id="enQ<?php echo $row['id']; ?>" value="<?php echo $shift_endPettycash; ?>" >
   <input type="hidden" id="date" value= "<?php echo $_SESSION['date']; ?>"> 
  </div>
  
</form>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="npi"  onclick="updatedatapettycash('<?php echo $row['id']; ?>')" class="btn btn-primary" data-dismiss="modal">Save changes</button>
      </div>
    </div>
  </div>
</div>
	    
	    </td>
	  </tr>
<?php


}

}
?>
	</tbody>
      </table>