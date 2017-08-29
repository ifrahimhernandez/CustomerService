$(document).ready(function(){ //Add employees
	
	$('#empCancel').click(function(){ //Clear all fields
	    document.getElementById('mName').value = "";
	    document.getElementById('mLastname').value = "";
	    document.getElementById('mQuestion').selectedIndex = "0";
	    document.getElementById('mAnswer').value = "";
	    document.getElementById("mLevel").selectedIndex = "0";
	    document.getElementById('mUsername').value = "";
	    document.getElementById('mPassword').value = "";
	    document.getElementById("mELocation").selectedIndex = "0";
	    document.getElementById('employeesAtStores').options.length = 0;
	    document.getElementById('storesLocation').selectedIndex = '0';
	  	$("#mLevel").change();
	 });

	$("#mLevel").change(function(){ //Employee privilege change everything multi location clear
		$('#mLevel').selectedIndex = 0;
		$('.comment').val("");
		$('.comment').attr('placeholder', "Location Note....");
	    $('.comment').attr('id', '');
	   	$('.mcomment').val("");
	   	$('.mcomment').attr('id', '');
	   	$('.mcomment').hide();
	   	$('.mLocation').selectedIndex = 0;
	   	$('.mLocation').attr('id', '');
	   	$('.mLocation').hide();
	   	$('#mELocation').attr('disabled',false);
	   	$('.mLocation').attr('disabled',false);
	});

	$('#empDelete').click(function(){ //Delete emp
		if(document.getElementById('storesLocation').value != '' && document.getElementById('employeesAtStores').value != '')
		{
			if (confirm("Remove this employee from: "+$('#storesLocation option:selected').text()+"?") == true)
			{
				var employeeId = document.getElementById('employeesAtStores').value;
				var locationId = document.getElementById('storesLocation').value;
				
				var empDData = 
				{
					empId : employeeId,
					locId : locationId,
					delEmp : "1",
				};
		        $.ajax
				({
					url: 'eMProcess.php',
					type: 'POST',
					dataType: 'json',
					data: empDData
				}).done(function(data){
					if(data.success == false)
					{
						alert(data.errors.exp);
					}
					else
					{
						alert(data.message);
						document.getElementById("empCancel").click();
					}
				}).fail(function(error){
				 });
			}
		}
		else
		{
			alert('Please select a Store then an Employee to delete');
		}
	});
	
	$('#empEdit').click(function(){ //Edit employees
		if (confirm("Update this employee?") == true)
		{
			var empLocNote = {};
			$('#testings > select:visible').each(function(){
				if($(this).val() != null) //If the value is not null, like the last one
				{
					empLocNote[$(this).val()] = $(this).next().val();
				}
			});
			var updData = 
			{
				emId : $('#employeesAtStores').val(),
				name : $('#mName').val(),
				last : $('#mLastname').val(),
				ques : $('#mQuestion').val(),
				answ : $('#mAnswer').val(),
				leve : $('#mLevel').val(),
				user : $('#mUsername').val(),
				pass : $('#mPassword').val(),
				loca : $('#mELocation').val(),
				note : $('.comment').val(),
				mult : empLocNote,
				eUpd : '1',
			};
	        $.ajax
			({
				url: 'eMProcess.php',
				type: 'POST',
				dataType: 'json',
				data: updData
			}).done(function(data){
				if(data.success == false)
				{
					alert(data.errors.exp);
				}
				else
				{
					alert(data.message);
					document.getElementById("empCancel").click();
				}
			}).fail(function(error){
			 });
		}
	});

	$('#empAdd').click(function(){ //Create a new employee
		var locQty = $("#testings > select:visible").length;
		var empLocNote = {};
		var formData;
		if(locQty == 1) //If the employee only have a single location do this ... else if (multiple) do arrays
		{
		 	formData = {
				name : $('#mName').val(),
				last : $('#mLastname').val(),
				ques : $('#mQuestion').val(),
				answ : $('#mAnswer').val(),
				leve : $('#mLevel').val(),
				user : $('#mUsername').val(),
				pass : $('#mPassword').val(),
				loca : $('#mELocation').val(),
				note : $('.comment').val(),
				eAdd : "1",
			};
		}
		else if($('#mLevel').val() == 2 && locQty > 1 && locQty <= 5) //If multi locations are selected
		{
			$('#testings > select:visible').each(function(){
				if($(this).val() != null) //If the value is not null, like the last one
				{
					empLocNote[$(this).val()] = $(this).next().val();
				}
			});

			formData = {
				name : $('#mName').val(),
				last : $('#mLastname').val(),
				ques : $('#mQuestion').val(),
				answ : $('#mAnswer').val(),
				leve : $('#mLevel').val(),
				user : $('#mUsername').val(),
				pass : $('#mPassword').val(),
				locNote: empLocNote,
				eAdd : "2",
			};
		}

		$.ajax
		({
			url: 'eMProcess.php',
			type: 'POST',
			dataType: 'json',
			data: formData
		}).done(function(data){
			if(data.success == false)
			{
				if(data.errors.name)
				{
					alert(data.errors.name);
				}
				if(data.errors.username)
				{
					alert(data.errors.username);
				}
				if(data.errors.exp)
				{
					alert(data.errors.exp);
				}
			}
			else
			{
				alert(data.message); //Success message and clear textboxes
				document.getElementById("empCancel").click();
			}

		}).fail(function(error){
			alert('error');
		});
	});

	var count;
	$('#testings > select').on('change', function(){ //Multiple location 
		count = $("#testings > select:visible").length
		var counter = $('#mLevel').val();
		if(counter == '2' && document.getElementById('mELocation').selectedIndex != '0' && count <= 5)
		{
    		$(this).nextAll('select').first().show();
    		$(this).nextAll('select').first().html($(this).find('option:not(:selected)').clone());
    		$(this).nextAll('textarea').first().show();
    		$(this).nextAll('textarea').first().attr('id', $(this).val());
    		$(this).nextAll('textarea').first().attr('placeholder', $(this).find(':selected').text() + " note...");
		}
	});

	$("#storesLocation").change(function(){ //When a location is selected displays employees from that location
		var id=$(this).val();
		var dataString = 'locChange='+ id;
		
		$.ajax
		({
			url: "eMProcess.php",
			type: "POST",
			cache: false,
			data: dataString,
			success: function(html)
			{
				$("#employeesAtStores").html(html);
			} 
		});

	});

	$('#employeesAtStores').change(function(){ //Select worker and display info in textbox
		var selectedEmp = $(this).val();
		var empData = 'selectedEmp=' + selectedEmp;
		$.ajax
		({
			url: 'eMProcess.php',
			type: 'POST',
			dataType: 'json',
			data: empData
		}).done(function(data){
			if(data.success == false)
			{
				if(data.exp)
				{
					alert(data.exp);
				}
			}
			else
			{
				$("#mLevel").change(); //Clear all fields in multi locations
				document.getElementById('mName').value = data.eName;
				document.getElementById('mLastname').value = data.eLastname;
				document.getElementById('mUsername').value = data.eUsername;
				document.getElementById('mPassword').value = data.ePassword;
				if(data.eQuest == '' || data.eQuest == null)
				{
					document.getElementById('mQuestion').selectedIndex = 0;
				}
				else
				{
					$('#mQuestion option:contains('+data.eQuest+')').attr('selected', 'selected');
				}
				document.getElementById('mAnswer').value = data.eAns;
				document.getElementById("mLevel").value = data.ePrivilege;
				var count = 0;
				var verify = false;
				$.each(data.info, function(index, value){
					if(verify == true && count <= 4) //Dont change lines of code order
					{
						$('.mLocation:eq('+count+')').val(index);
						$('.mLocation:eq('+count+')').attr('disabled',true);
						$('.mLocation:eq('+count+')').change();
						$('.mcomment:eq('+count+')').val(value);
						count++;
					}
					if(verify == false)
					{
						$('#mELocation').val(index);
						$('#mELocation').attr('disabled',true);
						$('#mELocation').change();
						$('.comment:eq(0)').val(value);
					 	verify = true;
					}
				});
			}

		}).fail(function(error){

		});
	});

});