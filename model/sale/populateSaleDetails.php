<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');

	// Execute o script se a solicitação POST for enviada
	if(isset($_POST['saleDetailsSaleID'])){
		
		$saleID = htmlentities($_POST['saleDetailsSaleID']);
		
		$saleDetailsSql = 'SELECT * FROM sale WHERE saleID = :saleID';
		$saleDetailsStatement = $conn->prepare($saleDetailsSql);
		$saleDetailsStatement->execute(['saleID' => $saleID]);
		
		// Se forem encontrados dados para o saleID fornecido, devolva-o como um objeto json
		if($saleDetailsStatement->rowCount() > 0) {
			$row = $saleDetailsStatement->fetch(PDO::FETCH_ASSOC);
			echo json_encode($row);
		}
		$saleDetailsStatement->closeCursor();
	}
?>