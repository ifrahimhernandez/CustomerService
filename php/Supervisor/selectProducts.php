<?php
session_start();
$con = mysqli_connect('localhost','root','','customer_service_experts');
              if (!$con) {
              die('Could not connect: ' . mysqli_error($con));
              }

$empLocation="";
if (isset($_GET["date"])) {
  $date=$_GET["date"];
  $shift_number=@$_SESSION['shift'];
  $arrproId=array();
  $arrproName=array();
  $empLocation=$_SESSION['empLocation'];
$i2=0;
$sql="SELECT * FROM `employee_product` WHERE readyOrNot='1' and proInOut='0' and locationId='$empLocation' and dateModificated='$date' GROUP BY proId";

				$result18 = mysqli_query($con,$sql);
            
                while($row18 = mysqli_fetch_array($result18)){
                  $arrproId[]= $row18["proId"];
                  $productId= $row18["proId"];
                      $sql17="SELECT productName FROM product where productId='$productId'";
                      $result17 = mysqli_query($con,$sql17);

                      while($row17 = mysqli_fetch_array($result17)){
                      $arrproName[] = $row17["productName"];
                      }

                  $i2++;
                 }

                 

 ?>   
       <p>Damaged Products</p>
        <select name="Products"  id="group_tag" class="form-control" style="width: 200px">
        <?php
        

        for ($i=0; $i <$i2 ; $i++) { 
          # code...
          echo "<option value='".$arrproId[$i]."'>".$arrproName[$i]."</option>";
        }
        
        ?> 
        </select>
        <br>

         <div class="form-group">
            <label>Quantity</label>
            <input type='number' required min='0' step='1' data-bind='value:replyNumber' class='form-control'  aria-describedby='basic-addon1' id="quantity" style="width: 200px" required>
        </div>
        
        <br>

        <div class="form-group">
      <label for="comment">Note:</label>
      <textarea class="form-control" rows="5" id="comment" ></textarea>
        </div>

       
<?php
        

}

?>