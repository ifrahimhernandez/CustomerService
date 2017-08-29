$(document).ready(function(){

	$('#proCancel').click(function(){ //Clear all fields
		document.getElementById('mPName').value = "";
		document.getElementById('mPPrice').value = "";
		document.getElementById('mPAvailable').selectedIndex = "-1";
		document.getElementById('mPLocation').selectedIndex = "0";
		$("#mPLocation").prop('disabled', false);
		document.getElementById('mpNotAvailable').selectedIndex = -1;
		$('#mPName').prop('disabled', false);
		$('#proEdit').prop('disabled', false);
		$('#proDelete').prop('disabled', false);
		// document.getElementById('mPQTY').value = "";
	});

	$('#proDelete').click(function(){ //Delete Selected Product
		if(document.getElementById('mPAvailable').value != '') //Verify if product is selected
		{
			var productName = document.getElementById('mPAvailable').options[document.getElementById('mPAvailable').selectedIndex].innerHTML;
			if (confirm("Are you sure do you want to delete: " + productName + "?") == true) 
			{
		        var productId = document.getElementById('mPAvailable').value;
				var prodData = 'productId=' + productId; 
		        $.ajax
				({
					url: 'pMProcess.php',
					type: 'POST',
					dataType: 'json',
					data: prodData
				}).done(function(data){
					if(data.success == false)
					{
						alert('Error: Deleting product');
					}
					else
					{
						alert('Product: '+ productName +' deleted!');
						document.getElementById("proCancel").click();
						//Clear boxes and refresh select list
						var dataString = 'refreshProducts=true';
						$.ajax
						({
							url: "pMProcess.php",
							type: "POST",
							cache: false,
							data: dataString,
							success: function(html)
							{
								$("#mPAvailable").html(html);
							} 
						});
					}
				}).fail(function(error){
				});
		   }
	   }
	   else
	   	{alert('Please select a product to delete!');}
	});

	$('#proEdit').click(function(){ //Edit Product
		if(document.getElementById('mPAvailable').value != '') //Verify if product is selected
		{
			var productEName = document.getElementById('mPAvailable').options[document.getElementById('mPAvailable').selectedIndex].innerHTML;
			if (confirm("Are you sure do you want to update: " + productEName + "?") == true) 
			{
				var proEData = 
				{
					proEId : $('#mPAvailable').val(),
					proEName : $('#mPName').val(),
					proEPrice : $('#mPPrice').val(),
					// proEQty : $('#mPQTY').val(),
					proELoca : $('#mPLocation').val()
				};
				$.ajax
				({
					url: 'pMProcess.php',
					type: 'POST',
					dataType: 'json',
					data: proEData
				}).done(function(data){
				if(data.success == false)
				{
					alert("Error: While updating data!");
				}
				else
				{
					alert("Product updated!");
					document.getElementById("proCancel").click();
				}
				}).fail(function(error){

				});
			}
			else
			{
				document.getElementById("proCancel").click();
			}
		}
		else
		{
			alert('Select a product to edit!');
		}
	});

	$('#proAdd').click(function(){ //Add Product
		if(document.getElementById('mPName').value != '' && document.getElementById('mPPrice').value != '' && !isNaN(document.getElementById('mPPrice').value) && document.getElementById('mPLocation').selectedIndex != 0)
		{
			if (document.getElementById('mpNotAvailable').selectedIndex != -1)
			{
				var proAData = 
				{
					proAPrice : $('#mPPrice').val(),
					proALoc : $('#mPLocation').val(),
					proAddID : $('#mpNotAvailable').val(),
				};
			}
			else
			{
				var proAData = 
				{
					proAName : $('#mPName').val(),
					proAPrice : $('#mPPrice').val(),
					proALoc : $('#mPLocation').val(),
				};
			}

			$.ajax
			({
				url: 'pMProcess.php',
				type: 'POST',
				dataType: 'json',
				data: proAData,
			 }).done(function(data){
				if(data.success == false)
				{
					alert(data.err);
					alert("Error: Product may exist in that store!");
				}
				else
				{
					alert("Product: " + document.getElementById('mPName').value + " added to the inventory!");
					// document.getElementById("proCancel").click();
					var dataString = 'refreshProducts=true';
					$.ajax
					({
						url: "pMProcess.php",
						type: "POST",
						cache: false,
						data: dataString,
						success: function(html)
						{
							$("#mPAvailable").html(html);
						} 
					});

				}
			}).fail(function(error){
			});
		}
		else
		{
			alert('Please give a proper name, price and pick a location to this product!');
		}
	});
	
	$('#mPAt').change(function(){ //Select store displays its products
		document.getElementById("proCancel").click();
				var pNAt = $(this).val();
				var pAtDate = 'pNAt=' + pNAt;
		$.ajax
		({
			url: "pMProcess.php",
			type: "POST",
			cache: false,
			data: pAtDate,
			success: function(html)
			{
				$("#mpNotAvailable").html(html);
			} 
		});

		var pAt = $(this).val();
		var pAtDate = 'pAt=' + pAt;
		$.ajax
		({
			url: "pMProcess.php",
			type: "POST",
			cache: false,
			data: pAtDate,
			success: function(html)
			{
				$("#mPAvailable").html(html);
			} 
		});
	});

	$('#mpNotAvailable').change(function(){ //Select product and display info in textbox
		document.getElementById('mPName').value = mpNotAvailable.options[mpNotAvailable.selectedIndex].text;
		$('#mPName').prop('disabled', true);
		$('#proEdit').prop('disabled', true);
		$('#proDelete').prop('disabled', true);
		document.getElementById('mPAvailable').selectedIndex = -1;
		$("#mPLocation").prop('disabled', false);
	});

	$('#mPAvailable').change(function(){ //Select product and display info in textbox
		document.getElementById('mpNotAvailable').selectedIndex = -1;
		$('#mPName').prop('disabled', false);
		$('#proEdit').prop('disabled', false);
		$('#proDelete').prop('disabled', false);
		var proId = $(this).val();
		var proIData = 
		{
			proId : proId,
			proSt : $('#mPAt').val(),
		}
		$.ajax
		({
			url: 'pMProcess.php',
			type: 'POST',
			dataType: 'json',
			data: proIData
		}).done(function(data){
			if(data.success == false)
			{
				alert("Error: While fetching data!");
			}
			else
			{
				if(data.pName == undefined)
				{
					document.getElementById('mPName').value = '';
				}
				else
				{
					document.getElementById('mPName').value = data.pName;
				}
				if(data.pPrice == undefined)
				{
					document.getElementById('mPPrice').value = '';
				}
				else
				{
					document.getElementById('mPPrice').value = data.pPrice;
				}
				if(data.pLocation == undefined)
				{
					document.getElementById('mPLocation').selectedIndex = 0;
				}
				else
				{
					document.getElementById('mPLocation').value = data.pLocation;
				}
				// if(data.pQty == undefined)
				// {
				// 	document.getElementById('mPQTY').value = '';
				// }
				// else
				// {
				// 	document.getElementById('mPQTY').value = data.pQty;
				// }
				$("#mPLocation").prop('disabled', true);
			}
		}).fail(function(error){
		});
	});
});