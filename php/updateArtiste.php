<?php

	// SET HEADER
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: access");
	header("Access-Control-Allow-Methods: PUT");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

	// INCLUDING DATABASE AND MAKING OBJECT
	require 'database.php';
	$db_connection = new Database('musique','127.0.0.1','root','' );
	$conn = $db_connection->connect();

	// GET DATA FORM REQUEST
	$data = json_decode(file_get_contents("php://input"));

	//CHECKING, IF ID AVAILABLE ON $data
	if(isset($data->id)) {
		 $msg['message'] = '';
		 $post_id = $data->id;

		 //GET POST BY ID FROM DATABASE
		 $get_post = "SELECT * FROM artiste WHERE Id=:post_id";
		 $get_stmt = $conn->prepare($get_post);
		 $get_stmt->bindValue(':post_id', $post_id,PDO::PARAM_INT);
		 $get_stmt->execute();
		 
		 //CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
		 if($get_stmt->rowCount() > 0) {
			 
			 // FETCH POST FROM DATBASE
			 $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
			 
			 // CHECK, IF NEW UPDATE REQUEST DATA IS AVAILABLE THEN SET IT OTHERWISE SET OLD DATA
			 $artistName = isset($data->artistName) ? $data->artistName : $row['artisteName'];
			 $realName = isset($data->realName) ? $data->realName : $row['realName'];
			 $update_query = "UPDATE artiste SET artistName = :artistName, realName = :realName WHERE Id = :id";
			 $update_stmt = $conn->prepare($update_query);

			 // DATA BINDING AND REMOVE SPECIAL CHARS AND REMOVE TAGS
			 $update_stmt->bindValue(':artistName', htmlspecialchars(strip_tags($artistName)),PDO::PARAM_STR);
			 $update_stmt->bindValue(':realName', htmlspecialchars(strip_tags($realName)),PDO::PARAM_STR);
			 $update_stmt->bindValue(':id', $post_id,PDO::PARAM_INT);

			 if($update_stmt->execute()){
				$msg['message'] = 'Data updated successfully';
			 } else {
				$msg['message'] = 'data not updated';
			 }
		 } else {
			$msg['message'] = 'Invalid ID';
		 }
		 echo json_encode($msg);
	}

?>