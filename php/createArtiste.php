<?php
	// SET HEADER
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: access");
	header("Access-Control-Allow-Methods: POST");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	
	// INCLUDING DATABASE AND MAKING OBJECT
	require 'database.php';
	$db_connection = new Database('musique','127.0.0.1','root','' );
	$conn = $db_connection->connect();
	
	// GET DATA FORM REQUEST
	$data = json_decode(file_get_contents("php://input"));
	
	//CREATE MESSAGE ARRAY AND SET EMPTY
	$msg['message'] = '';
	
	// CHECK IF RECEIVED DATA FROM THE REQUEST
	if(isset($data->artistName) && isset($data->realName))
	{
		 // CHECK DATA VALUE IS EMPTY OR NOT
		 if(!empty($data->artistName) ) {
			 $insert_query = "INSERT INTO artiste(artistName,realName) VALUES(:artistName,:realName)";
			 $insert_stmt = $conn->prepare($insert_query);

			 // DATA BINDING
			 $insert_stmt->bindValue(':artistName', htmlspecialchars(strip_tags($data->artistName)), PDO::PARAM_STR);
			 $insert_stmt->bindValue(':realName', htmlspecialchars(strip_tags($data->realName)), PDO::PARAM_STR);

			 if($insert_stmt->execute()){
				$msg['message'] = 'Data Inserted Successfully';
			 } else {
				$msg['message'] = 'Data not Inserted';
			 }
		 } else {
			$msg['message'] = 'Oops! empty field detected. Please fill all the fields';
		 }
	}
	else
	{
		$msg['message'] = 'Please fill all the fields | title, body, author';
	}
	
	//ECHO DATA IN JSON FORMAT
	echo json_encode($msg);
?>