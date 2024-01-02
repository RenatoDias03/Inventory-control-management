<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');

	// Execute o script se a solicitação POST for enviada
	if(isset($_POST['customerID'])){
		
		$customerID = htmlentities($_POST['customerID']);
		
		$customerDetailsSql = 'SELECT * FROM customer WHERE customerID = :customerID';
		$customerDetailsStatement = $conn->prepare($customerDetailsSql);
		$customerDetailsStatement->execute(['customerID' => $customerID]);
		
		// Se forem encontrados dados para o número de item fornecido, devolva-os como um objeto json
		if($customerDetailsStatement->rowCount() > 0) {
			$row = $customerDetailsStatement->fetch(PDO::FETCH_ASSOC);
			echo json_encode($row);
		}
		$customerDetailsStatement->closeCursor();
	}
?>