<!DOCTYPE html>
<!-- <html xmlns='http://www.w3.org/1999/xhtml'> -->
<html>
<head>
<!-- <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />
<title>Print to star printer directly from HTML</title>
<script type='text/javascript' src='assets/js/StarWebPrintBuilder.js'></script>
<script type='text/javascript' src='assets/js/StarWebPrintTrader.js'></script>
<script type='text/javascript' src='assets/js/html2canvas.min.js'></script> -->
<style>

.print-panel{
	/*border:1px solid #a0a0a0;*/
	width:250px;
    font: 15px arial, sans-serif;
    height:50px;
    position: absolute;
    border: 
    /*visibility:hidden;*/
}
div.print-img{
	margin:10px;
	text-align:center;
    font: 15px arial, sans-serif;
}
div.print-center{
	text-align:center;
    font: 15px arial, sans-serif;
}
div.print-row{
	width:100%;
}
div.print-item-name{
	clear:left;
	float:left;
	text-align:left;
    font: 15px arial, sans-serif;
    /*width:78%*/
}
div.print-item-price{
	clear:right;
	float:right;
	padding-right:10px;

	text-align:right;
    font: 15px arial, sans-serif;
    width:20%
}
div.print-space{
	clear:left;
	padding: 0.3em;
}
hr{
  width:100%;
}

#logo{
    width: 50%;
}
@media print {
    #Header, #Footer { display: none !important; }
}
</style>
 <script type="text/javascript">
// function starPrint(canvas, printerIP, successFn, errorFn){
// 	var context;
//     if (canvas.getContext){
//         context = canvas.getContext('2d');
//     }
// 	var url='http://'+printerIP+'/StarWebPRNT/SendMessage';
//     var trader = new StarWebPrintTrader({'url':url});
//     trader.onReceive = function (response) {
//     	if (response.traderSuccess){
    		
//     		successFn();
//     	}else{
//     		var msg='Printer Error. ';
//             if (trader.isCoverOpen({traderStatus:response.traderStatus})){
//             	msg += 'Cover is open';
//             }else if (trader.isOffLine({traderStatus:response.traderStatus})){
//             	msg += 'Printer is OffLine';
//             }else if (trader.isPaperEnd({traderStatus:response.traderStatus})){
//             	msg += 'End of Paper';
//             }
//             else{
//             	msg += 'Unknown Error';
//             }
//             errorFn(msg);
//     	}
    		
//     }.bind(this);
//     trader.onError = function (response) {
//         var msg = 'Print Error. ';
//         msg += 'Status:' + response.status;
//         msg += '; ResponseText:' + response.responseText;
//         errorFn(msg);
//     };
//     try{
//         var builder = new StarWebPrintBuilder();
//         var request = '';
//         request += builder.createBitImageElement({context:context, x:0, y:0, width:canvas.width, height:canvas.height});
//         request += builder.createInitializationElement();
//         request += builder.createCutPaperElement({feed:true});
//         trader.sendMessage({request:request});	                
//     }catch(msg){
//     	errorFn(msg);
//     	return;
//     }

// }
// function printMyPanel(){
// 	var printerIp=document.getElementById('printerIp').value;
// 	var printPanel=document.getElementById('printPanel');
// 	html2canvas(printPanel, {
// 		allowTaint:true,
// 		onrendered: function(canvas) {
// 			starPrint(canvas, printerIp,
// 					function(){
// 				alert('Receipt Printed Succesfully!');
// 			}, function(errMsg){
// 				alert('Printer error: '+errMsg);
// 			});
// 		}
// 	});

// }
</script>
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
</head>
<script>
    var text = window.location.hash.substring(1);
    var tId = 'pId=' + text; 
        $.ajax
        ({
            url: "sProcess.php",
            type: "POST",
            cache: false,
            data: tId,
            success: function(html)
            {
                $("#info").html(html);
            } 
        });
</script>


<body>
<!-- <input type="text" id="printerIp" placeholder="Printer IP address" value="192.168.1.9">
<input type="submit" value="Print" onclick="printMyPanel();"> -->

<div id="printPanel" class="print-panel">
<div id="info">
<!-- <div class="print-img"><img id="logo" alt="" src="./images/logo.png"/></div> -->

<!-- <div class="print-center">The Customer Service Experts</div>
<div class="print-center">Thank you for your for coming.</div>
<div class="print-center">We hope you'll visit again.</div>
<div class="print-center">Tel: 1(800)969-7820</div> -->

<!-- <div class="print-item-name">Apple</div><div class="print-item-price">$20.00</div>
<div class="print-item-name">Banana</div><div class="print-item-price">$30.00</div>
<div class="print-item-name">Grapes</div><div class="print-item-price">$40.00</div>
<div class="print-item-name">Lemon</div><div class="print-item-price">$50.00</div>
<div class="print-item-name">Orange</div><div class="print-item-price">$60.00</div>

<div class="print-row"><div class="print-item-name">Subtotal</div><div class="print-item-price">$200.00</div>
<div class="print-space"></div>
<div class="print-space"></div>
<div class="print-item-name">Tax</div><div class="print-item-price">$10.00</div>
<div class="print-space"></div>
<hr/>  

<div class="print-space"></div>
<div class="print-item-name">Total</div><div class="print-item-price">$210.00</div>
<div class="print-space"></div>
<div class="print-space"></div>

<div class="print-item-name">Received</div><div class="print-item-price">$300.00</div>
<div class="print-item-name">Change</div><div class="print-item-price">$90.00</div>
 -->

</div>
</div>
</div>
</body>
</html>

 <script type="text/javascript">
window.print();
window.onfocus=function(){ window.close();}
 </script>