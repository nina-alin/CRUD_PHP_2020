<?php

	// INCLURE LES HEADER
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: access");
	header("Access-Control-Allow-Methods: GET");
	header("Access-Control-Allow-Credentials: true");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	
	// INCLURE LA BASE DE DONNEES ET CREER L'OBJET
	require 'database.php';
	$db_connection = new Database('musique','127.0.0.1','root','' );
	$conn = $db_connection->connect();
	
	// VERIFIER SI ON RECUPERE LE PARAMETRE ID OU NON
	if(isset($_GET['id']))
	{
		 //SI ON A LE PARAMETRE ID
		 $post_id = filter_var($_GET['id'], FILTER_VALIDATE_INT,[
			 'options' => [
				 'default' => 'all_posts',
				 'min_range' => 1
			 ]
		 ]);
	}
	else{
		$post_id = 'all_posts';
	}
	
	// FAIRE SQL QUERY
	// SI ON A LES ID DES POSTS, ON MONTRE LES POSTS PAR ID SINON ON MONTRE TOUS LES POSTS 
	if( is_numeric($post_id))
		$sql = "SELECT * FROM album WHERE id='$post_id'" ;
	else
		$sql = "SELECT * FROM album"; // cas pour lequel : $post_id = 'all_posts';
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	
	//VERIFIER QU'IL Y AIT DES POSTS DANS NOTRE BASE DE DONNEES
	if($stmt->rowCount() > 0){
		 // CREER LE TABLEAU DES POSTS
		 $posts_array = [];
		 while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			 $post_data = [
				 'id' => $row['Id'],
				 'Titre' => html_entity_decode($row['Titre']),
				 'Parution' => html_entity_decode($row['Parution']),
				 'FkArtiste' => $row['FkArtiste']
			 ];
			 // AJOUTER LES DONNEES A NOTRE TABLEAU $posts_array
			 array_push($posts_array, $post_data);
		 }
		 //MONTRER LE(S) POST(S) DANS LE FORMAT JSON
		 echo json_encode($posts_array);
	}
	else{
		 //SI IL N'Y A PAS DE POST DANS NOTRE BASE DE DONNEES
		 echo json_encode(['message'=>'No post found']);
	}
	
?>	