$(document).ready(function () {
    $('#DateButton').click(function(){ 
  
   var date= document.getElementById("datepicker").value;
   var shift= $('#TodayShift option:selected').val();
       $.ajax({
     type: "GET",
     url: "php/Supervisor/getdata.php?date="+date+"&shift="+shift
      }).done(function( data ) {
    $('#viewdata').html(data);
      });

      $.ajax({
     type: "GET",
     url: "php/Supervisor/pettycashtable.php?date="+date+"&shift="+shift
      }).done(function( data ) {
    $('#pettyCashView').html(data);
      });

       $.ajax({
     type: "GET",
     url: "php/Supervisor/othertable.php?date="+date+"&shift="+shift
      }).done(function( data ) {
    $('#othertable').html(data);
      });

    
    $('#formpro').show();

      $.ajax({
     type: "GET",
     url: "php/Supervisor/selectProducts.php?date="+date+"&shift="+shift
      }).done(function( data ) {
    $('#Products').html(data);
      });
    
    });
 });



 

$(document).ready(function () {



    $('#DamageButton').click(function(){ 
  var isValid=false;
   isValid = validateDamage();
    
 if(isValid)
     { 
   var quantity= document.getElementById("quantity").value;
   var productsId= $('#Products option:selected').val();
   var comment= document.getElementById("comment").value;   


      $.ajax({
     type: "GET",
     url: "php/Supervisor/supervisorDamage.php?quantity="+quantity+"&productsId="+productsId+"&comment="+comment
      }).done(function( data ) {
        alert(data);
        document.getElementById("formpro").reset();
      });

      
    }
    });
 });


function validateDamage () {
  
  if (document.getElementById("quantity").value=="" ||  isNaN(document.getElementById("quantity").value)) {

      alert("Please fill all the products textboxes or an text error is occuring.");
      return false;

  } else  if (document.getElementById("comment").value=="") {
          alert("Please write an explanation on the Note area. ");
          return false;
      }else if (document.getElementById("quantity").value=="") {

        alert("Please fill the quantity box. ");
          return false;
      }else{
        return true;
        
      }




  }

    function viewdata(str){
        var date = str;
       $.ajax({
     type: "GET",
     url: "php/Supervisor/getdata.php?date="+date
      }).done(function( data ) {
    $('#viewdata').html(data);
      });
    }

    function pettyCashView(str){
       var date = str;
       $.ajax({
     type: "GET",
     url: "php/Supervisor/pettycashtable.php?date="+date
      }).done(function( data ) {
    $('#pettyCashView').html(data);
      });
    }

    function otherView(str){
       var date = str;
       $.ajax({
     type: "GET",
     url: "php/Supervisor/othertable.php?date="+date
      }).done(function( data ) {
    $('#othertable').html(data);
      });
    }

    function updatedata(str){
   var intRegex = /^\d+$/;
  var id = str;
  var nm = $('#nm'+str).val();
  var stQ = $('#stQ'+str).val();
  var enQ = $('#enQ'+str).val();
  var date = $('#date').val();
  var starId=$('#inside'+str).val();
  var endingId=$('#outside'+str).val();

  if (stQ=="" ||  !intRegex.test(stQ) || enQ=="" ||  !intRegex.test(enQ)){
alert("Please fill all the products textboxes. Or an text error is occuring.");

  }else{

  var datas="nm="+nm+"&stQ="+stQ+"&enQ="+enQ+"&date="+date+"&starId="+starId+"&endingId="+endingId;
      
  $.ajax({
     type: "POST",
     url: "php/Supervisor/updatedata.php?id="+id,
     data: datas
  }).done(function( data ) {
    $('#info').html(data);
    viewdata(date);
  });

}
    }

function updatedatapettycash(str){
   var intRegex = /^\d+$/;
  var id = str;
  var stQ = $('#stQ'+str).val();   //pettycash update
  var enQ = $('#enQ'+str).val();
  var date = $('#date').val();

 if (stQ=="" ||  isNaN(stQ) || enQ=="" ||  isNaN(enQ)){
alert("Please fill all the products textboxes. Or an text error is occuring.");

  }else{

  var datas="stQ="+stQ+"&enQ="+enQ;
      
  $.ajax({
     type: "POST",
     url: "php/Supervisor/pettycashupdate.php?id="+id,
     data: datas
  }).done(function( data ) {
    $('#info').html(data);
    pettyCashView(date);
  });
}
    }


  function updateother(str){
  
  var id = str;
  var Drop = $('#Drop').val();                //other update
  var CreditSales = $('#CreditSales').val();
  var BankDeposit = $('#BankDeposit').val();
  var Expences = $('#Expences').val();
  var date = $('#date').val();

   if (Drop=="" ||  isNaN(Drop) || CreditSales=="" ||  isNaN(CreditSales)|| BankDeposit=="" ||  isNaN(BankDeposit)|| Expences=="" ||  isNaN(Expences)){
alert("Please fill all the products textboxes. Or an text error is occuring.");

  }else{

  var datas="Drop="+Drop+"&CreditSales="+CreditSales+"&BankDeposit="+BankDeposit+"&Expences="+Expences+"&date="+date;
      
  $.ajax({
     type: "POST",
     url: "php/Supervisor/updateother.php?id="+id,
     data: datas
  }).done(function( data ) {
    $('#info').html(data);
    otherView(date);
  });
}
    }  


    $(function() {
    $( "#datepicker" ).datepicker({


      dateFormat: 'yy-mm-dd',
      onSelect: function(dateText, inst) { 
        $.ajax({
     type: "GET",
     url: "php/Supervisor/selectData.php?date="+dateText,
      }).done(function( data ) {
    $('#TodayShift').html(data);



      });
    }});
  });