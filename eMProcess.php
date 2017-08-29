<?php
include 'connection.php';

	if(@$_POST['locChange']) //Search for employees at selected location
	{
		$id=$_POST['locChange'];
		$id = stripslashes($id);

		$id = mysql_real_escape_string($id);

		$stmt = $conn->prepare("Select u.empId, u.name, u.lastname From location_user lu, user u Where lu.locationId = '$id' AND lu.empId = u.empId AND lu.active='1'");																						
		$stmt->execute();
		$result = $stmt->fetchAll();

	    foreach($result as $row) 
	    { 
	    	echo '<option value="'.$row['empId'].'">'.$row['name'].' '. $row['lastname']. '</option>';
	    }
	}

	if(@$_POST['selectedEmp']) //Display info of selected employee
	{
		$data = array();
		$locInfo = array();
		$employee = $_POST['selectedEmp'];

		$employee = stripslashes($employee);
		$employee = mysql_real_escape_string($employee);

		try {
				$stmt = $conn->prepare("Select * from user Where empId= '$employee' AND active='1'");																						
				$stmt->execute();
				$result = $stmt->fetchAll();

			    foreach($result as $row) 
			    { 
			    	$pass2word = '';
			    	for($i = 0; $i < strlen($row['password'])/3; $i++)
					{
						$pw = substr($row['password'], ($i*3), 3);
						$pass2word .= chr($pw-33);
					}

			    	$data['eName'] = $row['name'];
			    	$data['eLastname'] = $row['lastname'];
			    	$data['eUsername'] = $row['username'];
			    	$data['ePassword'] = $pass2word;
			    	$data['eQuest'] = $row['question'];
			    	$data['eAns'] = $row['answer']; 
			    	$data['ePrivilege'] = $row['privilege'];
			    }

			    $stmt = $conn->prepare("Select * from location_user Where empId= '$employee' AND active='1'");																			
				$stmt->execute();
				$result = $stmt->fetchAll();

			    foreach($result as $row) 
			    {
			    	$locInfo[$row['locationId']] = $row['note'];
			    }
			    $data['info'] = $locInfo;
				$data['success'] = true;
				echo json_encode($data);
			}
			catch(PDOException $e)
			{
			 	$errors['exp'] = 'Error: Fetching data!';
				$data['success'] = false;
				echo json_encode($data);
				exit;
			}
		}

	if(@$_POST['eAdd'] == "1") //Add a new  employee with single location
	{
		$data = array();
		$errors = array();

		$firstName = $_POST['name'];
		$lastName = $_POST['last'];
		$question = $_POST['ques'];
		$answer = $_POST['answ'];
		$level = $_POST['leve'];
		$location = $_POST['loca'];
		$username = $_POST['user'];
		$password = $_POST['pass'];
		$note = $_POST['note'];

		$firstName = stripslashes($firstName);
		$lastName = stripslashes($lastName);
		$question = stripslashes($question);
		$answer = stripslashes($answer);
		$level = stripslashes($level);
		$location = stripslashes($location);
		$username = stripslashes($username);
		$password = stripslashes($password);
		$note = stripslashes($note);

		$firstName = mysql_real_escape_string($firstName);
		$lastName = mysql_real_escape_string($lastName);
		$question = mysql_real_escape_string($question);
		$answer = mysql_real_escape_string($answer);
		$level = mysql_real_escape_string($level);
		$location = mysql_real_escape_string($location);
		$username = mysql_real_escape_string($username);
		$password = mysql_real_escape_string($password);
		$note = mysql_real_escape_string($note);

		if(empty($firstName) || empty($lastName))
		{
			$errors['name'] = 'Please enter a full name!';
		}
		if(empty($username) || empty($password))
		{
			$errors['username'] = 'Please enter both username and password!';
		}
		if(!empty($errors))
		{
			$data['errors'] = $errors;
			$data['success'] = false;
			echo json_encode($data);
			exit;
		}
			try {
				$pass2word = '';
				for($i = 0; $i < strlen($password); $i++)
				{
					if(strlen(ord($password[$i])+33) == 2)
					{
						$pass2word .= '0'.(ord($password[$i])+33).'';
					}
					if(strlen(ord($password[$i])+33) == 3)
					{
						$pass2word .= (ord($password[$i])+33);
					}
				}

				$stmt = $conn->prepare("Insert Into user (name, lastname, username, password, question, answer, privilege) Values ('$firstName','$lastName','$username','$pass2word','$question','$answer','$level')");																						
				$stmt->execute();
				
				$last_id = $conn->lastInsertId();

				$stmt = $conn->prepare("Insert Into location_user (empId, locationId, note) Values ('$last_id','$location','$note')");
				$stmt->execute();

				$data['success'] = true;
				$data['message'] = 'The employee is now part of TCSE!';
				echo json_encode($data);
			}
			catch(PDOException $e)
			{
				if ($e->errorInfo[1] == 1062) 
				{
				 	$errors['exp'] = 'Error: Duplicated Username!';
				 	$data['errors'] = $errors;
					$data['success'] = false;
					echo json_encode($data);
					exit;
				}
			}
	}


		if(@$_POST['eAdd'] == "2") //Add a new  employee with multiple locations
		{
			$data = array();
			$errors = array();

			$firstName = $_POST['name'];
			$lastName = $_POST['last'];
			$question = $_POST['ques'];
			$answer = $_POST['answ'];
			$level = $_POST['leve'];
			$username = $_POST['user'];
			$password = $_POST['pass'];
			
			$multiData = $_POST['locNote'];

			$firstName = stripslashes($firstName);
			$lastName = stripslashes($lastName);
			$question = stripslashes($question);
			$answer = stripslashes($answer);
			$level = stripslashes($level);
			$username = stripslashes($username);
			$password = stripslashes($password);

			$multiData = array_map('stripslashes', $multiData);

			
			$firstName = mysql_real_escape_string($firstName);
			$lastName = mysql_real_escape_string($lastName);
			$question = mysql_real_escape_string($question);
			$answer = mysql_real_escape_string($answer);
			$level = mysql_real_escape_string($level);
			$username = mysql_real_escape_string($username);
			$password = mysql_real_escape_string($password);

			$multiData = array_map('mysql_real_escape_string', $multiData);

			if(empty($firstName) || empty($lastName))
			{
				$errors['name'] = 'Please enter a full name!';
			}
			if(empty($username) || empty($password))
			{
				$errors['username'] = 'Please enter both username and password!';
			}
			if(!empty($errors))
			{
				$data['errors'] = $errors;
				$data['success'] = false;
				echo json_encode($data);
				exit;
			}
			$pass2word ='';
				for($i = 0; $i < strlen($password); $i++)
				{
					if(strlen(ord($password[$i])+33) == 2)
					{
						$pass2word .= '0'.(ord($password[$i])+33).'';
					}
					if(strlen(ord($password[$i])+33) == 3)
					{
						$pass2word .= (ord($password[$i])+33);
					}
				}
			try {

				$stmt = $conn->prepare("Insert Into user (name, lastname, username, password, question, answer, privilege) Values ('$firstName','$lastName','$username','$pass2word','$question','$answer','$level')");																						
				$stmt->execute();
				
				$last_id = $conn->lastInsertId();

				while(list($var, $val) = each($multiData)) 
				{
					$stmt = $conn->prepare("Insert Into location_user (empId, locationId, note) Values ('$last_id','$var','$val')");
					$stmt->execute();
				}

				$data['success'] = true;
				$data['message'] = 'The employee is now part of TCSE!';
				echo json_encode($data);
			}
			catch(PDOException $e)
			{
				if ($e->errorInfo[1] == 1062) 
				{
				 	$errors['exp'] = 'Error: Duplicated Username!';
				 	$data['errors'] = $errors;
					$data['success'] = false;
					echo json_encode($data);
					exit;
				}
			}
		}

		if(@$_POST['eUpd'] == "1") //Update employee
		{
			$data = array();
			$errors = array();

			$empId = $_POST['emId'];
			$firstName = $_POST['name'];
			$lastName = $_POST['last'];
			$question = $_POST['ques'];
			$answer = $_POST['answ'];
			$level = $_POST['leve'];
			$username = $_POST['user'];
			$password = $_POST['pass'];
			
			$location = $_POST['loca'];
			$note = $_POST['note'];
			$multiData = $_POST['mult'];

			$empId = stripslashes($empId);
			$firstName = stripslashes($firstName);
			$lastName = stripslashes($lastName);
			$question = stripslashes($question);
			$answer = stripslashes($answer);
			$level = stripslashes($level);
			$username = stripslashes($username);
			$password = stripslashes($password);
			
			$location = stripslashes($location);
			$note = stripslashes($note);
			$multiData = array_map('stripslashes', $multiData);

			$empId = mysql_real_escape_string($empId);
			$firstName = mysql_real_escape_string($firstName);
			$lastName = mysql_real_escape_string($lastName);
			$question = mysql_real_escape_string($question);
			$answer = mysql_real_escape_string($answer);
			$level = mysql_real_escape_string($level);
			$username = mysql_real_escape_string($username);
			$password = mysql_real_escape_string($password);
			
			$location = mysql_real_escape_string($location);
			$note = mysql_real_escape_string($note);
			$multiData = array_map('mysql_real_escape_string', $multiData);
			
			try 
			{
				$pass2word = '';
				for($i = 0; $i < strlen($password); $i++)
				{
					if(strlen(ord($password[$i])+33) == 2)
					{
						$pass2word .= '0'.(ord($password[$i])+33).'';
					}
					if(strlen(ord($password[$i])+33) == 3)
					{
						$pass2word .= (ord($password[$i])+33);
					}
				}
				$stmt = $conn->prepare("Update user Set name='$firstName', lastname='$lastName', username='$username', password='$pass2word', question='$question', answer='$answer', privilege='$level' Where empId='$empId'");																						
				$stmt->execute();
				while(list($var, $val) = each($multiData)) 
				{	
					$stmt = $conn->prepare("Select * from location_user Where empId='$empId' AND locationId='$var'");
					$stmt->execute();
					$result = $stmt->fetch();
					if($result)
					{
						$stmt = $conn->prepare("Update location_user Set note='$val' Where empId='$empId' And locationId='$var'");
						$stmt->execute();
					}
					else
					{
						$stmt = $conn->prepare("Insert Into location_user (empId, locationId, note) Values ('$empId', '$var', '$val')");
						$stmt->execute();
					}
				}
				$data['success'] = true;
				$data['message'] = 'Employee Update Completed!';
				echo json_encode($data);
			}
			catch(PDOException $e)
			{
				 	$errors['exp'] = 'Error: Updating Employee!';
				 	$data['errors'] = $errors;
					$data['success'] = false;
					echo json_encode($data);
					exit;
			}
		}

		if(@$_POST['delEmp'] == "1") //Delete employee
		{
			$data = array();
			$errors = array();
			$location = $_POST['locId'];
			$employee = $_POST['empId'];

			$location = stripslashes($location);
			$employee = stripslashes($employee);
			$location = mysql_real_escape_string($location);
			$employee = mysql_real_escape_string($employee);
			
			try {

				$stmt = $conn->prepare("Update location_user Set active='0' Where empId='$employee' And locationId='$location'");																						
				$stmt->execute();

				$data['success'] = true;
				$data['message'] = 'The Employee has being removed!';
				echo json_encode($data);
			}
			catch(PDOException $e)
			{
				if ($e->errorInfo[1] == 1062) 
				{
				 	$errors['exp'] = 'Error: Removing Employee!';
				 	$data['errors'] = $errors;
					$data['success'] = false;
					echo json_encode($data);
					exit;
				}
			}
		}
?>