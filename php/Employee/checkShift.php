<?php
    
//session_start();
$code1 =""; 
$optionShift="";
$inOrOut ="";
$bankBag="";
$pettyCashOut="";
$NumberRecords="";
$shiftNumberStart=0;
$shiftNumberEnd=0;
$NumberRecordsEnd ="";

$con = mysqli_connect('localhost','root','','customer_service_experts');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}
date_default_timezone_set("America/Puerto_Rico");

        $tiempo=date('Y-m-d ');


        if (isset($_SESSION['eId'])) {
    # code...
   

    $empId= $_SESSION['eId'];
    $sql2= "SELECT locationId FROM location_user where empId='".$empId."' and active ='1'";
    $result2 = mysqli_query($con,$sql2);

    while($row = mysqli_fetch_array($result2)){
    $location = $row["locationId"];
    }
$_SESSION['empLocation']=$location;

  }

      

$con = mysqli_connect('localhost','root','','customer_service_experts');
if (!$con) {
    die('Could not connect: ' . mysqli_error($con));
}


if (isset($_SESSION['eId'])) {
    # code...
   

		$empId= $_SESSION['eId'];
		$sql2= "SELECT locationId FROM location_user where empId='".$empId."' and active ='1'";
		$result2 = mysqli_query($con,$sql2);

		while($row = mysqli_fetch_array($result2)){
		$code1 = $row["locationId"];
		}


     $sql= "SELECT `inOrOut` FROM `location` where locationId='$code1' ";
         
        $result = mysqli_query($con,$sql);

            while($row1 = mysqli_fetch_array($result)){
                
                $inOrOut = $row1["inOrOut"];
                }

                json_encode($inOrOut );


      $sql5="SELECT bankBag FROM `shift_end` ORDER BY `shift_end`.`bankBag` DESC LIMIT 1" ;

      $result5 = mysqli_query($con,$sql5);

            while($row13 = mysqli_fetch_array($result5)){
                
                $bankBag = $row13["bankBag"];
                }
                $bankBag=$bankBag+1;


      $s="SELECT * from employee_product where dateModificated='$tiempo' and locationId='$location' and proInOut='0' ORDER BY `employee_product`.`NumberRecords` DESC LIMIT 1";

          $result28 = mysqli_query($con,$s); //start shift
          
                while($row28 = mysqli_fetch_array($result28)){
                    $NumberRecords = $row28["NumberRecords"];
                    
                    }

                    if($NumberRecords ==null){
                      
                      $shiftNumberStart=1;
                     
                    }else{
                      

                      $shiftNumberStart=intval($NumberRecords)+1;
                      
                    }
                  
                  json_encode($shiftNumberStart );
  $s1="SELECT * from employee_product where dateModificated='$tiempo' and locationId='$location' and proInOut='1' ORDER BY `employee_product`.`NumberRecords` DESC LIMIT 1";

          $result281 = mysqli_query($con,$s1); //finish shift
          
                while($row281 = mysqli_fetch_array($result281)){
                    $NumberRecordsEnd = $row281["NumberRecords"];
                    
                    }

                    if($NumberRecordsEnd ==null){
                      
                      $shiftNumberEnd=1;
                       
                    }else{
                      

                      $shiftNumberEnd=intval($NumberRecordsEnd)+1;
                     
                    }

               json_encode($shiftNumberEnd );
}

        
       




mysqli_close($con);
?>