
function Clear(){
  document.getElementById("register").reset();
}

function ClearEnd(){
  document.getElementById("registerEnding").reset();
}


$(document).ready(function(){

  // var inorno= <?php echo json_encode($inOrOut ) ?>;
  // alert(inorno);
  if(inorno=="0"){

document.getElementById( 'sales1' ).style.display = 'none';
  }else{

     document.getElementById("sales1").style.display = "block"; 

  }

});


$(document).ready(function(){

function validateFormStarting()
{
   
    

    var endproductId;
    
    var endArQty;
    var id ;
    var q=false;
    var k=false;
    var intRegex = /^\d+$/;
  for (i = 0; i < counter; i++) {
   
    endproductId= endingId[i];
    endArQty=endingQty[i];


    if(document.getElementById("number"+endproductId).value != endArQty){
       q= true;
    }
    



    if(document.getElementById("number"+endproductId).value == "" ||  isNaN(document.getElementById("number"+endproductId).value)){
      k=true;

      
    }

    

    }

    if (document.getElementById("pettyCash").value!=pettyCash) {
      q=true;
    }



    if (document.getElementById("pettyCash").value=="") {
      k=true;
    }
        if(k==true){
            alert("Please fill all the products textboxes. Or an text error is occuring.");
            return false;
        } else if(q==true){

      if (document.getElementById("comment").value=="") {
          alert("Please write an explanation on the Note area. Why are more or less products? Or Petty Cash is different from yesterday. ");
          return false;
      }else{
        return true;
        
      }
    
    }else{
        return true;
        
      }
   
   

}


  $('#Bshift').click(function(){
  var isValid=false;
   isValid = validateFormStarting();
    
 if(isValid)
       {
    
      var productId;
      var productQty;
      
      for (i = 0; i < counter; i++) {
        productQty = ArrQty[i];
        productId= ArrId[i];
       var pLocal= document.getElementById("number"+productId).value;
       var prodData = 'pLocal=' + pLocal+ '&proId='+productId +'&shiftStart='+StartShiftCount; 

       $.ajax ({
          url: 'php/Employee/shiftSave.php',
          type: 'POST',
          dataType: 'json',
          data: prodData,
          
           
         
            });
  
    }

  var pettyCash= document.getElementById("pettyCash").value;
  var comment= document.getElementById("comment").value;
  var prodData1 = 'pettyCash=' + pettyCash+ '&comment='+comment+'&shiftStart='+StartShiftCount; 

          $.ajax ({
          url: 'php/Employee/shiftSave.php',
          type: 'POST',
          dataType: 'json',
          data: prodData1,
          
         


          }).done(function(data){
      
              alert(data);
              document.getElementById('register').submit();
              // windows.location.href = "mainMenu.php";

          }).fail(function(error){
            alert("Error Saving Data.");
            document.getElementById('register').submit();
              // windows.location.href = "mainMenu.php";
          });
}



  });

});



//////////////////////////////END SHIFT/////////////////////////////



$(document).ready(function(){
            function validateFormEnding()
{
   
    var productId=0;
    var productQty=0;
    var id ;
  var q=false;
  var k=false;
  var w=false;
  var z=false;
   //var intRegex = /^\d+$/;
  for (i = 0; i < counter; i++) {
    productQty = parseInt(ArrQty[i]);
    productId= parseInt(ArrId[i]);


    
     if(document.getElementById("number"+productId).value == "" ||  isNaN(document.getElementById("number"+productId).value)){
      z=true;
      
    }

   
   

    }

    if (document.getElementById("pettyCash").value!=pettyCashIn) {
      q=true;
    }

    if (document.getElementById("pettyCash").value=="" || isNaN(document.getElementById("pettyCash").value)) {
      k=true;
    }

    if (document.getElementById("expenses").value==""  || isNaN(document.getElementById("expenses").value)) {
      k=true;
    }

    if (document.getElementById("bankDeposit").value==""  || isNaN(document.getElementById("bankDeposit").value)) {
      k=true;
    }

    if (document.getElementById("creditSales").value==""  || isNaN(document.getElementById("creditSales").value)) {
      k=true;
    }
if (z==true) {
alert("Please fill all the products textboxes or an text error is occuring. ");
return false;

} else if(k==true){
 alert("Please fill all the products textboxes or an text error is occuring.");
 return false;
}else if(q==true){

      if (document.getElementById("comment").value=="") {
          alert("Please write an explanation on the Note area. ");
          return false;
      }else{
        
        return true;
      }

}else{
        
        
        return true;
      }

}

 $('#Endingshift').click(function(){ //Ending
  var isValid=false;
   isValid = validateFormEnding();
    
 if(isValid)
       {
    
      var productId;
      var productQty;
      
      for (i = 0; i < counter; i++) {
        productQty = ArrQty[i];
        productId= ArrId[i];
       var pLocal= document.getElementById("number"+productId).value;
       var prodData = 'valorProE=' + pLocal+ '&proIdE='+productId+'&EndShiftCount='+EndShiftCount; 

       $.ajax ({
          url: 'php/Employee/shiftEndingSave.php',
          type: 'POST',
          dataType: 'json',
          data: prodData,
          
           
          
         
            });
  
    }

      var pettyCash= document.getElementById("pettyCash").value;
      var espenses= document.getElementById("expenses").value;
      var bankDeposit=document.getElementById("bankDeposit").value;
      var creditSales=document.getElementById("creditSales").value;
      var comment= document.getElementById("comment").value;
      var prodData1 = 'pettyCash=' + pettyCash+ '&comment='+comment+ '&expenses='+espenses+ '&bankDeposit='+bankDeposit+'&creditSales='+creditSales+'&EndShiftCount='+EndShiftCount; 

          $.ajax ({
          url: 'php/Employee/shiftEndingSave.php',
          type: 'POST',
          dataType: 'json',
          data: prodData1,
          
          
          



          }).done(function(data){
              
               alert(data);
              document.getElementById("registerEnding").reset();
              document.getElementById('registerEnding').submit();
              // windows.location.href = "mainMenu.php";
                         
               //location.reload();


          }).fail(function(error){
           
           alert(" Error Saving Data . ");
              document.getElementById("registerEnding").reset();
              document.getElementById('registerEnding').submit();
              // windows.location.href = "mainMenu.php";
                         
               //location.reload();
          });
}



  });
   });