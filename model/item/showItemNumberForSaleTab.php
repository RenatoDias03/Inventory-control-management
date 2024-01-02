<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	//Verifique se a solicitação POST foi recebida e, em caso afirmativo, execute o script
	if(isset($_POST['textBoxValue'])){
		$output = '';
		$itemNumberString = '%' . htmlentities($_POST['textBoxValue']) . '%';
		
		// Construa a consulta SQL para obter o nome do item
		$sql = 'SELECT itemNumber FROM item WHERE itemNumber LIKE ?';
		$stmt = $conn->prepare($sql);
		$stmt->execute([$itemNumberString]);
		
		// Se recebermos quaisquer resultados da consulta acima, exiba-os em uma lista
		if($stmt->rowCount() > 0){
			$output = '<ul class="list-unstyled suggestionsList" id="saleDetailsItemNumberSuggestionsList">';
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$output .= '<li>' . $row['itemNumber'] . '</li>';
			}
			echo '</ul>';
		} else {
			$output = '';
		}
		$stmt->closeCursor();
		echo $output;
	}
?>