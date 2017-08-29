<?php 

$con = mysqli_connect('localhost','root','','customer_service_experts');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}

$locationId ="";
$pettyCashIn="";

$endId=array();
$endQty=array();
$count1=0;

if (isset($_SESSION['eId'])) {
    # code...
   

		$empId= $_SESSION['eId'];
		$sql2= "SELECT locationId FROM location_user where empId='".$empId."' and active ='1'";
		$result2 = mysqli_query($con,$sql2);

		while($row = mysqli_fetch_array($result2)){
		$locationId = $row["locationId"];
		}

		$sql="SELECT endingProQty,productId  FROM `location_product` where productLocation='$locationId'";
		$result = mysqli_query($con,$sql);

		while($row1 = mysqli_fetch_array($result)){

			 $endQty[]=$row1["endingProQty"];
			 $endId[]=$row1["productId"];
			 $count1++;
		}	

	 	$sql35="SELECT pettyCashOut FROM `shift_end` WHERE locationId='$locationId'  ORDER BY `dateModified` DESC LIMIT 1"; 

       $result6 = mysqli_query($con,$sql35);

       while($row14 = mysqli_fetch_array($result6)){
                
                $pettyCashOut = $row14["pettyCashOut"];
                }

                if ($pettyCashOut==Null) {
                	 $pettyCashOut=0;
                	 json_encode($pettyCashOut);
                }else{
                	json_encode($pettyCashOut);
                }


                $sql36="SELECT pettyCashIn FROM `shift_start` WHERE locationId='$locationId'  ORDER BY `startTime` DESC LIMIT 1"; 

       $result89 = mysqli_query($con,$sql36);

       while($row146 = mysqli_fetch_array($result89)){
                
                $pettyCashIn = $row146["pettyCashIn"];
                }

                if ($pettyCashIn==Null) {
                	 $pettyCashIn=0;
                	 json_encode($pettyCashIn);
                }else{
                	json_encode($pettyCashIn);
                }


	json_encode($endId);
    json_encode($endQty);
    json_encode($count1);


}
?>