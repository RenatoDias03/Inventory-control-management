<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	$initialStock = 0;
	$baseImageFolder = '../../data/item_images/';
	$itemImageFolder = '';
	
	if(isset($_POST['itemDetailsItemNumber'])){
		
		$itemNumber = htmlentities($_POST['itemDetailsItemNumber']);
		$itemName = htmlentities($_POST['itemDetailsItemName']);
		$quantity = htmlentities($_POST['itemDetailsQuantity']);
		$status = htmlentities($_POST['itemDetailsStatus']);
		$description = htmlentities($_POST['itemDetailsDescription']);
		
		// Verifique se os campos obrigatórios não estão vazios
		if(!empty($itemNumber) && !empty($itemName) && isset($quantity)){
			
			// scanizar numero do item
			$itemNumber = filter_var($itemNumber, FILTER_SANITIZE_STRING);
			
			// Valide a prquantidade. Tem de ser um número
			if(filter_var($quantity, FILTER_VALIDATE_INT) === 0 || filter_var($quantity, FILTER_VALIDATE_INT)){
				// Quantidade válida
			} else {
				// A quantidade não é um número válido
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Introduza um número válido para a quantidade</div>';
				exit();
			}
			
			
			// Criar pasta de imagens para carregar imagens
			$itemImageFolder = $baseImageFolder . $itemNumber;
			if(is_dir($itemImageFolder)){
				// A pasta já existe. Por isso, não faça nada
			} else {
				// Pasta não existe, portanto, criá-lo
				mkdir($itemImageFolder);
			}
			
			// calcular valor do stock
			$stockSql = 'SELECT stock FROM item WHERE itemNumber=:itemNumber';
			$stockStatement = $conn->prepare($stockSql);
			$stockStatement->execute(['itemNumber' => $itemNumber]);
			if($stockStatement->rowCount() > 0){
				//$row = $stockStatement->fetch(PDO::FETCH_ASSOC);
				//$quantity = $quantity + $row['stock'];
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>O item já existe no banco de dados. Por favor, clique no botão <strong>Update</strong> para atualizar os detalhes. Ou use um número de item diferente.</div>';
				exit();
			} else {
				// Item não existe, portanto, você pode adicioná-lo ao DB como um novo item
				//Iniciar o processo de inserção
				$insertItemSql = 'INSERT INTO item(itemNumber, itemName, stock, status, description) VALUES(:itemNumber, :itemName,:stock, :status, :description)';
				$insertItemStatement = $conn->prepare($insertItemSql);
				$insertItemStatement->execute(['itemNumber' => $itemNumber, 'itemName' => $itemName, 'stock' => $quantity,'status' => $status, 'description' => $description]);
				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Item adicionado ao banco de dados.</div>';
				exit();
			}

		} else {
			// Um ou mais campos obrigatórios estão vazios. Portanto, exiba uma mensagem de erro
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Introduza todos os campos marcados com um (*)</div>';
			exit();
		}
	}
?>