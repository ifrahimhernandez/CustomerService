$(document).ready(function(){
	
	var inputNum;
	$('#iSDNB').click(function(){ //Get products of given delivery reference number input
		inputNum = document.getElementById('iSDN').value;
		var datasend = 'inputNum=' + inputNum;
		if(!isNaN(inputNum) && inputNum != "")
		{
			$.ajax
			({
				url: 'dISProcess.php',
				type: 'POST',
				cache: false,
				data: datasend,
				success: function(html)
				{

					$("#itemsDelivery").html(html);
				}
			}).done(function(data){
				if(data.success == false)
				{
					alert('Error: Fetching product data!');
				}
			});
		}
	});
	
	$("#itemsDelivery").on('click', '#dISSubmit', function(){ //Confirm to accept delivery
		if (confirm("Accept delivery?") == true) 
		{
			var proIds = new Array();
			var proNot = new Array();
			$('#itemsDelivery > input').each(function(){
				if($(this).val() == '') //If nothing is input, 0 damage of this product
				{
					proIds[$(this).attr("id")] = 0;
				}
				else
				{
					proIds[$(this).attr("id")] = $(this).val();
				}
			});

			$('#itemsDelivery > textarea').each(function(){
				proNot[$(this).attr("id")] = $(this).val();
			});
			var deliveryData = {
				ids : proIds,
				not : proNot,
				dnr : inputNum
			};
			$.ajax({
				url: 'dISProcess.php',
				type: 'POST',
				dataType: 'json',
				data: deliveryData

			}).done(function(data){
				if(data.success == true)
				{
					alert("Products have been added to the inventory");
					document.getElementById('iSDN').value = '';
					 $('#itemsDelivery').empty();
				}
				else
				{
					alert('Error: Storing data!');
				}

			});
		}
	});
});