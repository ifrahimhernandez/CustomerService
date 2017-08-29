<?php
	include '\reportcreator\Ejemplo_DOMPDF\dompdf\dompdf_config.inc.php';
	include 'connection.php';

	// $reportID = 'dailyRep';
	// $reportFormat = 'PDF';
	// $reportLocID = 269;
	// $reportLocation = 'Townhall';
	// $reportName = 'IDK';
	// $reportDateFrom = '1/1/2015';
	// $reportDateTo = '12/12/2015';
	
	$reportID = $_GET['repId'];
	$reportFormat = $_GET['repFormat'];
	$reportLocID = $_GET['repLocId'];
	$reportLocation =$_GET['repLocName'];
	$reportName = $_GET['repName'];
	$reportDateFrom = $_GET['repFrom'];
	$reportDateTo = $_GET['repTo'];

	$reportID = stripslashes($reportID);
	$reportFormat = stripslashes($reportFormat);
	$reportLocID = stripslashes($reportLocID);
	$reportLocation = stripslashes($reportLocation);
	$reportName = stripslashes($reportName);
	$reportDateFrom = stripslashes($reportDateFrom);
	$reportDateTo = stripslashes($reportDateTo);
	$reportID = mysql_real_escape_string($reportID);
	$reportFormat = mysql_real_escape_string($reportFormat);
	$reportLocID = mysql_real_escape_string($reportLocID);
	$reportLocation = mysql_real_escape_string($reportLocation);
	$reportName = mysql_real_escape_string($reportName);
	$reportDateFrom = mysql_real_escape_string($reportDateFrom);
	$reportDateTo = mysql_real_escape_string($reportDateTo);

	date_default_timezone_set('America/Los_Angeles');

	$HTMLCode ='
	<!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="=Content-Type" content="text/html" charset="utf-8" />
		<title>Reports</title>
	</head>
	<body>
		<h2 style="text-align:center; margin:0;">Location: '.$reportLocation.'</h2> 
		<h4 style="text-align:center; margin:0;">Report: '.$reportName.'</h4> 
		<h4 style="text-align:center; margin:0;">From: '.$reportDateFrom.'</h4> 
		<h4 style="text-align:center; margin:0;">To: '.$reportDateTo.'</h4> 
		<h5 style="text-align:center; margin-top:0;">Printed: '.date("l, M-d-Y").'</h5>

		<table width="100%" border="1" cellspacing="0" cellpadding="0">
		<tr>';

	if(@$reportID == 'invRep')//Inventory
	{
		$HTMLCode .=
		'<td colspan="3" style="text-align:center;"><strong>Information</strong></td>
		</tr>
		<tr>
			<td style="text-align:center;"><strong>Name</strong></td>
			<td style="text-align:center;"><strong>Quantity</strong></td>
			<td style="text-align:center;"><strong>Price</strong></td>
		</tr>';

		$stmt = $conn->prepare("Select p.productName, lp.productPrice, lp.productQTY From product p, location_product lp Where lp.productId = p.productId And lp.productLocation = $reportLocID And lp.active = '1'");																						
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row) 
	    { 
			$HTMLCode .=
			'<tr>
				<td><strong>&nbsp;&nbsp;'.$row['productName'].'</strong></td>
				<td><strong>&nbsp;&nbsp;$'.$row['productPrice'].'</strong></td>
				<td><strong>&nbsp;&nbsp;'.$row['productQTY'].'</strong></td>
			</tr>';
		}
	}

	if(@$reportID == 'dailyRep')//Daily sold item
	{
		$HTMLCode .=
		'<td colspan="6" style="text-align:center;"><strong>Information</strong></td>
		</tr>
		<tr>
			<td style="text-align:center;"><strong>Name</strong></td>
			<td style="text-align:center;"><strong>Date</strong></td>
			<td style="text-align:center;"><strong>Name</strong></td>
			<td style="text-align:center;"><strong>Price</strong></td>
			<td style="text-align:center;"><strong>Starting</strong></td>
			<td style="text-align:center;"><strong>Ending</strong></td>
		</tr>';

		$empID = '';
		$productId = '';
		$equalDate = '';
		$recordNumber = '';
		$boolVal = false;
		$checking = true;

		$stmt = $conn->prepare("Select u.name, u.lastname, ep.dateModificated, ep.mainId, ep.dateModificated, ep.empId, lp.productPrice, ep.proId, p.productName, ep.proQty, ep.proInOut From user u, employee_product ep, product p, location_product lp Where ep.empId = u.empId AND lp.productId = p.productId AND ep.proId = lp.productId And (ep.dateModificated BETWEEN '$reportDateFrom' AND '$reportDateTo') AND ep.locationId = $reportLocID And lp.productLocation = ep.locationId AND ep.readyOrNot ='1' ORDER BY empId Asc, dateModificated Asc, proId Asc, proQty Desc");																						
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row) 
	    { 
	    	if($boolVal == false)
	    	{
	    		$empID = $row['empId'];
				$productId = $row['proId'];
				$equalDate = $row['dateModificated'];
				$mainID = $row['mainId'];
				$identify = $row['proInOut'];
			}

			if($row['proInOut'] == 0)
    		{
    			if($checking == true)
				{
					$HTMLCode .='<tr>
	    			<td style="text-align:center;"><strong>'.$row['name'].' '.$row['lastname'].'</strong></td>
	    			<td style="text-align:center;"><strong>'.$row['dateModificated'].'</strong></td>
					<td style="text-align:center;"><strong>'.$row['productName'].'</strong></td>
	    			<td style="text-align:center;"><strong>$'.$row['productPrice'].'</strong></td>
	    			<td style="text-align:center;"><strong>'.$row['proQty'].'</strong></td>';
	    			$checking = false;
	    			$boolVal = true;
				}
				else
				{
					$HTMLCode .='<td style="text-align:center;"><strong>-</strong></td></tr>
					<tr>
					<td style="text-align:center;"><strong>'.$row['name'].' '.$row['lastname'].'</strong></td>
	    			<td style="text-align:center;"><strong>'.$row['dateModificated'].'</strong></td>
					<td style="text-align:center;"><strong>'.$row['productName'].'</strong></td>
	    			<td style="text-align:center;"><strong>$'.$row['productPrice'].'</strong></td>
	    			<td style="text-align:center;"><strong>'.$row['proQty'].'</strong></td>';
	    			$boolVal = false;
					$checking = false;
				}
    		}
    		else
    		{
    			if($empID == $row['empId'] && $productId == $row['proId'] && $equalDate == $row['dateModificated'] && $identify != $row['proInOut'])
				{
					$HTMLCode .='<td style="text-align:center;"><strong>'.$row['proQty'].'</strong></td></tr>';
					$boolVal = false;
					$checking = true;
				}
				else if($identify != $row['proInOut'])
				{
    				$HTMLCode .='<td style="text-align:center;"><strong>-</strong></td></tr>';
					$boolVal = false;
					$checking = true;
    			}
			}	
		}
	}

	if(@$reportID == 'deliRep')//Delivery
	{
		$HTMLCode .=
		'<td colspan="5" style="text-align:center;"><strong>Information</strong></td>
		</tr>
		<tr>
			<td style="text-align:center;"><strong>Delivery Num.</strong></td>
			<td style="text-align:center;"><strong>Product Name</strong></td>
			<td style="text-align:center;"><strong>Quantity</strong></td>
			<td style="text-align:center;"><strong>Damaged</strong></td>
			<td style="text-align:center;"><strong>Note</strong></td>
		</tr>';


		$stmt = $conn->prepare("Select dp.deliveryNumRef, p.productName, dp.productQty, dp.defect, dp.note From product p, delivery d, delivery_product dp Where p.productId = dp.productId And d.deliveryNumRef = dp.deliveryNumRef And d.locationId = $reportLocID And d.deliveryDate And (d.deliveryDate BETWEEN '$reportDateFrom' AND '$reportDateTo') ORDER BY d.deliveryNumRef");																						
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row) 
	    { 
			$HTMLCode .=
			'<tr>
				<td style="text-align:center;"><strong>'.$row['deliveryNumRef'].'</strong></td>
				<td style="text-align:center;"><strong>'.$row['productName'].'</strong></td>
				<td style="text-align:center;"><strong>'.$row['productQty'].'</strong></td>
				<td style="text-align:center;"><strong>'.$row['defect'].'</strong></td>
				<td style="text-align:center;"><strong>'.$row['note'].'</strong></td>
			</tr>';  
		}
	}

	if(@$reportID == 'saleSummary')// Sale Summary
	{
		$HTMLCode .=
		'<td colspan="7" style="text-align:center;"><strong>Information</strong></td>
		</tr>
		<tr>
			<td style="text-align:center;"><strong>Sale Id</strong></td>
			<td style="text-align:center;"><strong>Name</strong></td>
			<td style="text-align:center;"><strong>Quantity</strong></td>
			<td style="text-align:center;"><strong>Cost</strong></td>
			<td style="text-align:center;"><strong>Total</strong></td>
			<td style="text-align:center;"><strong>Promotion</strong></td>
			<td style="text-align:center;"><strong>Void/Refund</strong></td>
		</tr>';


		$stmt = $conn->prepare("Select ls.recordId, s.saleId, p.productName, lp.productPrice, ls.productQty, ls.void, ls.promo 
			From sales s, location_sale ls, product p, location_product lp 
			Where s.locationId = $reportLocID AND s.saleId = ls.idSale AND ls.productId = p.productId AND ls.productId = lp.productId AND lp.productLocation = $reportLocID AND (s.saleDate BETWEEN '$reportDateFrom' AND '$reportDateTo' +INTERVAL 1 DAY) ORDER BY s.saleId ASC");																						
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row) 
	    { 
			$HTMLCode .=
			'<tr>
				<td style="text-align:center;"><strong>'.$row['saleId'].'</strong></td>
				<td style="text-align:center;"><strong>'.$row['productName'].'</strong></td>
				<td style="text-align:center;"><strong>x'.$row['productQty'].'</strong></td>
				<td style="text-align:center;"><strong>$'.$row['productPrice'].'</strong></td>';
				$total = $row['productQty'] * $row['productPrice'];
			$HTMLCode .= '
				<td style="text-align:center;"><strong>$'.$total.'</strong></td>
				<td style="text-align:center;"><strong>'.$row['void'].'</strong></td>
				<td style="text-align:center;"><strong>'.$row['promo'].'</strong></td>
			</tr>';  
		}
	}

	if(@$reportID == 'shiftRep')// Shift 
	{
		$HTMLCode .=
		'<td colspan="11" style="text-align:center;"><strong>Information</strong></td>
		</tr>
		<tr>
			<td style="text-align:center;"><strong>Shift No.</strong></td>
			<td style="text-align:center;"><strong>Name</strong></td>
			<td style="text-align:center;"><strong>Start Petty</strong></td>
			<td style="text-align:center;"><strong>End Petty</strong></td>
			<td style="text-align:center;"><strong>Expenses</strong></td>
			<td style="text-align:center;"><strong>Amount Due</strong></td>
			<td style="text-align:center;"><strong>Bank Deposit</strong></td>
			<td style="text-align:center;"><strong>Credit Card</strong></td>
			<td style="text-align:center;"><strong>Over/Short</strong></td>
			<td style="text-align:center;"><strong>Start Note</strong></td>
			<td style="text-align:center;"><strong>End Note</strong></td>
		</tr>';


		$stmt = $conn->prepare("Select u.name, u.lastname, ss.NumberRecords, ss.pettyCashIn, ss.note, se.pettyCashOut, se.expenses, se.bankDeposit, se.creditSales, se.endNote, b.bankDrop, b.CompleteDeposit From user u, shift_end se, shift_start ss, bankdrop b Where ss.empId = u.empId And se.shiftStartId = ss.id And se.readyOrNot = '1' And ss.readyOrNot = '1' And ss.locationId = $reportLocID And se.locationId = $reportLocID And b.locationId = $reportLocID And (ss.startTime BETWEEN '$reportDateFrom' AND '$reportDateTo') And (b.time_executed BETWEEN '$reportDateFrom' AND '$reportDateTo')");																						
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row) 
	    { 
			$HTMLCode .=
			'<tr>
				<td style="text-align:center;"><strong>'.$row['NumberRecords'].'</strong></td>
				<td style="text-align:center;"><strong>'.$row['name'].' '.$row['lastname'].'</strong></td>
				<td style="text-align:center;"><strong>$'.$row['pettyCashIn'].'</strong></td>
				<td style="text-align:center;"><strong>$'.$row['pettyCashOut'].'</strong></td>
				<td style="text-align:center;"><strong>$'.$row['expenses'].'</strong></td>';
				if($row['bankDeposit'] == '0')
				{
					$HTMLCode .='<td style="text-align:center;"><strong>$'.$row['bankDrop'].'</strong></td>';
				}
				else
				{
					$HTMLCode .='<td style="text-align:center;"><strong>$0.00</strong></td>';
				}
				
			$HTMLCode .='
				<td style="text-align:center;"><strong>$'.$row['bankDeposit'].'</strong></td>
				<td style="text-align:center;"><strong>$'.$row['creditSales'].'</strong></td>';

			if($row['pettyCashIn'] < $row['pettyCashOut'])
			{
				$difference = $row['pettyCashOut'] - $row['pettyCashIn'];
				$HTMLCode .= '<td style="text-align:center;"><strong>Short $'.$difference.'</strong></td>';
			}
			if($row['pettyCashIn'] > $row['pettyCashOut'])
			{
				$difference = $row['pettyCashIn'] - $row['pettyCashOut'];
				$HTMLCode .= '<td style="text-align:center;"><strong>Over $'.$difference.'</strong></td>';
			}
			if($row['pettyCashIn'] == $row['pettyCashOut'])
			{
				$HTMLCode .= '<td style="text-align:center;"><strong>Even $'.$row['pettyCashIn'] .'</strong></td>';
			}

			$HTMLCode .= '
				<td style="text-align:center;"><strong>'.$row['note'].'</strong></td>
				<td style="text-align:center;"><strong>'.$row['endNote'].'</strong></td>
			</tr>';  
		}
	}

	if(@$reportID == 'shiEdiRep')//Shift Edit
	{
		$HTMLCode .=
		'<td colspan="6" style="text-align:center;"><strong>Petty Cash Change</strong></td>
		</tr>
		<tr>
			<td style="text-align:center;"><strong>Shift No.</strong></td>
			<td style="text-align:center;"><strong>Name</strong></td>
			<td style="text-align:center;"><strong>Start Petty</strong></td>
			<td style="text-align:center;"><strong>End Petty</strong></td>
			<td style="text-align:center;"><strong>Date</strong></td>
			<td style="text-align:center;"><strong></strong></td>
		</tr>';

		$stmt = $conn->prepare("Select scp.time_executed, scp.shift_number, scp.petty_in, scp.petty_out, u.name, u.lastname From user u, `supervisor _changes_pettycash` scp Where u.empId = scp.empId And scp.locationId = $reportLocID AND (scp.time_executed BETWEEN '$reportDateFrom' AND '$reportDateTo')");
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row) 
	    { 
	    	$HTMLCode .=
			'<tr>
				<td style="text-align:center;"><strong>'.$row['shift_number'].'</strong></td>
				<td style="text-align:center;"><strong>'.$row['name'].' '.$row['lastname'].'</strong></td>
				<td style="text-align:center;"><strong>$'.$row['petty_in'].'</strong></td>
				<td style="text-align:center;"><strong>$'.$row['petty_out'].'</strong></td>
				<td style="text-align:center;"><strong>'.$row['time_executed'].'</strong></td>
				<td style="text-align:center;"><strong> </strong></td>
			</tr>';  
		}

		$HTMLCode .=
		'<tr>
		<td colspan="6" style="text-align:center;"><strong>Shift Changes</strong></td>
		</tr>
		<tr>
			<td style="text-align:center;"><strong>Name</strong></td>
			<td style="text-align:center;"><strong>Expences</strong></td>
			<td style="text-align:center;"><strong>Bank Drop</strong></td>
			<td style="text-align:center;"><strong>Bank Deposito</strong></td>
			<td style="text-align:center;"><strong>Date</strong></td>
			<td style="text-align:center;"><strong>Credit Card</strong></td>
		</tr>';

		$stmt = $conn->prepare("Select sco.time_executed, sco.expences, sco.bank_deposit, sco.credit_sales, sco.bank_drop, u.name, u.lastname From user u, supervisor_changes_other sco Where u.empId = sco.empId And sco.locationId = $reportLocID AND (sco.time_executed BETWEEN '$reportDateFrom' AND '$reportDateTo')");
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row) 
	    { 
	    	$HTMLCode .=
			'<tr>
				<td style="text-align:center;"><strong>'.$row['name'].' '.$row['lastname'].'</strong></td>
				<td style="text-align:center;"><strong>'.$row['expences'].'</strong></td>
				<td style="text-align:center;"><strong>$'.$row['bank_drop'].'</strong></td>
				<td style="text-align:center;"><strong>$'.$row['bank_deposit'].'</strong></td>
				<td style="text-align:center;"><strong>'.$row['time_executed'].'</strong></td>
				<td style="text-align:center;"><strong>$'.$row['credit_sales'].'</strong></td>
			</tr>';  
		}

		$HTMLCode .=
		'<tr>
		<td colspan="6" style="text-align:center;"><strong>Product Changes</strong></td>
		</tr>
		<tr>
			<td style="text-align:center;"><strong>Shift No.</strong></td>
			<td style="text-align:center;"><strong>Name</strong></td>
			<td style="text-align:center;"><strong>Start Items</strong></td>
			<td style="text-align:center;"><strong>End Items</strong></td>
			<td style="text-align:center;"><strong>Date</strong></td>
			<td style="text-align:center;"><strong> </strong></td>
		</tr>';

		$stmt = $conn->prepare("Select time_executed, shift_number, product_name, pro_starting, pro_ending From supervisor_changes_products Where locationId = $reportLocID AND (time_executed BETWEEN '$reportDateFrom' AND '$reportDateTo')");
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row) 
	    { 
	    	$HTMLCode .=
			'<tr>
				<td style="text-align:center;"><strong>'.$row['shift_number'].'</strong></td>
				<td style="text-align:center;"><strong>'.$row['product_name'].'</strong></td>
				<td style="text-align:center;"><strong>'.$row['pro_starting'].'</strong></td>
				<td style="text-align:center;"><strong>'.$row['pro_ending'].'</strong></td>
				<td style="text-align:center;"><strong>'.$row['time_executed'].'</strong></td>
				<td style="text-align:center;"><strong> </strong></td>
			</tr>';  
		}

		$HTMLCode .=
		'<tr>
		<td colspan="6" style="text-align:center;"><strong>Damage Product</strong></td>
		</tr>
		<tr>
			<td style="text-align:center;"><strong>Name</strong></td>
			<td style="text-align:center;"><strong>Product Qty</strong></td>
			<td style="text-align:center;"><strong>Total</strong></td>
			<td style="text-align:center;"><strong>Note</strong></td>
			<td style="text-align:center;"><strong>Date</strong></td>
			<td style="text-align:center;"><strong> </strong></td>
		</tr>';

		$stmt = $conn->prepare("Select p.productName, proQtyDamaged, sdp.time_executed, sdp.proCost, sdp.note From product p, supervisor_damage_product sdp Where sdp.productId = p.productId And sdp.locationId = $reportLocID And (sdp.time_executed BETWEEN '$reportDateFrom' AND '$reportDateTo')");
		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row) 
	    { 
	    	$HTMLCode .=
			'<tr>
				<td style="text-align:center;"><strong>'.$row['productName'].'</strong></td>
				<td style="text-align:center;"><strong>'.$row['proQtyDamaged'].'</strong></td>
				<td style="text-align:center;"><strong>$'.$row['proCost'].'</strong></td>
				<td style="text-align:center;"><strong>'.$row['note'].'</strong></td>
				<td style="text-align:center;"><strong>'.$row['time_executed'].'</strong></td>
				<td style="text-align:center;"><strong> </strong></td>
			</tr>';  
		}

	}

	if(@$reportID == 'trainRep')//Train Report
	{
		$HTMLCode .=
		'<td colspan="4" style="text-align:center;"><strong>Information</strong></td>
		</tr>
		<tr>
			<td style="text-align:center;"><strong>Date</strong></td>
			<td style="text-align:center;"><strong>Quantity</strong></td>
			<td style="text-align:center;"><strong>Ticket Price</strong></td>
			<td style="text-align:center;"><strong>Day Total</strong></td>
		</tr>';
$reportDateFrom = date("Y-m-d", strtotime($reportDateFrom));
$reportDateTo = date("Y-m-d", strtotime($reportDateTo));

		$stmt = $conn->prepare("Select DATE_FORMAT(s.saleDate, '%Y-%m-%W') AS the_date, lp.productPrice, ls.productQty 
			FROM sales s, location_sale ls, location_product lp
			WHERE lp.productLocation = 269 AND lp.productId = '3' AND s.locationId = 269 And s.saleId = ls.idSale And ls.productId = '3' AND s.saleDate BETWEEN DATE_FORMAT('$reportDateFrom', '%Y-%m-%d') AND DATE_FORMAT('$reportDateTo', '%Y-%m-%d') +INTERVAL 1 DAY
			GROUP BY the_date");

		$stmt->execute();
		$result = $stmt->fetchAll();
		foreach($result as $row) 
	    { 
	    	$total = $row['productQty'] * $row['productPrice'];
			$HTMLCode .=
			'<tr>
				<td style="text-align:center;"><strong>'.$row['the_date'].'</strong></td>
				<td style="text-align:center;"><strong>'.$row['productQty'].'</strong></td>
				<td style="text-align:center;"><strong>$'.$row['productPrice'].'</strong></td>
				<td style="text-align:center;"><strong>$'.$total.'</strong></td>
			</tr>';  
		}
	}
	$HTMLCode.='</table></body></html>';

	if(@$reportFormat == "PDF")
	{
		$HTMLCode = utf8_encode($HTMLCode);
		$dompdf = new DOMPDF();
		$dompdf->load_html($HTMLCode);
		ini_set("memory_limit", "128M");
		$dompdf->render();
		$dompdf->stream($reportLocation.' '.$reportName.' '.$reportFormat.".pdf");
	}
	else if(@$reportFormat == "EXCEL")
	{
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=excelReport.xls");
		echo $HTMLCode;
	}	
	else if(@$reportFormat == "WORD")
	{
		header("Content-type:application/vnd.ms-word");
		header("Content-Disposition: attachment; filename=wordReport.doc");
		echo $HTMLCode;
	}
	
	//echo $HTMLCode;
	
?>