$(document).ready(function(){ 

	// var stockItem;
	// var qty;
	$('#dICancel').click(function(){ //Clear
		if(confirm('Are you sure you want to cancel this delivery?') == true)
		{
			document.getElementById('dIProduct').options.length = 1;
			document.getElementById('dIProduct').selectedIndex = 0;
			document.getElementById('dIPLoc').selectedIndex = 0;			
			document.getElementById('dITotal').value = '';
			document.getElementById('dIQty').value = '';
			document.getElementById('dIPrice').value = '';
			document.getElementById('dIRefNum').value = '';
			document.getElementById('dITime').value = '';
			document.getElementById('dIComment').value = '';
			document.getElementById("dIList").options.length = 1;
			// document.getElementById('dISQty').innerHTML = '';
		}
	});

	$('#dIAdd').click(function(){
	    var select = document.getElementById('dIList');
	    qty = document.getElementById('dIQty').value;
		var intRegex = /^\d+$/;
		if(document.getElementById('dIProduct').selectedIndex > 0 && qty.value != '' && intRegex.test(qty))
		{ 
		    if(document.getElementById('dIRefNum').value == '') //Reference number
		    {
					var rNum = Math.floor(Math.random()*(999999999)+1); //Generate a random number betweeen 1 - 9 nines
		    		var rData = 'rNum='+ rNum;
					$.ajax({
						url: 'dIProcess.php',
						type: 'POST',
						dataType: 'json',
						data: rData

					}).done(function(data){
						if(data.match == false)
						{
							document.getElementById('dIRefNum').value = rNum;
						}
						else
						{
							$('#dIAdd').click();
						}
					});
			}
		}

			var check = true;
			$("#dIList option").each(function(index){
				if(index != 0)
				{
					var $this = $(this);
					if(document.getElementById('dIProduct').value == $this.val())
					{
						var number = $this.text().substr(0, $this.text().indexOf(' ')); //Number 
					    var text = (parseInt(number) + parseInt(qty)) + " " + $this.text().substr($this.text().indexOf(' ')+1); //Sum qtys + Rest of the product
					    $this.text(text); // Assing the text to this option
						check = false; // Not add same product
					}
				}
			});
			if(check == true)
			{
				var opt = document.createElement('option');
			    opt.value = document.getElementById('dIProduct').value;
			    opt.innerHTML = qty + " x " + document.getElementById('dIProduct').options[document.getElementById('dIProduct').selectedIndex].innerHTML;
			    select.appendChild(opt);
			}
	});

	$('#dIRL').click(function(){ //Delecte product selected
		if(document.getElementById('dIList').selectedIndex != 0)
		{
			$("#dIList option:selected").remove();
		}
	});

	$('#dISend').click(function(){ //Store the delivery
		if(document.getElementById('dIList').length > 1)
		{
			if(document.getElementById('dIPLoc').selectedIndex > 1)
			{
				if(document.getElementById('dITime').value != '')
				{
					var options = {};
					$('#dIList option').each(function(i){
						if(i > 0) //Jump the header of the select
						{
					    	options[$(this).val()] = $(this).text().substr(0, $(this).text().indexOf(' '));
						}
					});
					var delivData = 
					{
						storeId : $('#dIPLoc').val(),
						refNum : $('#dIRefNum').val(),
						date : $('#dITime').val(),
						note : $('#dIComment').val(),
						products : options
					};

					$.ajax({
						url: 'dIProcess.php',
						type: 'POST',
						dataType: 'json',
						data: delivData

					}).done(function(data){
						if(data.success == false)
						{
							alert('Error with data in this delivery!');
						}
						else
						{
							document.getElementById('dIProduct').options.length = 1;
							document.getElementById('dIPLoc').selectedIndex = '0';			
							document.getElementById('dITotal').value = '';
							document.getElementById('dIQty').value = '';
							document.getElementById('dIPrice').value = '';
							document.getElementById('dIRefNum').value = '';
							document.getElementById('dITime').value = '';
							document.getElementById('dIComment').value = '';
							document.getElementById("dIList").options.length = 1;
							alert('Delivery Complete');
						}

					}).fail(function(error){

					});
				}
				else
				{
					alert('Pick the Date of the Delivery!');
				}
			}
			else
			{
				alert('Pick a Location!');
			}
		}
		else
		{
			alert('Add products to the list!');
		}
	});

	$('#dIPLoc').change(function(){ //Location change, display items from there
		var idLoc = $(this).val();
		var locData = 'idLoc=' + idLoc;
		$.ajax
		({
			url: 'dIProcess.php',
			type: 'POST',
			cache: false,
			data: locData,
			success: function(html)
			{
				document.getElementById("dIList").options.length = 1;
				document.getElementById('dISQty').innerHTML = '';
                document.getElementById("dIPrice").value = '';
                document.getElementById("dIQty").value = '';
				document.getElementById("dITotal").value = '';
				$("#dIProduct").html(html);
			}
		}).done(function(data){
				if(data.success == false)
				{
					alert('Error: Fetching product data!');
				}
		});
	});

	$("#dIProduct").change(function(){ //When product selected display cost and stock. Warning if below 5
		var id=$(this).val();
		var storeToDel = document.getElementById('dIPLoc').value;
		var dataVal =
		{
			dIPID: id,
			dILoc: storeToDel,
		}; 

		$.ajax({
			url: 'dIProcess.php',
			type: 'POST',
			dataType: 'json',
			data: dataVal,

		}).done(function(data){
			if(data.success == false)
			{
				alert('Error while loading product data!');
			}
			else
			{
				document.getElementById("dIPrice").value = parseFloat(Math.round(data.price * 100) / 100).toFixed(2);
				document.getElementById("dIQty").value = '';
				document.getElementById("dITotal").value = '';
				// stockItem = data.qty;
				// document.getElementById('dISQty').innerHTML = 'In stock: ' + stockItem;
				// if(stockItem <= 5)
				// {
				// 	alert('There are: ' + stockItem + ' left of this item in stock!!!');
				// }
			}
		}).fail(function(error){
		});
	});

	$("#dIQty").on('input propertychange paste', function() { //Test of quantity is an integer number
		var qty = document.getElementById('dIQty').value;
		var intRegex = /^\d+$/;
    	if(document.getElementById('dIProduct').selectedIndex > 0 && document.getElementById('dIQty').value != '')
    	{
	    	if(intRegex.test(qty))
	    	{
					var total = document.getElementById('dIQty').value * document.getElementById('dIPrice').value;
					document.getElementById('dITotal').value ='$' + parseFloat(Math.round(total * 100) / 100).toFixed(2);
			}
			else
			{
				document.getElementById('dITotal').value = '$0.00';
			}
		}
		else
		{
			document.getElementById('dITotal').value = '';
		}
	});
});