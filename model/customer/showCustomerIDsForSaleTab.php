<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	//Verifique se a solicitação POST foi recebida e, em caso afirmativo, execute o scrip
	if(isset($_POST['textBoxValue'])){
		$output = '';
		$customerIDString = '%' . htmlentities($_POST['textBoxValue']) . '%';
		
		//Construa a consulta SQL para obter a ID do tecnico
		$sql = 'SELECT customerID FROM customer WHERE customerID LIKE ?';
		$stmt = $conn->prepare($sql);
		$stmt->execute([$customerIDString]);
		
		//Se recebermos quaisquer resultados da consulta acima, exiba-os em uma lista
		if($stmt->rowCount() > 0){
			
			//O ID do cliente está disponível no banco de dados. Portanto, crie a lista suspensa
			$output = '<ul class="list-unstyled suggestionsList" id="saleDetailsCustomerIDSuggestionsList">';
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$output .= '<li>' . $row['customerID'] . '</li>';
			}
			echo '</ul>';
		} else {
			$output = '';
		}
		$stmt->closeCursor();
		echo $output;
	}
?>