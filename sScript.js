$(document).ready(function(){
	var subtotal = 0.00;
	var promotions = 0.00;
	var total = 0.00;
	var vTotal = 0.00;
	var vPrice = 0.00; 

	$('#sCancel').click(function(){ //Clear
		document.getElementById('sPurchase').length = 1;
		document.getElementById('subTotal').innerHTML = 'Subtotal:'
		document.getElementById('sPromo').innerHTML = 'Promotions:';
		document.getElementById('sTotal').innerHTML = 'Total:';
		subtotal = 0.00;
		promotions = 0.00;
		total = 0.00;
	});

	jQuery(document).delegate( "#saleBtns button[type='button']", "click", //Button product clicked
	    function(e){
	    var inputId = this.value;
	    var pName = this.innerHTML;
	    var select = document.getElementById('sPurchase');
	    var intRegex = /^\d+$/;
	    if(intRegex.test(inputId)) //Verify if entered product have a valid Id
	    {
		    var sendData = 'inputId=' + inputId;
			$.ajax({
			url: 'sProcess.php',
			type: 'POST',
			dataType: 'json',
			data: sendData

			}).done(function(data){
				if(data.success == false)
				{
					alert('Something fail');
				}
				else
				{
					var check = true;
					$("#sPurchase option").each(function(index){
						if(index != 0)
						{
							var $this = $(this);
							if(inputId == $this.val())
							{
								var number = $this.text().substr(0, $this.text().indexOf(' ')); //Number 
								if(!isNaN(number))
								{
									if(data.qty - number > 0) //If the sum of the qty of the product is lest than 0 give alert
									{
									    var text = (parseInt(number) + 1) + " " + $this.text().substr($this.text().indexOf(' ')+1); //Sum qtys + Rest of the product
									    $this.text(text); // Assing the text to this option
									    //subtotal = parseFloat(document.getElementById('subTotal').innerHTML.substr(document.getElementById('subTotal').innerHTML.indexOf('$')+1, document.getElementById('subTotal').innerHTML.length)) + parseFloat(data.price); //Get the subtotal + product price
									    //document.getElementById('subTotal').innerHTML = 'Subtotal: $' + parseFloat(subtotal, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"); //Parse the result to currency format
									    subtotal = parseFloat(subtotal) + parseFloat(data.price);
										document.getElementById('subTotal').innerHTML = 'Subtotal: $' + parseFloat(subtotal, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"); //Parse the result to currency format
										total = parseFloat(total) + parseFloat(data.price);
										document.getElementById('sTotal').innerHTML = "Total: $" + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"); 
									}	
									else
									{
										alert('No more ' +pName+ ' left in the inventory!');
									}
									check = false; // Not add same product
								}
							}
						}
					});
					if(check == true)
					{
						if(data.qty - 1 >= 0) //If product is out of stock been the first added of its kind to the list give a alert
						{
					  		subtotal = parseFloat(subtotal) + parseFloat(data.price);
					    	document.getElementById('subTotal').innerHTML = 'Subtotal: $' + parseFloat(subtotal, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"); //Parse the result to currency format
							var opt = document.createElement('option');
						    opt.value = inputId;
						    opt.innerHTML = 1 + " x $" + parseFloat(data.price, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,") + "  " + pName;
						    select.appendChild(opt);
						    total = parseFloat(total) + parseFloat(data.price);
							document.getElementById('sTotal').innerHTML = "Total: $" + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
						}
						else
						{
							alert('This product is out of stocks!');
						}
					}

				}
			}).fail(function(error){});
		}
		else
		{
			alert('Only products Id allow!');
		}
	});

	$('#sPPromo').change(function(){ //Promo verification display
		if(document.getElementById('sPPromo').selectedIndex > 0)
		{
			var selectedPromo = $("#sPPromo option:selected").val();
			var promoData = 'promoID=' + selectedPromo;
			$.ajax({
			url: 'sProcess.php',
			type: 'POST',
			dataType: 'json',
			data: promoData
			}).done(function(data){
				if(data.success == true)
				{
					var number = 0;
					$("#sPurchase option").each(function(index){
						if(index != 0)
						{
							var $this = $(this);
							if($this.val() == data.pId)
							{
								if(!isNaN($this.text().substr(0, $this.text().indexOf(' '))))
								{
									number = $this.text().substr(0, $this.text().indexOf(' ')); //Number
								}
								else
								{
									number = number - data.pNe;
								}
							}
						}
					});
					document.getElementById('reqPromo').innerHTML = 'Required: ' + data.pNe;
					document.getElementById("reqPromo").style.color = "green";
					document.getElementById('inBag').innerHTML = 'In Bag: ' + number;
					document.getElementById("inBag").style.color = "red";
			
					//Add a fields that saids how much is left in the inventory with the total of the quantity in the bag + promos 
					//If the quantity left is not sufficient to add the promo message saying not posible no stocks available.    

					if(parseInt(number) >= parseInt(data.pNe))
					{
						document.getElementById("inBag").style.color = "green";
						$('#saleSPromo').prop('disabled', false);
					}
					else
					{
						$('#saleSPromo').prop('disabled', true);
					}
				}
				else
				{
					alert('Error in data verification!');
				}
			});
		}
	});

	$('#closePromo').click(function(){ //When promo close reset select
		document.getElementById('sPPromo').selectedIndex = 0;
		document.getElementById('reqPromo').innerHTML = '';
		document.getElementById('inBag').innerHTML = '';
		$('#saleSPromo').prop('disabled', true);
	});

	
	$('#saleSPromo').click(function(){ //Apply the promotion 
		if(document.getElementById('sPPromo').selectedIndex > 0)
		{
			var check = false;
			var selectedPromo = $("#sPPromo option:selected").val();
			var promoData = 'promoID=' + selectedPromo;
			$.ajax({
			url: 'sProcess.php',
			type: 'POST',
			dataType: 'json',
			data: promoData
			}).done(function(data){
				if(data.success == true)
				{
					$("#sPurchase option").each(function(index){
						if(index != 0)
						{
							var $this = $(this);
							if($this.val() == data.pId)
							{
								var number = $this.text().substr(0, $this.text().indexOf(' ')); //Number 

								if(number >= data.pNe) //Verify needed vs in bag
								{
									check = true;
								}
							}
						}
					});
					if(check == true)
					{
						var select = document.getElementById('sPurchase');
						var opt = document.createElement('option');
					    opt.value = data.pPr + '#' + data.promoID;/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					    opt.innerHTML = "Promotion x " + data.pNa;
					    select.appendChild(opt);
						promotions = parseFloat(promotions) + parseFloat(data.pVa);
						document.getElementById('sPromo').innerHTML = 'Promotions: $' + parseFloat(promotions, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"); //Parse the result to currency format
						document.getElementById('closePromo').click();
						subtotal = parseFloat(subtotal) + parseFloat(data.pVa);
						document.getElementById('subTotal').innerHTML = "Subtotal: $" + parseFloat(subtotal, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
						//document.getElementById("promos").innerHTML = document.getElementById("promos").innerHTML + "<br>" +$("#sPPromo option:selected").text();
					}
				}
				else
				{
					alert('Error while applying promotion!');
				}
			});
		}
	});

	$('#sPay').click(function(){ //Finish sale and pay
		if(document.getElementById('sPurchase').length > 1)
		{
			confirm("Are you sure to make the purchase?")
			{
				//Method of payment (???)			
				var	dSID = new Array();
				var dSQty = new Array();
				
				$("#sPurchase option").each(function(index){
					if(index != 0)
					{
						var pI = $(this);
						var number = pI.text().substr(0, pI.text().indexOf(' ')); //Number
						dSID[index-1] = pI.val();
						dSQty[index-1] = number;
					}
				});

				if(dSID.length == dSQty.length && subtotal > 0 && total >= 0)
				{
					var sendData = {
					sSub : subtotal,
					sPro : promotions,
					sTot : total,
					pIden: dSID,
					pQty : dSQty,
					};
				
					$.ajax({
					url: 'sProcess.php',
					type: 'POST',
					dataType: 'json',
					data: sendData
					}).done(function(data){
						if(data.success == true)
						{
							alert('Sale complete, thank you!');
							document.getElementById('sCancel').click();
							window.open('HtmlReceipt.php#' + data.tId, 'noimporta', 'width=300, height=300, scrollbars=NO');
						}
						else
						{
							alert('Error: Saving sale data!');
						}
					});
				}
				else
				{
					alert('Error: Data mismatch! Please cancel and do another sale!');
				}
			}
		}	
		else
		{
			alert('Add products to the bag!');
		} 		
	});

	$('#sRemove').click(function(){ //Remove an item prom the bag
		if(document.getElementById('sPurchase').selectedIndex > 0 && document.getElementById('sPurchase').length > 1)
		{
			var selectedOp = $("#sPurchase option:selected").val();
			var sendData = 'calPrice=' + selectedOp;
			
			$.ajax({
				url: 'sProcess.php',
				type: 'POST',
				dataType: 'json',
				data: sendData
			}).done(function(data){
			if(data.success == true)
			{
				var number = $("#sPurchase option:selected").text().substr(0, $("#sPurchase option:selected").text().indexOf(' '));
				if(!isNaN(number)) //Verify if its a number or a promo
				{
					subtotal = parseFloat(subtotal) - (parseInt(number) * data.price);
					total = parseFloat(total) - (parseInt(number) * data.price);
					document.getElementById('sTotal').innerHTML = 'Total: $' + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"); //Parse the result to currency format
				}
				else //A promo
				{
					subtotal = parseFloat(subtotal) - parseFloat(data.price);
					promotions = parseFloat(promotions) - parseFloat(data.price);
					document.getElementById('sPromo').innerHTML = 'Promotions: $' + parseFloat(promotions, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"); //Parse the result to currency format
				}
				document.getElementById('subTotal').innerHTML = "Subtotal: $" + parseFloat(subtotal, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
				$("#sPurchase option:selected").remove();
			}
			else
			{
				alert('Error removing this product from bag!');
			}
		});
			
		}
	});

	$('#voidSubmit').click(function(){ //Void menu display
		var intRegex = /^\d+$/;
		if($("#purchaseID").val() != '' && intRegex.test($("#purchaseID").val()))
		{
			var ID = $("#purchaseID").val();
			var sendData = 'IDPurchase=' + ID;
			
			$.ajax({
				url: 'sProcess.php',
				type: 'POST',
				cache: false,
				data: sendData,
				success: function(html)
				{
					if(html == 'false')
					{
						alert('No purchase found with this ID!');
						$('#voidItems').hide();
						$('#voidQuantity').hide();
						$('#voidRefund').hide();
						$('#voidAccept').hide();
					}
					else
					{
						$("#voidItems").html(html);
						$("#voidItems").show();
						$('#voidQuantity').hide();
						$('#voidRefund').hide();
						$('#voidAccept').hide();
					}
				} 
			});
		}
	});

	$('#voidItems').change(function(){
		$('#voidQuantity').show();
		$('#voidRefund').show();
		$('#voidQuantity').val('');
		$('#voidRefund').val('');
		$('#voidAccept').hide();
	});

	$("#voidQuantity").on('input propertychange paste', function(){ //Test of quantity is an integer number
		var qty = document.getElementById('voidQuantity').value;
		var intRegex = /^\d+$/;
		if(intRegex.test(qty) && qty != 0)
		{
			var selectedProId = $('#voidItems option:selected').attr('id'); //Selected ID
			var selectedName = $('#voidItems option:selected').val();
			var start_pos = selectedName.indexOf('(')+1;
			var end_pos = selectedName.indexOf(')',start_pos);
			var text_to_get = selectedName.substring(start_pos,end_pos); //Qty of product
			if(parseInt(text_to_get) >= parseInt(qty) || text_to_get == 'p' && qty == 1)
			{
				var sendData = {
					iID: selectedProId,
				};

				$.ajax({
						url: 'sProcess.php',
						type: 'POST',
						dataType: 'json',
						data: sendData
					}).done(function(data){
					if(data.success == true)
					{
						vTotal = 1.10*(data.pPrice*qty);
						vTotal = parseFloat(vTotal, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
						vPrice = data.pPrice * qty;
						$('#voidRefund').val('$'+parseFloat((1.10*(data.pPrice*qty)), 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"));
						$('#voidAccept').prop('disabled', false);
						$('#voidAccept').show();
					}
					else
					{
						alert('Error: Getting item data!');
					}

				});
			}
			else
			{
				$('#voidAccept').prop('disabled', true);
			}
		}
		else
		{
			$('#voidRefund').val('$0');
			$('#voidAccept').prop('disabled', true);
		}
	});
	
	$('#voidAccept').click(function(){
		confirm("Are you sure to void these items?")
		{
			var salesID = $('#voidItems option:first').attr('id'); //Sale ID
			var qty = document.getElementById('voidQuantity').value;
			var selectedProId = $('#voidItems option:selected').attr('id'); //Selected ID
			
			var selectedName = $('#voidItems option:selected').val();
			var start_pos = selectedName.indexOf('(')+1;
			var end_pos = selectedName.indexOf(')',start_pos);
			var text_to_get = selectedName.substring(start_pos,end_pos); //Qty of product
			
			if(text_to_get == 'p')
			{
				qty = 'p';
			}

			var sendData = {
				voidPID: selectedProId,
				voidSID: salesID,
				voidQTY: qty,
				voidTot: vTotal,
				voidPPri: vPrice,
			};

			$.ajax({
					url: 'sProcess.php',
					type: 'POST',
					dataType: 'json',
					data: sendData
				}).done(function(data){
				if(data.success == true)
				{
					$('#voidItems').hide();
					$('#voidQuantity').hide();
					$('#voidRefund').hide();
					$('#voidAccept').hide();
					alert("Refund: $" + parseFloat(vTotal, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,") + " to the client");
				}
				else
				{
					alert('Error: Void cannot be completed!');
				}
			});
		}
	});

});