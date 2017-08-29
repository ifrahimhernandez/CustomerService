<?php 
	include 'connection.php';
	session_start();

	if(@$_POST['user'] && @$_POST['pass']) //Check if log in for first time
	{
		$data = array();
		$user = $_POST['user'];
		$pass = $_POST['pass'];

		$user = stripslashes($user);
		$pass = stripslashes($pass);
		$user = mysql_real_escape_string($user);
		$pass = mysql_real_escape_string($pass);

		$pass2word = '';
		for($i = 0; $i < strlen($pass); $i++)
		{
			if(strlen(ord($pass[$i])) == 1)
			{
				$pass2word .= '00'.(ord($pass[$i])+33).'';
			}
			if(strlen(ord($pass[$i])) == 2)
			{
				$pass2word .= '0'.(ord($pass[$i])+33).'';
			}
			if(strlen(ord($pass[$i])) == 3)
			{
				$pass2word .= (ord($pass[$i])+33);
			}
		}
		try{
			$stmt = $conn->prepare("Select firstLog From user Where active='1' AND username='$user' AND password='$pass2word'");																						
			$stmt->execute();
			$result = $stmt->fetchAll();
			foreach($result as $row) 
		    { 
		    	$data['answer'] = $row['firstLog'];
		    }
			$data['success'] = true;
			echo json_encode($data);
		}
		catch(PDOException $e)
		{
			$data['success'] = false;
			echo json_encode($data);
			exit;
		}
	}

	if(@$_POST['actualLocation'])
	{
		$data = array();
		$locId = $_POST['actualLocation'];
		try{
			$stmt = $conn->prepare("Select locationName From location Where locationId = $locId");																						
			$stmt->execute();
			$result = $stmt->fetchAll();
			foreach($result as $row) 
		    { 
		    	$_SESSION['locName'] = $row['locationName'];
		    }
		    $_SESSION['eLoc'] = $_POST['actualLocation'];
			$data['success'] = true;
			echo json_encode($data);
		}
		catch(PDOException $e)
		{
			$data['success'] = false;
			echo json_encode($data);
			exit;
		}
	}
?>