 <!DOCTYPE html>

 <?php 
  session_start(); 
   include 'connection.php';
   include 'login.php';
   include 'php/Employee/checkShift.php';
   include 'php/Employee/endingQty.php';
 ?>

<html lang="en">
  <head>
    <style type="text/css">*{font-weight: bold;}</style>

    <title>TCSE</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <script src="jquery-1.11.3.min.js"></script>
    <link rel="stylesheet" type="text/css" href="bootstrap-3.3.2-dist/css/bootstrap.min.css">
    <script src="bootstrap-3.3.2-dist/js/bootstrap.min.js"></script>
    
    <link rel="stylesheet" type="text/css" href="./mainPage.css">
    <link rel="stylesheet" type="text/css" href="bootstrap-datepicker.css">
    
    <script src="jquery-migrate-1.2.1.min.js"></script>

    
    <script>$(function(){$('.datepicker').datepicker()});</script>

    <script src="eMScript.js"></script>
    <script src="pMScript.js"></script>
    <script src="promoMScript.js"></script>
    <script src="dIScript.js"></script>
    <script src="logInProcess.js"></script>
    <script src="sScript.js"></script>
    <script src="dISScript.js"></script>
    <script src="report.js"></script>

    <script src="jquery-1.10.2.js"></script>
    <script src="jquery-ui.js"></script>
    <script>var inorno= <?php echo json_encode($inOrOut ) ?>;</script>
    <script src="logInProcess.js"></script>
    <script src="js/supervisorTable.js"></script>
    <script src="js/employeeshifs.js"></script>
    <link rel="stylesheet" href="jquery-ui.css">

    <?php
      $arrId=array();
      $arrQty=array();
      $count="";
      if(isset($_SESSION['ArrayId']))
      {
        $arrId=$_SESSION['ArrayId']; // ID Product y Qty
        $arrQty= $_SESSION['ArrayQty'];
        $i=$_SESSION['ArrayCount'];
      }
//ending disable button
    ?>
    <style type="text/css">
    #formpro {display: none;}
    </style>
  </head>
  <body>

    <div class="container-fluid">
      <div class="row" style="border-bottom: 1px solid yellow;">
      <!--Logo of Company-->
        <div class="col-md-2">
        </div>
        <div class="col-md-7">
          <img style="padding-bottom:5px;" class="img-responsive" src="./images/logo.png">
        </div>
        <!--Log in Form-->
        <div class="col-md-3" style="margin-top:10px; height: 40px;">
            <form action="" method="post">
              <input type="password" id="uPassI" class="<?php echo $input?> pull-right" name="password" style="width:48%;" <?php echo @$validation?> placeholder="password">
              <input type="text" id="uNameI" class="<?php echo $input?> pull-right" name="userNInput" style="width:48%;" placeholder="username" <?php echo @$validation?>>
              <h5 style="text-align:right;"><?php echo @$error;?></h5>
              <button name="out" type="submit" class="btn btn-default pull-right" style="margin-left: 10px; <?php echo $notLogged?>;">Log Out</button>
              <button name="in" id="in" type="submit" class="btn btn-default pull-right btn-sm" style="margin-left: 10px; <?php echo @$logged?>;">Log In</button>
              <!-- Trigger the modal with a button -->
              <button type="button" class="btn btn-default pull-right <?php echo @$_SESSION['btnLocDesappear'];?>" <?php echo @$_SESSION['ePickLoc'];?>>Pick a Location</button>
            </form>
        </div>
      </div>
    </div>

    <!--Menu btns-->
    <div id='menuToggle' class="col-md-2" style="padding:0;">
          <ul class="list-group nav nav-pills <?php echo $_SESSION['btnDesappear']?>" style="padding-right:0;">
            <li <?php echo @$_SESSION['sh']?> id="pills" class="<?php echo $_SESSION['privShift']?>"><a data-toggle="<?php echo $_SESSION['sPill']?>" href="#supervisorshift"><span class="glyphicon glyphicon-download-alt"></span><br> Supervisor Shift</a></li>
            <li <?php echo @$_SESSION['sh']?> id="pills" class="<?php echo $_SESSION['privShift']?>"><a data-toggle="<?php echo $_SESSION['sPill']?>" href="#shift"><span class="glyphicon glyphicon-hourglass"></span><br> Shift</a></li>
            <li <?php echo @$_SESSION['sa']?> id="pills" class="<?php echo $_SESSION['privSales']?>"><a id="sales1" data-toggle="<?php echo $_SESSION['slsPill']?>" href="#sales"><span class="glyphicon glyphicon-usd"></span><br> Sales</a></li>
            <li <?php echo @$_SESSION['re']?> id="pills" class="<?php echo $_SESSION['privReport']?>"><a data-toggle="<?php echo $_SESSION['rPill']?>" href="#report"><span class="glyphicon glyphicon-folder-open"></span><br> Report</a></li>
            <li <?php echo @$_SESSION['ma']?> id="pills" class="<?php echo $_SESSION['privManagement']?>"><a data-toggle="<?php echo $_SESSION['mPill']?>" href="#employeeManagement"><span class="glyphicon glyphicon-wrench"></span><br> Management</a></li>
            <li <?php echo @$_SESSION['in']?> id="pills" class="<?php echo $_SESSION['privInventory']?>"><a data-toggle="<?php echo $_SESSION['iPill']?>" href="#inventory"><span class="glyphicon glyphicon-list-alt"></span><br> Inventory</a></li>
            <!-- <li <?php echo @$_SESSION['he']?> id="pills"><a data-toggle="pill" href="#help"><span class="glyphicon glyphicon-question-sign"></span><br> Help</a></li> -->
          </ul>
    </div>

    <!--Panel Content-->
    <div class="col-md-10" style="padding:0;">
      <img class="img-responsive" src="./images/train.jpg" style="position:absolute; z-index:-1; width:100%; height:700px;">
      <div class="tab-content">

     <!--Panel Help-->
        <div id="help" class="tab-pane fade">
           <div class="panel panel-default panel-transparent">
            <div class="panel-heading"><h3>Help</h3></div>
            <div class="panel-body">
              <form role="form">
                <div class="form-group col-md-5">
                </div>
              </form>
           </div>
          </div>
        </div>


          <div id="supervisorshift" class="tab-pane fade">
           <div class="panel panel-default panel-transparent">
            <div class="panel-heading"><h3>Supervisor Shift</h3></div>
            <div class="panel-body">

                <?php
                  if($inOrOut =="0" && $_SESSION['ePrivilege'] == "2" )
                {
                ?>

                <a data-toggle="pill" href="#Ending"><button type="submit" class="btn btn-default" > <?php 
                date_default_timezone_set("America/Puerto_Rico");
              
                echo date('A'); ?></button></a>
                <a id = "1" data-toggle="pill" href="#Starting" ><button type="submit"  class="btn btn-default" id="StartingB"><span class="glyphicon glyphicon-import"></span> Starting</button></a>
                <a data-toggle="pill" href="#Ending"><button type="submit" class="btn btn-default" id="EndingB"><span class="glyphicon glyphicon-export"></span> Ending</button></a>
                <br>
                <br> 
              <style>
                    div .d
                    {
                      overflow:auto;
                    }
              </style>                
               <form action="mainPage.php" method="POST" role="form" id="register" style="height: 210px; overflow-y: scroll;">
                <div class="form-group col-md-5" id ="d">

                  <script>document.getElementById("EndingB").disabled = true;</script>
                  <?php
                      include 'php/Employee/StartShift.php';
                  ?>
                  <script>
                      var counter= <?php echo  json_encode($count1); ?>;
                      var endingQty=<?php echo json_encode($endQty ); ?>;
                      var endingId=<?php echo json_encode($endId ); ?>;
                      var pettyCash=<?php echo json_encode($pettyCashOut ); ?>;

                      var ArrId= <?php echo json_encode($arrId ); ?>;
                      var ArrQty= <?php  echo json_encode($arrQty ); ?>;
                      var counter= <?php echo  json_encode($i ); ?>;
                      var StartShiftCount= <?php echo json_encode($shiftNumberStart ); ?>;
                  </script>
                  <div class="input-group">
                    <span class="input-group-addon" style='width:50%; white-space:normal;' id="basic-addon1">Petty Cash</span>
                    <input type="number" step="0.01" min="0" data-bind='value:replyNumber' class='form-control'  aria-describedby='basic-addon1' id="pettyCash" required>
                  </div>
                </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="comment">Note:</label>
            <textarea class="form-control" rows="5" id="comment" ></textarea>
          </div>
          <button type="button" class="btn btn-default" onclick="Clear()">Clear All</button>
          <button   type="button" class="btn btn-default"   id="Bshift">Save and Start Shift</button>
        </div>
      </form>

          <?php 
            } //Termina Starting
            if($inOrOut =="1" && $_SESSION['ePrivilege'] == "2") //Empieza Ending
             {            
                include 'php/Employee/EndPro.php';
          ?>
                <a data-toggle="pill" href="#Ending"><button type="submit" class="btn btn-default" > <?php 
                date_default_timezone_set("America/Puerto_Rico");
                
                echo date('A'); ?></button></a>
                <a id = "1" data-toggle="pill" href="#Starting" ><button type="submit"  class="btn btn-default" id="StartingB"><span class="glyphicon glyphicon-import"></span> Starting</button></a>
                <a data-toggle="pill" href="#Ending"><button type="submit" class="btn btn-default" id="EndingB"><span class="glyphicon glyphicon-export"></span> Ending</button></a>

                <br>
                <br> 

                <style>
                  div .d2
                  {
                    overflow:auto;
                  }
                </style>                
                <form action="mainPage.php" method="POST" role="form" id="registerEnding">
                  <div class="form-group col-md-5" id ="d2" style="height: 210px; overflow-y: scroll;">
                    <?php 
                      include 'php/Employee/EndShift.php';
                    ?>
                    <script>
                      document.getElementById("StartingB").disabled = true;
                      var ArrId= <?php echo json_encode($arrId1 ); ?>;
                      var ArrQty= <?php  echo json_encode($arrQty1 ); ?>;
                      var counter= <?php echo  json_encode($i1); ?>;
                      var pettyCashIn=<?php echo json_encode($pettyCashIn); ?>;

                      var ArrId= <?php echo json_encode($arrId1 ); ?>;
                      var ArrQty= <?php  echo json_encode($arrQty1 ); ?>;
                      var counter= <?php echo  json_encode($i1 ); ?>;
                      var EndShiftCount= <?php echo json_encode($shiftNumberEnd); ?>;
                    </script>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                    
                    <div class="input-group" style='width:100%;'>
                    <span class="input-group-addon" style='width:50%; white-space:normal;' id="basic-addon1" >Petty Cash</span>
                    <input type="number" step="0.01" min="0" style='width:100%;' data-bind='value:replyNumber' class='form-control'  aria-describedby='basic-addon1' id="pettyCash" required>
                    </div>

                    <div class="input-group" style='width:100%;'>
                    <span class="input-group-addon" style='width:50%; white-space:normal;' id="basic-addon1" >Expenses</span>
                    <input type="number" step="0.01" min="0" style='width:100%;' data-bind='value:replyNumber' class='form-control'  aria-describedby='basic-addon1' id="expenses" required>
                    </div>
                
                    <div class="input-group" style='width:100%;'>
                    <span class="input-group-addon" style='width:50%; white-space:normal;' id="basic-addon1" >Bank Deposit</span>
                   <input type="number" step="0.01" min="0" style='width:100%;' data-bind='value:replyNumber' class='form-control'  aria-describedby='basic-addon1' id="bankDeposit" required>
                    </div>

                    <div class="input-group" style='width:100%;'>
                    <span class="input-group-addon" style='width:50%; white-space:normal;' id="basic-addon1" >Credit Sales</span>
                    <input type="number" step="0.01" min="0" style='width:100%;' data-bind='value:replyNumber' class='form-control'  aria-describedby='basic-addon1' id="creditSales" required>
                    </div>

                    <div class="input-group" style='width:100%;'>
                    <span class="input-group-addon" id="basic-addon1" ><?php echo "Bank Bag #".$bankBag; ?></span>
                    </div>

                     <br> 
                    <label for="comment">Note:</label>
                    <textarea class="form-control" rows="5" id="comment" ></textarea>
                    </div>
                    <button type="button" class="btn btn-default" onclick="ClearEnd()">Clear All</button>
                    <button   type="button" class="btn btn-default"   id="Endingshift">Save and Start Shift</button>
                  </div>
                </form>
              <?php 
                }
              ?>
            </div>
          </div>
        </div>


        <!--Panel Shift-->
        <div id="shift" class="tab-pane fade">
           <div class="panel panel-default panel-transparent">
            <div class="panel-heading"><h3>Shift</h3></div>
            <div class="panel-body">

                <?php
                  if ($_SESSION['ePrivilege'] == "2")  //Supervisor shift
                  {
                ?>                 
                    <div id="info"></div>
                    <br/>
                    <div id="viewdata"></div>
                    <br/>
                    <div id="pettyCashView"></div>
                    <br>
                    <div id="othertable"></div>


                    <form role="form" id="formpro">
                      <div  id="content2" class="well" >
                      <div id="Products" ></div>
                        <button type="button" class="btn btn-default" id="DamageButton" >Submit</button>
                      </div>
                    </form> 

                    <form role="form">
                      <div id='content' class="well" >
                        <p>Select Date: <input type="text" id="datepicker" class="form-control" style="width: 200px"></p>
                        <div id="TodayShift" ></div>
                        <br>
                        <button type="button" class="btn btn-default" id="DateButton" >Submit</button>
                      </div>
                    </form> 
                    
                <?php   
                }
                if($inOrOut =="0" && $_SESSION['ePrivilege'] == "3" )
                {
                ?>

                <a data-toggle="pill" href="#Ending"><button type="submit" class="btn btn-default" > <?php 
                date_default_timezone_set("America/Puerto_Rico");
              
                echo date('A'); ?></button></a>
                <a id = "1" data-toggle="pill" href="#Starting" ><button type="submit"  class="btn btn-default" id="StartingB"><span class="glyphicon glyphicon-import"></span> Starting</button></a>
                <a data-toggle="pill" href="#Ending"><button type="submit" class="btn btn-default" id="EndingB"><span class="glyphicon glyphicon-export"></span> Ending</button></a>
                <br>
                <br> 
              <style>
                    div .d
                    {
                      overflow:auto;
                    }
              </style>                
               <form action="mainPage.php" method="POST" role="form" id="register" style="height: 210px; overflow-y: scroll;">
                <div class="form-group col-md-5" id ="d">

                  <script>document.getElementById("EndingB").disabled = true;</script>
                  <?php
                      include 'php/Employee/StartShift.php';
                  ?>
                  <script>
                      var counter= <?php echo  json_encode($count1); ?>;
                      var endingQty=<?php echo json_encode($endQty ); ?>;
                      var endingId=<?php echo json_encode($endId ); ?>;
                      var pettyCash=<?php echo json_encode($pettyCashOut ); ?>;

                      var ArrId= <?php echo json_encode($arrId ); ?>;
                      var ArrQty= <?php  echo json_encode($arrQty ); ?>;
                      var counter= <?php echo  json_encode($i ); ?>;
                      var StartShiftCount= <?php echo json_encode($shiftNumberStart ); ?>;
                  </script>
                  <div class="input-group">
                    <span class="input-group-addon" style='width:50%; white-space:normal;' id="basic-addon1">Petty Cash</span>
                    <input type="number" step="0.01" min="0" data-bind='value:replyNumber' class='form-control'  aria-describedby='basic-addon1' id="pettyCash" required>
                  </div>
                </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="comment">Note:</label>
            <textarea class="form-control" rows="5" id="comment" ></textarea>
          </div>
          <button type="button" class="btn btn-default" onclick="Clear()">Clear All</button>
          <button   type="button" class="btn btn-default"   id="Bshift">Save and Start Shift</button>
        </div>
      </form>

          <?php 
            } //Termina Starting
             if($inOrOut =="1" && $_SESSION['ePrivilege'] == "3") //Empieza Ending
             {            
                include 'php/Employee/EndPro.php';
          ?>
                <a data-toggle="pill" href="#Ending"><button type="submit" class="btn btn-default" > <?php 
                date_default_timezone_set("America/Puerto_Rico");
                
                echo date('A'); ?></button></a>
                <a id = "1" data-toggle="pill" href="#Starting" ><button type="submit"  class="btn btn-default" id="StartingB"><span class="glyphicon glyphicon-import"></span> Starting</button></a>
                <a data-toggle="pill" href="#Ending"><button type="submit" class="btn btn-default" id="EndingB"><span class="glyphicon glyphicon-export"></span> Ending</button></a>

                <br>
                <br> 

                <style>
                  div .d2
                  {
                    overflow:auto;
                  }
                </style>                
                <form action="mainPage.php" method="POST" role="form" id="registerEnding">
                  <div class="form-group col-md-5" id ="d2" style="height: 210px; overflow-y: scroll;">
                    <?php 
                      include 'php/Employee/EndShift.php';
                    ?>
                    <script>
                      document.getElementById("StartingB").disabled = true;
                      var ArrId= <?php echo json_encode($arrId1 ); ?>;
                      var ArrQty= <?php  echo json_encode($arrQty1 ); ?>;
                      var counter= <?php echo  json_encode($i1); ?>;
                      var pettyCashIn=<?php echo json_encode($pettyCashIn); ?>;

                      var ArrId= <?php echo json_encode($arrId1 ); ?>;
                      var ArrQty= <?php  echo json_encode($arrQty1 ); ?>;
                      var counter= <?php echo  json_encode($i1 ); ?>;
                      var EndShiftCount= <?php echo json_encode($shiftNumberEnd); ?>;
                    </script>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                    
                    <div class="input-group" style='width:100%;'>
                    <span class="input-group-addon" style='width:50%; white-space:normal;' id="basic-addon1" >Petty Cash</span>
                    <input type="number" step="0.01" min="0" style='width:100%;' data-bind='value:replyNumber' class='form-control'  aria-describedby='basic-addon1' id="pettyCash" required>
                    </div>

                    <div class="input-group" style='width:100%;'>
                    <span class="input-group-addon" style='width:50%; white-space:normal;' id="basic-addon1" >Expenses</span>
                    <input type="number" step="0.01" min="0" style='width:100%;' data-bind='value:replyNumber' class='form-control'  aria-describedby='basic-addon1' id="expenses" required>
                    </div>
                
                    <div class="input-group" style='width:100%;'>
                    <span class="input-group-addon" style='width:50%; white-space:normal;' id="basic-addon1" >Bank Deposit</span>
                   <input type="number" step="0.01" min="0" style='width:100%;' data-bind='value:replyNumber' class='form-control'  aria-describedby='basic-addon1' id="bankDeposit" required>
                    </div>

                    <div class="input-group" style='width:100%;'>
                    <span class="input-group-addon" style='width:50%; white-space:normal;' id="basic-addon1" >Credit Sales</span>
                    <input type="number" step="0.01" min="0" style='width:100%;' data-bind='value:replyNumber' class='form-control'  aria-describedby='basic-addon1' id="creditSales" required>
                    </div>

                    <div class="input-group" style='width:100%;'>
                    <span class="input-group-addon" id="basic-addon1" ><?php echo "Bank Bag #".$bankBag; ?></span>
                    </div>

                     <br> 
                    <label for="comment">Note:</label>
                    <textarea class="form-control" rows="5" id="comment" ></textarea>
                    </div>
                    <button type="button" class="btn btn-default" onclick="ClearEnd()">Clear All</button>
                    <button   type="button" class="btn btn-default"   id="Endingshift">Save and Start Shift</button>
                  </div>
                </form>
              <?php 
                }
              ?>
    </div>
      </div>
      </div>    <!--Termina Shift -->

      <!--Panel Shift-->
        <!-- <div id="shift" class="tab-pane fade">
           <div class="panel panel-default panel-transparent">
            <div class="panel-heading"><h3>Shift</h3></div>
            <div class="panel-body">
              <form role="form">
                <div class="form-group col-md-5">
                </div>
              </form>
           </div>
          </div>
        </div> -->

        <!--Panel Sales-->
        <div id="sales" class="tab-pane fade">
          <div class="panel panel-default panel-transparent">
            <div class="panel-heading"><h3>Sales</h3></div>
            <div class="panel-body">
              <form role="form" id="saleBtns">
                <div class="form-group col-md-5">
                  <p>Available Products</p>
                  <div id="btnSales" style="height:320px; overflow-y: scroll;">
                   <?php 
                   if(isset($_SESSION['eLoc']))
                   {
                      $sql = "Select p.productName, lp.productId From location_product lp, product p Where lp.active = '1' And p.productId = lp.productId And lp.productLocation = '".$_SESSION['eLoc']."'";
                      $stmt = $conn->prepare($sql);
                      $stmt->execute();
                      $result = $stmt->fetchAll();

                      foreach($result as $row) 
                      { 
                          echo "<button type='button' style='box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24); white-space:normal; width: 31%; margin-right:5px; height:100px;' class='btn btn-info pull-left addEmpBtn' value=". $row['productId'].">". $row['productName']."</button>";
                      }      
                    }                                    
                    ?>
                  <input id="sPromotion" data-toggle="modal" data-target="#sMPromo" type="button" style="width: 33%;height:100px;" class="btn btn-default pull-left addEmpBtn" value="Promotions">
                </div>
                </div>
                 <div class="form-group col-md-7">
                  <p>Purchase Summary</p>
                  <select style="width:49%; margin-right:2px;" size="9" class="pull-left form-control addEmpBtn" id="sPurchase">
                   <option>Items in Bag: </option>
                  </select>

                  <div class="well addEmpBtn pull-right" style="margin-bottom:0; height:175px; background: white; width:50%; border: 1px solid #ccc">
                  <p id='subTotal'>Subtotal: </p>
                  <p id="sPromo">Promotions: </p>
                  <p id="">Tax: </p>
                  <p id="sTotal">Total: </p>
                  <p> </p>
                  <p> </p>
                  </div>

                  <input id="sPay" style="margin-right: 1px;" type="button" class="btn btn-default pull-right addEmpBtn" value="Pay"><!--<span class="glyphicon glyphicon-remove"></span>--><!-- Cancel</button> -->
                  <input id="sRemove" style="margin-right: 1px;" type="button" class="btn btn-default pull-right addEmpBtn" value="Remove of Bag"><!--<span class="glyphicon glyphicon-pencil"></span>--><!-- Remove From Bag</button> -->
                  <input id="sCancel" style="margin-right: 1px;" type="button" class="btn btn-default pull-right addEmpBtn" value="Cancel"><!--<span class="glyphicon glyphicon-trash"></span>--><!-- Pay</button> -->
                  <input id="sVoid" style="margin-right: 1px;" data-toggle="modal" data-target="#sVoidModal" type="button" class="btn btn-default pull-right addEmpBtn" value="Void"><!--<span class="glyphicon glyphicon-ok"></span>--><!-- Void</button> -->
                </div>
              </form>
           </div>
          </div>
        </div>

        <!--Panel Reports-->
        <div id="report" class="tab-pane fade">
         <div class="panel panel-default panel-transparent">
            <div class="panel-heading"><h3>Reports</h3></div>
            <div class="panel-body">
              <form role="form">
                <div class="form-group col-md-3">

                  <select class="form-control" id="reportType" style="margin-top:10px; width:100%;">
                    <option selected disabled>Type of Report</option>
                    <option id="invRep">Inventory</option>
                    <option id="dailyRep">Daily sold item</option>
                    <option id="deliRep">Delivery</option>
                    <option id="saleSummary">Sale Summary</option>
                    <option id="shiftRep">Shift</option>
                    <option id="shiEdiRep">Shift Edit</option>
                    <option id="trainRep">Train Report</option>
                  </select>

                  <input id="repFrom" class="datepicker form-control addEmpFields" style="width:100%; margin-top:3px;" type="text" placeholder="From">
                  <input id="repTo" class="datepicker form-control addEmpFields" style="width:100%; margin-top:3px;" type="text" placeholder="To">
                  
                  <select class="form-control" id="reportStore" style="margin-top:10px; width:100%;">
                    <option selected disabled>Stores</option>
                      <?php 
                        if($_SESSION['ePrivilege'] == 1)
                        {
                          $sql = "Select * From location";
                          $stmt = $conn->prepare($sql);
                          $stmt->execute();
                          $result = $stmt->fetchAll();

                          foreach($result as $row) 
                          { 
                            echo "<option value=". $row['locationId'].">". $row['locationName']."</option>";
                          }
                        }
                        else if($_SESSION['ePrivilege'] == 2)
                        {
                          $sql = "Select l.locationName, l.locationId From location_user lu, location l Where lu.empId = " .$_SESSION['eId']. " AND lu.locationId = l.locationId";
                          $stmt = $conn->prepare($sql);
                          $stmt->execute();
                          $result = $stmt->fetchAll();

                          foreach($result as $row) 
                          { 
                            echo "<option value=". $row['locationId'].">". $row['locationName']."</option>";
                          }
                        }
                      ?>
                    </select>

                    <!-- <input style="display: none;" type="button" id="viewRepBtn" class="btn btn-default pull-right addEmpBtn" value="View"><br><br> -->

                </div>
                  <div class="form-group col-md-9">
                    <label>Choose a Format</label>
                    <button type="button" style="background-color:transparent; border-color:transparent;" id="EXCEL"><img src="images/icon_excel.png"></button>
                    <button type="button" style="background-color:transparent; border-color:transparent;" id="PDF"><img src="images/icon_pdf.png"></button>
                    <button type="button" style="background-color:transparent; border-color:transparent;" id="WORD"><img src="images/icon_word.png"></button>
                    <div style="width:100%; background-color:white;">

                    </div>
                  </div>
              </form>
           </div>
          </div>
        </div>

        <!--Panel Inventory-->
        <?php if(@$_SESSION['ePrivilege'] == 1){
            echo '
          <div id="inventory" class="tab-pane fade"> 
           <div class="panel panel-default panel-transparent">
              <div class="panel-heading"><h3>Inventory</h3></div>
              <div class="panel-body">
                <form role="form">
                  <div class="form-group col-md-5">
                  <p style="margin-top:10px;">Create a new Delivery:</p>
                  <select class="form-control addEmpFields" id="dIPLoc">
                    <option selected disabled>Stores to deliver</option>';
                    
                        $sql = "Select * From location";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                        foreach($result as $row) 
                        { 
                          echo "<option value=". $row["locationId"].">". $row["locationName"]."</option>";
                        }                                          
                    
                    echo '</select>
                    <select class="form-control addEmpFields" id="dIProduct" style="width: 95%">
                     <option selected disabled>Available products at this store</option>
                    </select>

                    <input id="dIPrice" type="text" class="form-control addEmpFields" disabled placeholder="Price" style="width:29%;"> x 
                    <input id="dIQty" type="text" class="form-control addEmpFields" placeholder="Quantity" style="width:29%;"> = 
                    <input id="dITotal" type="text" class="form-control addEmpFields" disabled placeholder="Total" style="width:29%;">
                    <span id="dISQty" style="display:inline-block; margin-top:3%; font-size: 20px;"></span>
                    <button id="dIAdd" type="button" class="btn btn-default pull-right addEmpBtn" style="margin-right: 5%; display:inline-block;"><span class="glyphicon glyphicon-ok"></span> Add</button>
                    <button id="dICancel" type="button" class="btn btn-default pull-right addEmpBtn" style="margin-right: 5%; display:inline-block;"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                    <!-- <button id="dIDelete" type="button" class="btn btn-default pull-left addEmpBtn"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                    <button id="dIEdit" type="button" class="btn btn-default pull-left addEmpBtn"><span class="glyphicon glyphicon-pencil"></span> Edit</button> -->
                  </div>
                  <div class="form-group col-md-7">
                    <p style="margin-top:10px;">Products to Deliver:</p>
                    <select size="10" class="form-control" id="dIList" style="width: 95%">
                    <option>Product Name:</option>
                    </select>
                    <button id="dIRL" type="button" class="btn btn-default pull-right addEmpBtn" style="margin-right: 5%;"><span class="glyphicon glyphicon-remove"></span> Remove From List</button>
                    <br>
                    <br>
                    <input id="dIRefNum" type="text" class="form-control addEmpFields pull-left" disabled style="width:47%" placeholder="Reference Number">
                    <input id="dITime" class="datepicker form-control addEmpFields" style="width:48%; margin-top:3px;" type="text" placeholder="Date of Delivery">
                    <textarea class="form-control addEmpFields" rows="3" id="dIComment" placeholder="Delivery Note...."></textarea>
                    <button id="dISend" type="button" class="btn btn-default pull-right addEmpBtn" style="margin-right: 5%;"><span class="glyphicon glyphicon-send"></span> Send</button>
                  </div>
                </form>
             </div>
            </div>
          </div>
          ';
        }
        else
        {
          //Delivery for employee
        echo '
        ';
        }
        ?>
        <div id="inventory" class="tab-pane fade"> 
         <div class="panel panel-default panel-transparent">
            <div class="panel-heading"><h3>Supervisor Inventory</h3></div>
            <div class="panel-body">
              <form role="form">
                <div class="form-group col-md-5">
                <p>Delivery Reference Number</p>
                  <input id="iSDN" type="text" class="form-control addEmpFields" placeholder="Delivery Number">
                  <input id="iSDNB" type="button" style="margin-right: 5%;" class="btn btn-default pull-right addEmpBtn" value="Verify">
                  <br/>
                  <br/>
                  <br/>
                </div>
                <div class="form-group col-md-7">
                  <div id="itemsDelivery">
                  </div>
                </div>
              </form>
           </div>
          </div>
        </div>

        <!--Panel Employee Management-->
        <div id="employeeManagement" class="tab-pane fade">
          <div class="panel panel-default panel-transparent">
            <div class="panel-heading"><h3>Employee Management</h3></div>
            <div class="panel-body">

                <a data-toggle="pill" href="#employeeManagement"><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-user"></span> Employee Management</button></a>
                <a data-toggle="pill" href="#productManagement"><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-apple"></span> Products Management</button></a>
                <a data-toggle="pill" href="#promotionManagement"><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-tag"></span> Promotions Management</button></a>

              <form action="process.php" method="POST" id="addEmp">
                <div class="form-group col-md-5">
                  <label for="email" style="margin-top:10px;">Fill to add New Employee:</label>
                  <input name="manageEmployees" type="hidden" value="myform">
                  <input type="text" class="form-control addEmpFields" id="mName" placeholder="Name"><span style="color:red;font-weight:bold;"> *</span>
                  <input type="text" class="form-control addEmpFields" id="mLastname" placeholder="Last Name"><span style="color:red;font-weight:bold;"> *</span>
                  <select class="form-control addEmpFields" id="mQuestion">
                    <option selected disabled>Question</option>
                    <option value="What is your phone number?">What is your phone number?</option>
                    <option value="What are the last four digits of your Social Security?">What are the last four digits of your Social Security?</option>
                  </select>
                  <input type="text" class="form-control addEmpFields" id="mAnswer" placeholder="Answer">
                  <select id="mLevel" class="form-control addEmpFields">
                  <option selected disabled>Employee category</option>
                    <?php 
                      $sql = "Select * From privilege";
                      $stmt = $conn->prepare($sql);
                      $stmt->execute();
                      $result = $stmt->fetchAll();

                      foreach($result as $row) 
                      { 
                        echo "<option value=". $row['privilegId'].">". $row['privilegeName']."</option>";
                      }                                          
                    ?>
                  </select><span style="color:red;font-weight:bold;"> *</span>
                  <input type="text" class="form-control addEmpFields" id="mUsername" placeholder="Username"><span style="color:red;font-weight:bold;"> *</span>
                  <input type="text" class="form-control addEmpFields" id="mPassword" placeholder="Password"><span style="color:red;font-weight:bold;"> *</span>
                  <div id='testings'>
                   
                    <select id="mELocation" class="form-control addEmpFields">
                    <option selected disabled>Employee Location</option>
                      <?php 
                        $stmt = $conn->prepare("Select * From location");
                        $stmt->execute();
                        $result = $stmt->fetchAll();

                        foreach($result as $row) 
                        { 
                          echo "<option value=". $row['locationId'].">". $row['locationName']."</option>";
                        }                                          
                      ?>
                    </select>
                    <textarea class="form-control addEmpFields comment" rows="1" placeholder="Location Note...."></textarea>
                      
                    <select style='display:none;' class="form-control addEmpFields mLocation"></select>
                    <textarea style='display:none;' class="form-control addEmpFields mcomment" rows="1" placeholder="Location Note...."></textarea>
                    <select style='display:none;' class="form-control addEmpFields mLocation"></select>
                    <textarea style='display:none;' class="form-control addEmpFields mcomment" rows="1" placeholder="Location Note...."></textarea>
                    <select style='display:none;' class="form-control addEmpFields mLocation"></select>
                    <textarea style='display:none;' class="form-control addEmpFields mcomment" rows="1" placeholder="Location Note...."></textarea>
                    <select style='display:none;' class="form-control addEmpFields mLocation"></select>
                    <textarea style='display:none;' class="form-control addEmpFields mcomment" rows="1" placeholder="Location Note...."></textarea>
                    
                  </div>

                  <button id="empCancel" type="button" class="btn btn-default pull-left addEmpBtn"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                  <button id="empDelete" type="button" class="btn btn-default pull-left addEmpBtn"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                  <button id="empEdit" type="button" class="btn btn-default pull-left addEmpBtn"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
                  <button id="empAdd" type="button" class="btn btn-default pull-left addEmpBtn"><span class="glyphicon glyphicon-ok"></span> Add</button>
                </div>

              <!--Dropdowns of locations/ Manager select Location/ Employees appear from selected location -->
              <div class="form-group col-md-7">
                <div class="form-group">
                  <label style="margin-top:10px;">Pick a Location:</label>
                  <select name="stores" class="form-control" id="storesLocation" style="margin-top:10px; width:95%;">
                  <option selected disabled>Stores</option>
                    <?php 
                      $stmt = $conn->prepare("Select * From location");
                      $stmt->execute();
                      $result = $stmt->fetchAll();

                      foreach($result as $row) 
                      { 
                        echo "<option value=". $row['locationId'].">". $row['locationName']."</option>";
                      }                                          
                    ?>
                  </select>
                  <label style="margin-top:10px;">Employees in this location:</label>
                  
                  <select name="" class="form-control" id="employeesAtStores" style="width: 95%" size="15">
                  </select>
                </div>
              </div>
            </form>

           </div>
          </div>
        </div>

        <!--Panel Product Management-->
        <div id="productManagement" class="tab-pane fade">
         <div class="panel panel-default panel-transparent">
            <div class="panel-heading"><h3>Product Management</h3></div>
            <div class="panel-body">

                <a data-toggle="pill" href="#employeeManagement"><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-user"></span> Employee Management</button></a>
                <a data-toggle="pill" href="#productManagement"><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-apple"></span> Products Management</button></a>
                <a data-toggle="pill" href="#promotionManagement"><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-tag"></span> Promotions Management</button></a>

              <form role="form">
                <div class="form-group col-md-5">
                  <label style="margin-top:10px;">Fill to add New Product:</label>
                  <input type="text" class="form-control addEmpFields" id="mPName" placeholder="Product Name"><span style="color:red;font-weight:bold;"> *</span>
                  <input type="text" class="form-control addEmpFields" id="mPPrice" placeholder="Product Price"><span style="color:red;font-weight:bold;"> *</span>
                  <!-- <input type="text" class="form-control addEmpFields" id="mPQTY" placeholder="Product Quantity"><span style="color:red;font-weight:bold;"> *</span> -->
                  <select class="form-control addEmpFields" id="mPLocation" style="margin-top:10px;">
                  <option selected disabled>Product Location</option>
                    <?php 
                      $stmt = $conn->prepare("Select * From location");
                      $stmt->execute();
                      $result = $stmt->fetchAll();

                      foreach($result as $row) 
                      { 
                        echo "<option value=". $row['locationId'].">". $row['locationName']."</option>";
                      }                                          
                    ?>
                  </select><span style="color:red;font-weight:bold;"> *</span>
                  <button id="proCancel" type="button" class="btn btn-default pull-left addEmpBtn"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                  <button id="proDelete" type="button" class="btn btn-default pull-left addEmpBtn"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                  <button id="proEdit" type="button" class="btn btn-default pull-left addEmpBtn"><span class="glyphicon glyphicon-pencil"></span> Edit</button>
                  <button id="proAdd" type="button" class="btn btn-default pull-left addEmpBtn"><span class="glyphicon glyphicon-ok"></span> Add</button>

                </div>
                
                <div class="form-group col-md-7">
                <div class="form-group">
                  <select class="form-control addEmpFields" id="mPAt" style="width: 90%">
                    <option selected disabled>Products at:</option>
                    <?php 
                      $sql = "Select * From location";
                      $stmt = $conn->prepare($sql);
                      $stmt->execute();
                      $result = $stmt->fetchAll();

                      foreach($result as $row) 
                      { 
                        echo "<option value=". $row['locationId'].">". $row['locationName']."</option>";
                      }                                          
                    ?>
                  </select>
                  <p style="margin-top:10px;"><span style="text-align:left">Product in Store:</span><span style="float: right;margin-right: 10%;">Product not in Store:</span></p>
                  <select size="15" class="form-control addEmpFields" id="mPAvailable" style="width: 45%">
                  </select>
                  <select size="15" class="form-control addEmpFields" id="mpNotAvailable" style="width: 45%">
                  </select>
                </div>
              </div>
              </form>
           </div>
          </div>
        </div>
        
        <!--Panel Promotion Management-->
        <div id="promotionManagement" class="tab-pane fade">
         <div class="panel panel-default panel-transparent">
            <div class="panel-heading"><h3>Promotion Management</h3></div>
            <div class="panel-body">
                <a data-toggle="pill" href="#employeeManagement"><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-user"></span> Employee Management</button></a>
                <a data-toggle="pill" href="#productManagement"><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-apple"></span> Products Management</button></a>
                <a data-toggle="pill" href="#promotionManagement"><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-tag"></span> Promotions Management</button></a>
              <form role="form">
                <div class="form-group col-md-5">
                  <label style="margin-top:10px;">Fill to add New Promotion:</label>
                  <input type="text" class="form-control addEmpFields" id="mPromoName" placeholder="Promotion Name">
                  
                  <select class="form-control" id="mPromoPNeed" style="width:95%; margin-top:10px;">
                    <option selected disabled>Needed product</option>
                    <?php 
                      $stmt = $conn->prepare("Select lp.productId, p.productName From location_product lp, product p Where lp.productLocation='".$_SESSION['eLoc']."' AND lp.productId = p.productId And lp.active='1'");
                      $stmt->execute();
                      $result = $stmt->fetchAll();

                      foreach($result as $row) 
                      { 
                        echo "<option value=". $row['productId'].">". $row['productName']."</option>";
                      }                                          
                    ?>
                  </select>
                  
                  <input type="number" min='1' class="form-control addEmpFields" id="mPromoQtyPro" placeholder="Quantity of needed product">
                  
                  <select class="form-control" id="mPromoInProm" style="width:95%; margin-top:10px;">
                    <option selected disabled>At promotion</option>
                    <?php 
                      $stmt = $conn->prepare("Select lp.productId, p.productName From location_product lp, product p Where lp.productLocation='".$_SESSION['eLoc']."' AND lp.productId = p.productId And lp.active='1'");
                      $stmt->execute();
                      $result = $stmt->fetchAll();

                      foreach($result as $row) 
                      { 
                        echo "<option value=". $row['productId'].">". $row['productName']."</option>";
                      }                                          
                    ?>
                  </select>

                  <select class="form-control" id="mPromoLocAssign" style="width:95%; margin-top:10px;">
                    <option selected disabled>Promotion apply on:</option>
                    <?php 
                      $stmt = $conn->prepare("Select * From location");
                      $stmt->execute();
                      $result = $stmt->fetchAll();

                      foreach($result as $row) 
                      { 
                        echo "<option value=". $row['locationId'].">". $row['locationName']."</option>";
                      }                                          
                    ?>
                  </select>

                  <button id="promoCancel" type="button" class="btn btn-default pull-left addEmpBtn"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                  <button id="promoRemove" type="button" class="btn btn-default pull-left addEmpBtn"><span class="glyphicon glyphicon-trash"></span> Remove</button>
                  <button id="promoAdd" type="button" class="btn btn-default pull-left addEmpBtn"><span class="glyphicon glyphicon-ok"></span> Add</button>
                </div>
                <div class="form-group col-md-7">
                <label style="margin-top:10px;">Pick a Location:</label>
                  <select class="form-control" id="mPromoLocation" style="width:95%; margin-top:10px;">
                      <option selected disabled>Locations</option>
                    <?php 
                      $stmt = $conn->prepare("Select * From location Order By locationName ASC");
                      $stmt->execute();
                      $result = $stmt->fetchAll();

                      foreach($result as $row) 
                      { 
                        echo "<option value=". $row['locationId'].">". $row['locationName']."</option>";
                      }                                          
                    ?>
                  </select>
                  <label style="margin-top:10px;">Promotions in this Location:</label>
                  <select size="15" class="form-control" id="promoAtLocation" style="width: 95%">
                  </select>
                </div>
              </form>
           </div>
          </div>
        </div>

      </div><!--End of tab-content-->
    </div><!--End of Col 10-->
  </body>

<!-- Loc Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header"  style="background-color: red;">
        <button type="button" id="closeLoc" class="close" data-dismiss="modal">&times;</button>
        <h4 style="color:white;" class="modal-title">Pick a Location</h4>
      </div>
      <div class="modal-body">
        <select id="empLocal">
        <option selected disabled>Locations</option>
        <?php
        for($i = 0; $i < sizeof(@$eLocation); $i++)
          echo "<option value=".@$eLocation[$i].">".@$lName[$i]."</option>";
        ?>
        </select>
          Select a Location
          <br/><p><?php echo 'Actual location: '. @$_SESSION['locName']?></p>
      </div>
      <div class="modal-footer">
        <button type="button" disabled id="mLoc" class="btn btn-default" style="color:white;background-color: red;" data-dismiss="modal"><b>Ok</b></button>
      </div>
    </div>
  </div>
</div>

<div id="sMPromo" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Promo Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="color:white;background-color: red;">
        <button id='closePromo' type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><b>Promotions</b></h4>
      </div>
      <div class="modal-body">
      Select a Promotion: 
        <select id="sPPromo">
        <option selected disabled>Select a Promotion</option>
        <?php
            try 
            {
              $stmt = $conn->prepare("Select p.promotionName, lp.promotionId From location_promotion lp, promotion p Where p.active='1' AND lp.promotionId = p.promotionId AND lp.locationId = '".$_SESSION['eLoc']."'");                                            
              $stmt->execute();
              $result = $stmt->fetchAll();
              foreach($result as $row) 
              { 
                echo "<option value=". $row['promotionId'].">". $row['promotionName']."</option>";
              }                       
            }
            catch(PDOException $e)
            {
              $data['success'] = false;
            }
        ?>
        </select>
        <br><br>
        <span id="reqPromo" class='pull-left'></span>
        <br>
        <span id="inBag" class='pull-left'></span>
        <br>
      </div>
      <div class="modal-footer">
        <button type="button" disabled id="saleSPromo" class="btn btn-default"  style="color:white;background-color: red;" data-dismiss="modal"><b>Ok</b></button>
      </div>
    </div>
  </div>
</div>

<!-- Void Modal -->
<div id="sVoidModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header"  style="background-color: red;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="color:white"><b>Refund(Void)</b></h4>
      </div>
      <div class="modal-body">
          <input type="text" id="purchaseID" class='form-control pull-left' placeholder="Purchase ID" style="width:66%; float:left;">
          <button type="button" id="voidSubmit" class="btn btn-default"  style="color:white;background-color: red;margin-left: 10px;"><b>Verify</b></button>

            <select class="form-control" id="voidItems" style="width:80%; display:none; margin-top:10px;">
            </select>
          <input type="text" id="voidQuantity" class='form-control addEmpFields' placeholder="Quantity" style="width:40%; display:none;">
          <input type="text" disabled id="voidRefund" class='form-control addEmpFields' placeholder="Refund" style="width:40%; display:none;">
          <button type="button" id="voidAccept" disabled class="btn btn-default"  style="color:white;background-color: red; display:none;"><b>Submit</b></button>
      </div>
      <div class="modal-footer">
        <button type="button" id="sMVoid" class="btn btn-default"  style="color:white;background-color: red;" data-dismiss="modal"><b>Ok</b></button>
      </div>
    </div>
  </div>
</div>

<!-- First time Modal -->
<div id="fTModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header"  style="background-color: red;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="color:white"><b>First Time Here!</b></h4>
      </div>
      <div class="modal-body">
        <input type="text" class='form-control addEmpFields pull-left' placeholder="Item" style="width:33%;">
        <input type="text" class='form-control addEmpFields pull-left' placeholder="Quantity" style="width:33%;">
        <input type="text" class='form-control addEmpFields pull-left' placeholder="Price" style="width:33%;">
      </div>
      <div class="modal-footer">
        <button type="button" id="answerfTModal" class="btn btn-default"  style="color:white;background-color: red;" data-dismiss="modal"><b>Ok</b></button>
      </div>
    </div>
  </div>
</div>

</html>