$(document).ready(function(){ //Add promo

	$('#promoCancel').click(function(){ //Clear
		document.getElementById('mPromoName').value = "";
		document.getElementById('mPromoPNeed').selectedIndex = 0;
		document.getElementById('mPromoQtyPro').value = "";
		document.getElementById('mPromoInProm').selectedIndex = 0;
		document.getElementById('mPromoLocAssign').selectedIndex = 0;
		document.getElementById('mPromoLocation').selectedIndex = 0;
		document.getElementById('promoAtLocation').options.length = 0;
	});

	$('#promoAdd').click(function(){ // Add a new promo
		var vali = true;
		if($('#mPromoName').val() != '' && $('#mPromoQtyPro').val() != '')
		{
			if(document.getElementById('mPromoInProm').selectedIndex != 0 && document.getElementById('mPromoPNeed').selectedIndex != 0 )
			{
				if(document.getElementById('mPromoLocAssign').selectedIndex != 0)
				{
					var promoData = 
					{
						promoAdd : "1",
						promoName : $('#mPromoName').val(),
						promoProNeeded: $('#mPromoPNeed').val(),
						promoQtyNeeded: $('#mPromoQtyPro').val(),
						promoInPromo: $('#mPromoInProm').val(),
						promoLocalize: $('#mPromoLocAssign').val(),
					};
					$.ajax
					({
						url: 'promoMProcess.php',
						type: 'POST',
						dataType: 'json',
						data: promoData,
					 }).done(function(data){

						if(data.success == false)
						{
							if(data.errors.exp)
							{
								alert(data.errors.exp);
							}
						}
						else
						{
							vali = false;
							alert(data.message);
							document.getElementById("promoCancel").click();
						}
					}).fail(function(error){
					});
				}
			}
		}
		if(vali)
		{
			alert('All inputs are requiered!');
		}
	});

	// $('#promoApply').click(function(){ //Assing a promotion to location
	// 	if(document.getElementById('mPromoLocAssign').selectedIndex != '0' && document.getElementById('mPromoAdded').selectedIndex != '0')
	// 	{	
	// 		var promoAData = 
	// 		{
	// 			promoAssign : "1",
	// 			promoLocation : $('#mPromoLocAssign').val(),
	// 			promoId : $('#mPromoAdded').val(),
	// 		};
	// 		$.ajax
	// 		({
	// 			url: 'promoMProcess.php',
	// 			type: 'POST',
	// 			dataType: 'json',
	// 			data: promoAData,
	// 		 }).done(function(data){

	// 			if(data.success == false)
	// 			{

	// 			}
	// 			else
	// 			{
	// 				alert(data.message);
	// 				document.getElementById("promoCancel").click();
	// 			}
	// 		}).fail(function(error){
	// 		});
	// 	}
	// 	else
	// 	{
	// 		alert('Please select an available promotion and a store to apply it!');
	// 	}
	// });

	// $("#promoRemove").click(function(){ //Remove promotion at selected location
	// 	if(document.getElementById('mPromoLocation').value != '' && document.getElementById('promoAtLocation').value != '')
	// 	{
	// 		var promoRData = 
	// 		{
	// 			promoRemove : "1",
	// 			promoLocation : $('#mPromoLocation').val(),
	// 			promoId : $('#promoAtLocation').val(),
	// 		};
	// 		$.ajax
	// 		({
	// 			url: 'promoMProcess.php',
	// 			type: 'POST',
	// 			dataType: 'json',
	// 			data: promoRData,
	// 		 }).done(function(data){

	// 			if(data.success == false)
	// 			{
	// 				alert('Error while removing the promotion!');
	// 			}
	// 			else
	// 			{
	// 				alert(data.message);
	// 				document.getElementById("promoCancel").click();
	// 			}
	// 		}).fail(function(error){
	// 		});
	// 	}
	// 	else
	// 	{
	// 		alert('Please select a Location and a the Promotion to remove!');
	// 	}
	// });

	$("#promoRemove").click(function(){ //Remove selected promotion
		if($('#promoAtLocation').val() !='' && $('#mPromoLocation').val() != ''){
			if (confirm("Are you sure you want to delete this promotion?") == true){
				var promoDData = 
				{
					deleteId : $('#promoAtLocation').val(),
					promoLocation : $('#mPromoLocation').val(),
				};
				
				$.ajax
				({
					url: 'promoMProcess.php',
					type: 'POST',
					dataType: 'json',
					data: promoDData,
				 }).done(function(data){
					if(data.success == false)
					{
						alert('Error: While deleting promotion!');
					}
					else
					{
						alert('Promotion deleted!');
						document.getElementById('promoCancel').click();
					}
				}).fail(function(error){
				});
			}	
		}
	});

	$("#mPromoLocation").change(function(){ //When a location is selected displays promo from that location
	var dataString = 'locId='+ $(this).val();
		$.ajax
		({
			url: "promoMProcess.php",
			type: "POST",
			cache: false,
			data: dataString,
			success: function(html)
			{
				$("#promoAtLocation").html(html);
			} 
		});
	});

		$("#promoAtLocation").change(function(){ //Display selected promo info
			var promoDisplayData = 
			{
				promoSelectedId : $(this).val(),
				promoLocation : $('#mPromoLocation').val(),
			};

			$.ajax
			({
				url: 'promoMProcess.php',
				type: 'POST',
				dataType: 'json',
				data: promoDisplayData,
			 }).done(function(data){
				if(data.success == false)
				{
					alert('Error: While loading data!');
				}
				else
				{
					document.getElementById('mPromoName').value = data.promotionName;
					document.getElementById('mPromoPNeed').value = data.productId;
					document.getElementById('mPromoQtyPro').value = data.neededQty;
					document.getElementById('mPromoInProm').value = data.productPromotion;
					document.getElementById('mPromoLocAssign').value = $('#mPromoLocation').val();
				}
			}).fail(function(error){
			});
		});
});