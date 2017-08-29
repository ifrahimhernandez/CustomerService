$(document).ready(function(){

	// $('#in').click(function(){
	// 	var userInput = document.getElementById('uNameI').value;
	// 	var passInput = document.getElementById('uPassI').value;
	// 	var logData = 
	// 	{
	// 		user: userInput,
	// 		pass: uPassI,
	// 	}

	// 	$.ajax
	// 	({
	// 		url: 'logInProcess.php',
	// 		type: 'POST',
	// 		dataType: 'json',
	// 		data: logData
	// 	}).done(function(data){
	// 		if(data.success == true && data.answer == 0)
	// 		{
	// 			$('#fTModal').modal('show');
	// 		}
	// 	});
	// });

	// $('#answerfTModal').click(function(){//first time
	// 	alert('Modal accept btn');
	// });

	// $('#mLoc').click(function(){
	// 	locationSelect = document.getElementById('empLocal').value;
	// 	var datasend = 'actualLocation=' + locationSelect;
	// 	$.ajax
	// 	({
	// 		url: 'logInProcess.php',
	// 		type: 'POST',
	// 		dataType: 'json',
	// 		data: datasend
	// 	}).done(function(data){
	// 		if(data.success == true)
	// 		{
	// 			location.reload();
	// 		}
	// 		else
	// 		{
	// 			alert('Error: Changing location. Verify connection or log in again.')
	// 		}
	// 	});
	// });

	// $('#empLocal').change(function(){
	// 	if(document.getElementById('empLocal').selectedIndex > 0)
	// 	{
	// 		$('#mLoc').prop('disabled', false);
	// 	}
	// });

	// $('#closeLoc').click(function(){ //When promo close reset select
	// 	document.getElementById('empLocal').selectedIndex = 0;
	// 	$('#mLoc').prop('disabled', true);
	// });

});