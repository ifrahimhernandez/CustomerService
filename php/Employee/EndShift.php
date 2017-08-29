<?php 

             $code2 ="";
              $exeption1="";
              $s1="";
              unset($arrId);
            unset($arrQty);
              //session_start(); 
              $arrId1=array();
              $arrQty1=array();
              $con = mysqli_connect('localhost','root','','customer_service_experts');
              if (!$con) {
              die('Could not connect: ' . mysqli_error($con));
              }

              $empId1= $_SESSION['eId'];
               $sql23= "SELECT locationId FROM location_user where empId='".$empId1."' and active ='1'";
              $result21 = mysqli_query($con,$sql23);

            while($row1 = mysqli_fetch_array($result21)){
                $code2 = $row1["locationId"];
                }

            mysqli_select_db($con,"customer_service_experts");
            $sql8="SELECT * FROM location_product where productLocation=$code2";
            $result8 = mysqli_query($con,$sql8);
    
    $i3=0;
    while($row8 = mysqli_fetch_array($result8)) {

            
            $arrQty1[]=$row8['productQty'];
            $arrId1[]=$row8['productId'];
            $sql17="SELECT productName FROM product where productId='".$row8['productId'] ."' limit 1 ";
            $result17 = mysqli_query($con,$sql17);
            
            if($result17!=false){
                while($row17 = mysqli_fetch_array($result17)){
                $code8 = $row17["productName"];
                 echo " <div class='input-group'>";
                 echo "<span class='input-group-addon' style='width:50%; white-space:normal;' id='basic-addon1' >".$code8."</span>";
                }
               $idpro2="";
               $idpro2=$row8["productId"];
                 echo "<input type='number' required min='0' step='1' data-bind='value:replyNumber' class='form-control' name=number".$idpro2." aria-describedby='basic-addon1' id='number". $idpro2 ."' >";
                  echo "</div>";
                 }else {
                  $s1="1";
                    $exeption1="There are no Products for in this location!";
                         }
                         
         $i3++;                
    }
        $_SESSION['ArrayCount']=$i3; //counter
        $_SESSION['ArrayId']=$arrId1; // ID Product y Qty
        $_SESSION['ArrayQty']=$arrQty1;
        $_SESSION['location']=$code2;
        json_encode($arrId1 );
        json_encode($arrQty1 );
        json_encode($i3 );
        if ($s1=="1") {
            echo $exeption1;
        }

            ?>