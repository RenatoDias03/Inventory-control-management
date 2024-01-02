<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	if(isset($_POST['itemImageItemNumber'])){
		
		$itemImageItemNumber = htmlentities($_POST['itemImageItemNumber']);
		
		$baseImageFolder = '../../data/item_images/';
		$itemImageFolder = '';
		
		if(!empty($itemImageItemNumber)){
			
			//  Verifique se o usuário selecionou uma imagem
			if($_FILES['itemImageFile']['name'] != ''){
				// Tanto itemNumber quanto o arquivo de imagem fornecidos. Por isso, prossiga para os próximos passos
				
				// scanizar  numero do item
				$itemImageItemNumber = filter_var($itemImageItemNumber, FILTER_SANITIZE_STRING);
				
				// checkar se o numero esta na bd
				$itemNumberSql = 'SELECT * FROM item WHERE itemNumber = :itemNumber';
				$itemNumberStatement = $conn->prepare($itemNumberSql);
				$itemNumberStatement->execute(['itemNumber' => $itemImageItemNumber]);
				
				if($itemNumberStatement->rowCount() > 0){
					//O item está no DB, portanto, prossiga para as próximas etapas
					//Verifique o arquivo .extension
					$arr = explode('.', $_FILES['itemImageFile']['name']);
					$extension = strtolower(end($arr));
					$allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
					
					if(in_array($extension, $allowedTypes)){
						// All good so far...
						
						$baseImageFolder = '../../data/item_images/';
						$itemImageFolder = '';
						$fileName = time() . '_' . basename($_FILES['itemImageFile']['name']);
						
						// Criar pasta de imagens para carregar imagens
						$itemImageFolder = $baseImageFolder . $itemImageItemNumber . '/';
						if(is_dir($itemImageFolder)){
							// A pasta já existe. Por isso, não faça nada
						} else {
							// Pasta não existe, portanto, criá-lo
							mkdir($itemImageFolder);
						}
						
						$targetPath = $itemImageFolder . $fileName;
						//echo $targetPath;
						//exit();
						
						// Carregar ficheiro para o servidor
						if(move_uploaded_file($_FILES['itemImageFile']['tmp_name'], $targetPath)){
							
							// Atualizar url da imagem na tabela de itens
							$updateImageUrlSql = 'UPDATE item SET imageURL = :imageURL WHERE itemNumber = :itemNumber';
							$updateImageUrlStatement = $conn->prepare($updateImageUrlSql);
							$updateImageUrlStatement->execute(['imageURL' => $fileName, 'itemNumber' => $itemImageItemNumber]);
							
							echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Imagem carregada com sucesso.</div>';
							exit();
							
						} else {
							echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Não foi possível carregar a imagem.</div>';
							exit();
						}
						
					} else {
					// O tipo de imagem não é permitido
					echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>O tipo de imagem não é permitido. Selecione uma imagem válida.</div>';
					exit();
					}
				}
				
			} else {
				// Arquivo de imagem não fornecido
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Selecione uma imagem</div>';
				exit();
			}
		
		} else {
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter item number</div>';
			exit();
		}

	}

?>