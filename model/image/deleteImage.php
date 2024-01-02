<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	if(isset($_POST['itemImageItemNumber'])){

			$itemImageItemNumber = htmlentities($_POST['itemImageItemNumber']);
			
			$baseImageFolder = '../../data/item_images/';
			$itemImageFolder = '';
			
			if(!empty($itemImageItemNumber)){
					
				// scanizar numero do item
				$itemImageItemNumber = filter_var($itemImageItemNumber, FILTER_SANITIZE_STRING);
				
				// testar numero na bd
				$itemNumberSql = 'SELECT * FROM item WHERE itemNumber = :itemNumber';
				$itemNumberStatement = $conn->prepare($itemNumberSql);
				$itemNumberStatement->execute(['itemNumber' => $itemImageItemNumber]);
				
				if($itemNumberStatement->rowCount() > 0){
					// O item está no DB, portanto, prossiga para as próximas etapas	
					//Atualizar url da imagem na tabela de itens para a imagem padrão
					$updateImageUrlSql = "UPDATE item SET imageURL = 'imageNotAvailable.jpg' WHERE itemNumber = :itemNumber";
					$updateImageUrlStatement = $conn->prepare($updateImageUrlSql);
					$updateImageUrlStatement->execute(['itemNumber' => $itemImageItemNumber]);
					
					echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Imagem apagada com êxito.</div>';
					exit();
				}
			
			} else {
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Introduza o número do artigo</div>';
				exit();
			}

	}

?>