<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	$itemNumber = htmlentities($_POST['itemDetailsItemNumber']);
	
	if(isset($_POST['itemDetailsItemNumber'])){
		
		// Verifique se os campos obrigatórios não estão vazios
		if(!empty($itemNumber)){
			
			// scanizar o numero do item
			$itemNumber = filter_var($itemNumber, FILTER_SANITIZE_STRING);

			// Verifique se o item está no banco de dados
			$itemSql = 'SELECT itemNumber FROM item WHERE itemNumber=:itemNumber';
			$itemStatement = $conn->prepare($itemSql);
			$itemStatement->execute(['itemNumber' => $itemNumber]);
			
			if($itemStatement->rowCount() > 0){
				
				// O item existe no banco de dados. Daí iniciar o processo DELETE
				$deleteItemSql = 'DELETE FROM item WHERE itemNumber=:itemNumber';
				$deleteItemStatement = $conn->prepare($deleteItemSql);
				$deleteItemStatement->execute(['itemNumber' => $itemNumber]);

				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Item apagado.</div>';
				exit();
				
			} else {
				// Item não existe, portanto, diga ao usuário que ele não pode excluir esse item 
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>O item não existe no banco de dados. Portanto, não posso excluir.</div>';
				exit();
			}
			
		} else {
			// O número do item está vazio. Portanto, exiba a mensagem de erro
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Introduza o número do equipamento.</div>';
			exit();
		}
	}
?>