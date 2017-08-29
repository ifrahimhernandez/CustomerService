<?php
session_start();
$con = mysqli_connect('localhost','root','','customer_service_experts');
              if (!$con) {
              die('Could not connect: ' . mysqli_error($con));
              }

$empLocation="";
if (isset($_GET["date"])) {
  $date=$_GET["date"];
  $arrNumberRecords=array();
  $empLocation=$_SESSION['empLocation'];
$i2=0;
$sql="SELECT * FROM `employee_product` WHERE readyOrNot='1' and locationId='$empLocation' and dateModificated='$date' GROUP BY NumberRecords";

				$result18 = mysqli_query($con,$sql);
            
                while($row18 = mysqli_fetch_array($result18)){
                  $arrNumberRecords[]= $row18["NumberRecords"];
                  $i2++;
                 }
                 

 ?>      <p>Todays Shift</p>
        <select name="TodayShift"  id="group_tag" class="form-control" style="width: 200px">
        <?php
        

        for ($i=0; $i <$i2 ; $i++) { 
          # code...
          echo "<option value='".$arrNumberRecords[$i]."'>Shift #".$arrNumberRecords[$i]."</option>";
        }
        
        ?> 
        </select>
<?php
        

}

?>