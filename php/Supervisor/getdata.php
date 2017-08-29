<table class="table table-bordered table-hover">
	<label>Products Table</label>
  <thead>
	  <tr>
	    <th>Produts</th>
	    <th>Starting #</th>
	    <th>Ending #</th>
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
$res = mysqli_query($con,"SELECT * FROM `employee_product` where   proInOut='0' and dateModificated='$date' and locationId='$empLocation' and NumberRecords='$shift' and readyOrNot='1' ORDER BY `proId` DESC"); //Por el tiempo buscarlo
$code8 ="";
$proQtyend="";
$proQtyend ="";
$arr=array();
$arrMainId1=array();
$arrMainId0=array();
$proIdArr=array();
$i=0;
$y=0;
  $w="SELECT * FROM `employee_product` where  proInOut='1' and dateModificated='$date' and locationId='$empLocation' and NumberRecords='$shift' and readyOrNot='1' ORDER BY `proId` DESC"; //Por el tiempo buscarlo
              $result18 = mysqli_query($con,$w);
            
                while($row18 = mysqli_fetch_array($result18)){
                  $arr[]= $row18["proQty"];
                  $arrMainId1[]=$row18["mainId"];
                  $proIdArr[]=$row18['proId'];
                  $y++;
                }

while ($row = mysqli_fetch_array($res)) {

           
              $productId=$row['proId'];

             // if ($proIdArr[$i]==$productId) {
                # code...
              
              $arrMainId0[]=$row["mainId"];
              $sql17="SELECT productName FROM product where productId='$productId'";
              $result17 = mysqli_query($con,$sql17);
            
                while($row17 = mysqli_fetch_array($result17)){
                  $code8 = $row17["productName"];
                }
?>
    
	  <tr>
	    <td style="width: 25%"><?php echo $code8; ?></td>
	    
	    <td style="width: 25%"><?php echo $row['proQty']; ?></td>
      <td style="width: 25%"><?php  echo $arr[$i]; ?></td>
      <td style="width: 25%">
	    <a class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModal<?php echo $row['mainId']; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
	    </td>

<!-- Modal -->
<div class="modal fade" id="myModal<?php echo $row['mainId']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?php echo $row['proId']; ?>" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel<?php echo $row['mainId']; ?>">Edit Data</h4>
      </div>
      <div class="modal-body">

<form>
  <div class="form-group" style="width: 33%; margin-right:auto; margin-left:auto;">
    <label for="nm">Product Name</label>
    <input  class="form-control" id="nm<?php echo $row['mainId']; ?>" value="<?php echo  $code8; ?>" disabled>
  </div>
  
  <div class="form-group" style="width: 33%; margin-right:auto; margin-left:auto;">
    <label for="gd">Starting #</label>
    <input type='number' required min='0' step='1' data-bind='value:replyNumber' class='form-control' value="<?php echo $row['proQty']; ?>" aria-describedby='basic-addon1' id="stQ<?php echo $row['mainId']; ?>" >
  </div>

  <div class="form-group" style="width: 33%; margin-right:auto; margin-left:auto;">
    <label for="pn">Ending #</label>
    <input type='number' required min='0' step='1' data-bind='value:replyNumber' class='form-control' value="<?php echo $arr[$i]; ?>" aria-describedby='basic-addon1' id="enQ<?php echo $row['mainId']; ?>" >
        
  </div>
  <input type="hidden" id="date" value= "<?php echo $_SESSION['date']; ?>">
  <input type="hidden" id="inside<?php echo $row['mainId']; ?>" value= "<?php echo $row["mainId"]; ?>">
  <input type="hidden" id="outside<?php echo $row['mainId']; ?>" value= "<?php echo $arrMainId1[$i]; ?>">

</form>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="npi" onclick="updatedata('<?php echo $row['mainId']; ?>')" class="btn btn-primary" data-dismiss="modal">Save changes</button>
      </div>
    </div>
  </div>
</div>
	    
	    </td>
	  </tr>
<?php
$i++;
//}
}

}
?>
	</tbody>
      </table>