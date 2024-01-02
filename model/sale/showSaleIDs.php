<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	// Verifique se a solicitação POST foi recebida e, em caso afirmativo, execute o script
	if(isset($_POST['textBoxValue'])){
		$output = '';
		$saleIDString = '%' . htmlentities($_POST['textBoxValue']) . '%';
		

		// Construa a consulta SQL para obter o saleID
		$sql = 'SELECT saleID FROM sale WHERE saleID LIKE ?';
		$stmt = $conn->prepare($sql);
		$stmt->execute([$saleIDString]);
		
		// Se recebermos quaisquer resultados da consulta acima, exiba-os em uma lista
		if($stmt->rowCount() > 0){
			
			// O ID de venda está disponível na bd. Portanto, crie a lista suspensa
			$output = '<ul class="list-unstyled suggestionsList" id="saleDetailsSaleIDSuggestionsList">';
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$output .= '<li>' . $row['saleID'] . '</li>';
			}
			echo '</ul>';
		} else {
			$output = '';
		}
		$stmt->closeCursor();
		echo $output;
	}
?>