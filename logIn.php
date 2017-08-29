<?php
	include 'connection.php';
	$error = ""; // Variable To Store Error Message
		if (isset($_POST['in'])) 
		{
				$username = $_POST['userNInput'];
				$password = $_POST['password'];
				$username = stripslashes($username);
				$password = stripslashes($password);
				$username = mysql_real_escape_string($username);
				$password = mysql_real_escape_string($password);

				try
				{
					$pass2word = '';
					for($i = 0; $i < strlen($password); $i++)
					{
						if(strlen(ord($password[$i])) == 1)
						{
							$pass2word .= '00'.(ord($password[$i])+33).'';
						}
						if(strlen(ord($password[$i])) == 2)
						{
							$pass2word .= '0'.(ord($password[$i])+33).'';
						}
						if(strlen(ord($password[$i])) == 3)
						{
							$pass2word .= (ord($password[$i])+33);
						}
					}

					// SQL query to fetch information of registerd users and finds user match.
					$sql = "Select empId, name, lastname, privilege from user where password=$pass2word AND username='$username'";
					$stmt = $conn->prepare($sql);
					$stmt->execute();
					$result = $stmt->fetchAll();

				    foreach($result as $row) 
				    { 
				    	$eId = $row['empId'];
				       	$eName = $row['name'];
				       	$eLastname = $row['lastname'];
				       	$ePrivilege = $row['privilege'];
				    }

					if(isset($eId))
					{
					    $sql2 = "Select lu.locationId, l.locationName From location_user lu, location l Where lu.empId = '$eId' AND l.locationId = lu.locationId AND lu.active = '1'";
						$stmt = $conn->prepare($sql2);
						$stmt->execute();
						$result2 = $stmt->fetchAll();
						$eLocation = array();
						$lName = array();
						$i = 0;
					    foreach($result2 as $row) 
					    { 
					    	$eLocation[$i] = $row['locationId'];
					    	$lName[$i] = $row['locationName'];
					    	$i++;
					    }
					    $_SESSION['eName']=$eName; // Initializing Session
						$_SESSION['eLastname']=$eLastname;
						$_SESSION['ePrivilege']=$ePrivilege;

						$_SESSION['eLoc'] = @$eLocation[0];
						$_SESSION['locationName'] = $lName[0];
						$_SESSION['locName'] = $lName[0];
						$_SESSION['eId'] = $eId;
						
						if(sizeof($eLocation) > 1)
						{
							$_SESSION['ePickLoc'] = 'data-toggle="modal" data-target="#myModal"';
							$_SESSION['btnLocDesappear'] = "";
						}
					}
					else
					{
						$error = 'Try again!';
					}
				}
				catch(PDOException $e)
				{
					echo "Error: " . $e->getMessage();
				}
		}

		if(isset($_POST['out']))
		{
			session_destroy();
			$validation = "required";
			$input = "";
			header('Location: mainPage.php');
			exit();
		}

	 	// Starting Session
		$validation = "required";
		$notLogged = "display: none";
		$input = "";

		if(@$_SESSION['ePrivilege'] == "1")
		{
			$notLogged = "";
			$logged = "display: none";
			//Admin
			$_SESSION['privShift'] = "";
			$_SESSION['sPill'] = "pill";
			$_SESSION['privInventory'] = "";
			$_SESSION['iPill'] = "pill";
			$_SESSION['privManagement'] = "";
			$_SESSION['mPill'] = "pill";
			$_SESSION['privSales'] = "";
			$_SESSION['slsPill'] = "pill";
			$_SESSION['privReport'] = "";
			$_SESSION['rPill'] = "pill";
			$_SESSION['privHelp'] = "";
			$_SESSION['hPill'] = "pill";

			$_SESSION['sh'] = "style=display:none;";
			$_SESSION['sa'] = "";
			$_SESSION['re'] = "";
			$_SESSION['ma'] = "";
			$_SESSION['in'] = "";
			$_SESSION['he'] = "style=display:none;";

			$validation = "";
			$input = "hide";
			$_SESSION['btnDesappear'] = "";
			$error = "Welcome: " ."<b>".$_SESSION['eName']. " " . $_SESSION['eLastname']."</b>";
		}
		else if(@$_SESSION['ePrivilege'] == "2")
		{
			//Supervisor
			$notLogged = "";
			$logged = "display: none";
			$_SESSION['privShift'] = "";
			$_SESSION['privInventory'] = "";
			$_SESSION['privManagement'] = "disabled";
			$_SESSION['privSales'] = "";
			$_SESSION['privReport'] = "";
			$_SESSION['sPill'] = "pill";
			$_SESSION['iPill'] = "pill";
			$_SESSION['mPill'] = "";
			$_SESSION['slsPill'] = "pill";
			$_SESSION['rPill'] = "pill";
			$_SESSION['privHelp'] = "";
			$_SESSION['hPill'] = "pill";

			$_SESSION['sh'] = "";
			$_SESSION['sa'] = "";
			$_SESSION['re'] = "";
			$_SESSION['ma'] = "style=display:none;";
			$_SESSION['in'] = "";
			$_SESSION['he'] = "";

			$validation = "";
			$input = "hide";
			$_SESSION['btnDesappear'] = "";
			$error = "Welcome: " ."<b>".$_SESSION['eName']. " " . $_SESSION['eLastname']."</b>";
		}
		else if(@$_SESSION['ePrivilege'] == "3")
		{
			$notLogged = "";
			$logged = "display: none";
			//Employee
			$_SESSION['privShift'] = "";
			$_SESSION['privInventory'] = "";
			$_SESSION['privManagement'] = "disabled";
			$_SESSION['privSales'] = "";
			$_SESSION['privReport'] = "disabled";
			$_SESSION['sPill'] = "pill";
			$_SESSION['iPill'] = "pill";
			$_SESSION['mPill'] = "";
			$_SESSION['slsPill'] = "pill";
			$_SESSION['rPill'] = "";
			$_SESSION['privHelp'] = "";
			$_SESSION['hPill'] = "pill";

			$_SESSION['sh'] = "";
			$_SESSION['sa'] = "";
			$_SESSION['re'] = "style=display:none;";
			$_SESSION['ma'] = "style=display:none;";
			$_SESSION['in'] = "";
			$_SESSION['he'] = "";
			$validation = "";
			$input = "hide";
			$_SESSION['btnDesappear'] = "";
			$error = "Welcome: " ."<b>".$_SESSION['eName']. " " . $_SESSION['eLastname']."</b>";
		}
		else
		{
			$_SESSION['btnDesappear'] = "hide";
			$_SESSION['privShift'] = "disabled";
			$_SESSION['sPill'] = "";
			$_SESSION['iPill'] = "";
			$_SESSION['mPill'] = "";
			$_SESSION['slsPill'] = "";
			$_SESSION['rPill'] = "";
			$_SESSION['privInventory'] = "disabled";
			$_SESSION['privManagement'] = "disabled";
			$_SESSION['privSales'] = "disabled";
			$_SESSION['privReport'] = "disabled";
			$_SESSION['privHelp'] = "disabled";
			$_SESSION['hPill'] = "";
			$_SESSION['btnLocDesappear'] = "hide";
			$_SESSION['ePickLoc'] = "";
			$_SESSION['eLoc'] = "";

			$_SESSION['sh'] = "style=display:none;";
			$_SESSION['sa'] = "style=display:none;";
			$_SESSION['re'] = "style=display:none;";
			$_SESSION['ma'] = "style=display:none;";
			$_SESSION['in'] = "style=display:none;";
			$_SESSION['he'] = "style=display:none;";
		}	
?>