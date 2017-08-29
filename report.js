$(document).ready(function(){

	$('#viewRepBtn').click(function(){
		var type = document.getElementById('reportType').value;
		var type2 = document.getElementById('reportType').selectedIndex.innerHTML;
		var store = document.getElementById('reportStore').value;
		var store2 = document.getElementById('reportStore').selectedIndex.innerHTML;
		alert(type + type2);
		alert(store + store2);

		});

	$('#reportType').change(function(){
		if(document.getElementById('reportType').selectedIndex != 0)
		{
			$('#viewRepBtn').show();
		}
	});

	$('#EXCEL').click(function(){
		if(document.getElementById('reportType').selectedIndex != 0 && document.getElementById('repFrom').value != "" && document.getElementById('repTo').value != "" && document.getElementById('reportStore').selectedIndex != 0)
		{
			var reportFormat = 'EXCEL';
			var reportLocID = document.getElementById('reportStore').value;
			var reportLocation = reportStore.options[reportStore.selectedIndex].text;
			var reportFrom = $('#repFrom').val();
			var reportTo = $('#repTo').val();
			var reportID = reportType.options[reportType.selectedIndex].id;
			var reportName = reportType.options[reportType.selectedIndex].text;

			window.location = "/customerservice/reportPDF.php?repFormat="+reportFormat+"&repLocId="+reportLocID+"&repLocName="+reportLocation+"&repFrom="+reportFrom+"&repTo="+reportTo+"&repId="+reportID+"&repName="+reportName+"";
		}
	});


	$('#PDF').click(function(){
		if(document.getElementById('reportType').selectedIndex != 0 && document.getElementById('repFrom').value != "" && document.getElementById('repTo').value != "" && document.getElementById('reportStore').selectedIndex != 0)
		{
			var reportFormat = 'PDF';
			var reportLocID = document.getElementById('reportStore').value;
			var reportLocation = reportStore.options[reportStore.selectedIndex].text;
			var reportFrom = $('#repFrom').val();
			var reportTo = $('#repTo').val();
			var reportID = reportType.options[reportType.selectedIndex].id;
			var reportName = reportType.options[reportType.selectedIndex].text;

			window.location = "/customerservice/reportPDF.php?repFormat="+reportFormat+"&repLocId="+reportLocID+"&repLocName="+reportLocation+"&repFrom="+reportFrom+"&repTo="+reportTo+"&repId="+reportID+"&repName="+reportName+"";
		}
	});

	$('#WORD').click(function(){
		if(document.getElementById('reportType').selectedIndex != 0 && document.getElementById('repFrom').value != "" && document.getElementById('repTo').value != "" && document.getElementById('reportStore').selectedIndex != 0)
		{
			var reportFormat = 'WORD';
			var reportLocID = document.getElementById('reportStore').value;
			var reportLocation = reportStore.options[reportStore.selectedIndex].text;
			var reportFrom = $('#repFrom').val();
			var reportTo = $('#repTo').val();
			var reportID = reportType.options[reportType.selectedIndex].id;
			var reportName = reportType.options[reportType.selectedIndex].text;

			window.location = "/customerservice/reportPDF.php?repFormat="+reportFormat+"&repLocId="+reportLocID+"&repLocName="+reportLocation+"&repFrom="+reportFrom+"&repTo="+reportTo+"&repId="+reportID+"&repName="+reportName+"";
		}
	});

});