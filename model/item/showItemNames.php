<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	// Verifique se a solicitação POST foi recebida e, em caso afirmativo, execute o script
	if(isset($_POST['textBoxValue'])){
		$output = '';
		$itemNameString = '%' . htmlentities($_POST['textBoxValue']) . '%';
		
		// Construa a consulta SQL para obter o nome do item
		$sql = 'SELECT itemName FROM item WHERE itemName LIKE ?';
		$stmt = $conn->prepare($sql);
		$stmt->execute([$itemNameString]);
		
		// Se recebermos quaisquer resultados da consulta acima, exiba-os em uma lista
		if($stmt->rowCount() > 0){
			$output = '<ul class="list-unstyled suggestionsList" id="itemDetailsItemNamesSuggestionsList">';
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$output .= '<li>' . $row['itemName'] . '</li>';
			}
			echo '</ul>';
		} else {
			$output = '';
		}
		$stmt->closeCursor();
		echo $output;
	}
?>