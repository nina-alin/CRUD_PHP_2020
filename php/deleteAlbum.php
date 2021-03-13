<?php

	// SET HEADER
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: access");
	header("Access-Control-Allow-Methods: DELETE");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	
	// INCLUDING DATABASE AND MAKING OBJECT
	require 'database.php';
	$db_connection = new Database('musique','127.0.0.1','root','' );
	$conn = $db_connection->connect();
	
	// GET DATA FORM REQUEST
	$data = json_decode(file_get_contents("php://input"));
			
	if(isset($data->Id))
	{
		$msg['message'] = '';
		$post_id = $data->Id;
		 

		$delete_query = "DELETE FROM album WHERE Id = :Id";
		$delete_stmt = $conn->prepare($delete_query);
		$delete_stmt->bindValue(':Id', $post_id,PDO::PARAM_INT);
		 
		 

		if($delete_stmt->execute()){
			$msg['message'] = 'Données modifiées avec succès';
		} else {
			$msg['message'] = 'Données non modifiées';
		}
	} else {
		$msg['message'] = 'ID invalide';
	}
	echo json_encode($msg);
	
?>