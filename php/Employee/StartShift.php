<?php
              $code1 ="";
              $exeption="";
              $s="";
              //session_start(); 
              unset($arrId);
            unset($arrQty);

              $arrId=array();
              $arrQty=array();
              $con = mysqli_connect('localhost','root','','customer_service_experts');
              if (!$con) {
              die('Could not connect: ' . mysqli_error($con));
              }

              $empId= $_SESSION['eId'];
               $sql2= "SELECT locationId FROM location_user where empId='".$empId."' and active ='1'";
              $result2 = mysqli_query($con,$sql2);

            while($row = mysqli_fetch_array($result2)){
                $code1 = $row["locationId"];
                }

            mysqli_select_db($con,"customer_service_experts");
            $sql="SELECT * FROM location_product where productLocation=$code1";
            $result = mysqli_query($con,$sql);
    
    $i=0;
    while($row = mysqli_fetch_array($result)) {

            
            $arrQty[]=$row['productQty'];
            $arrId[]=$row['productId'];
            $sql1="SELECT productName FROM product where productId='".$row['productId'] ."' limit 1 ";
            $result1 = mysqli_query($con,$sql1);
            
            if($result1!=false){
                while($row1 = mysqli_fetch_array($result1)){
                $code = $row1["productName"];
                 echo " <div class='input-group'>";
                 echo "<span class='input-group-addon' style='width:50%; white-space:normal;' id='basic-addon1' >".$code."</span>";
                }
               $idpro="";
               $idpro=$row["productId"];
                 echo "<input type='number' required min='0' data-bind='value:replyNumber' class='form-control' name='number". $idpro ."' aria-describedby='basic-addon1' id='number". $idpro ."' >";
                  echo "</div>";
                 }else {
                  $s="1";
                    $exeption="There are no Products for in this location!";
                         }
                        
         $i++;                
    }
        $_SESSION['ArrayCount']=$i; //counter
        $_SESSION['ArrayId']=$arrId; // ID Product y Qty
        $_SESSION['ArrayQty']=$arrQty;
        $_SESSION['location']=$code1;
    json_encode($arrId );
    json_encode($arrQty );
    json_encode($i );
    if ($s=="1") {
        echo $exeption;
    }
?>