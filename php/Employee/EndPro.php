<?php

$con = mysqli_connect('localhost','root','','customer_service_experts');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}


              $arrId1=array();
              $arrQty1=array();
              $i1=0;
            $sql="SELECT * FROM location_product where productLocation=$code1";
            $result = mysqli_query($con,$sql);

            while($row = mysqli_fetch_array($result)){
                
                $arrQty1[] = $row["productQty"];
                $arrId1[]= $row["productId"];
                $i1++;
                }
           
              json_encode($arrId1 );
              json_encode($arrQty1 );
              json_encode($i1 );

      
?>
