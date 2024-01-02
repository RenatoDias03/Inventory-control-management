<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	if(isset($_POST['saleDetailsItemNumber'])){
		
		$itemNumber = htmlentities($_POST['saleDetailsItemNumber']);
		$itemName = htmlentities($_POST['saleDetailsItemName']);
		$quantity = htmlentities($_POST['saleDetailsQuantity']);
		$customerID = htmlentities($_POST['saleDetailsCustomerID']);
		$customerName = htmlentities($_POST['saleDetailsCustomerName']);
		$saleDate = htmlentities($_POST['saleDetailsSaleDate']);
		
		// Verifique se os campos obrigatórios não estão vazios
		if(!empty($itemNumber) && isset($customerID) && isset($saleDate) && isset($quantity)){
			
			// scanizar item
			$itemNumber = filter_var($itemNumber, FILTER_SANITIZE_STRING);
			
			// Valide a quantidade do item. Tem de ser um número
			if(filter_var($quantity, FILTER_VALIDATE_INT) === 0 || filter_var($quantity, FILTER_VALIDATE_INT)){
				// validar quantidade
			} else {
				// quantidade nao é um numero valido
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Insira um número válido para a quantidade</div>';
				exit();
			}
			
			// Verifique se o ID do tecnico está vazio
			if($customerID == ''){ 
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Insira o TecnicoId válido. </div>';
				exit();
			}
			
			// validar tecnicpID
			if(filter_var($customerID, FILTER_VALIDATE_INT) === 0 || filter_var($customerID, FILTER_VALIDATE_INT)){
				// validar tecnicId
			} else {
				// tecnicoId nao é um numero valido
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Insira o TecnicoId válido. </div>';
				exit();
			}
			
			// Verifique se itemNumber está vazio
			if($itemNumber == ''){ 
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Porfavor insira um número.</div>';
				exit();
			}
			
			

			// Calcular os valores das ações
			$stockSql = 'SELECT stock FROM item WHERE itemNumber = :itemNumber';
			$stockStatement = $conn->prepare($stockSql);
			$stockStatement->execute(['itemNumber' => $itemNumber]);
			if($stockStatement->rowCount() > 0){
				// As saídas de itens no DB, portanto, podem prosseguir para uma venda
				$row = $stockStatement->fetch(PDO::FETCH_ASSOC);
				$currentQuantityInItemsTable = $row['stock'];
				
				if($currentQuantityInItemsTable <= 0) {
					// Se currentQuantityInItemsTable for <= 0, o Stock está vazio! Isso significa que não podemos fazer a recolha. Daí abortar.
					echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>O stock está vazio. Portanto, não pode fazer a recolha. Selecione um item diferente.</div>';
					exit();
				} elseif ($currentQuantityInItemsTable < $quantity) {
					// A quantidade de recolha solicitada é maior do que a quantidade de item disponível. Daí abortar 
					echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Não há stock suficiente disponível para esta recolha. Portanto, não pode fazer uma recolha. Selecione um item diferente.</div>';
					exit();
				}
				else {
					// Tem pelo menos 1 ou mais em estoque, portanto, prossiga para os próximos passos
					$newQuantity = $currentQuantityInItemsTable - $quantity;
					
					// Verifique se o cliente está no banco de dados
					$customerSql = 'SELECT * FROM customer WHERE customerID = :customerID';
					$customerStatement = $conn->prepare($customerSql);
					$customerStatement->execute(['customerID' => $customerID]);
					
					if($customerStatement->rowCount() > 0){
						// O tecnico sai. Isso significa que tanto o cliente, o item e os estoques estão disponíveis. Daí iniciar INSERT e UPDATE
						$customerRow = $customerStatement->fetch(PDO::FETCH_ASSOC);
						$customerName = $customerRow['fullName'];
						
						// INSERT data to recolha table
						$insertSaleSql = 'INSERT INTO sale(itemNumber, itemName,quantity,customerID, customerName, saleDate) VALUES(:itemNumber, :itemName,:quantity,:customerID, :customerName, :saleDate)';
						$insertSaleStatement = $conn->prepare($insertSaleSql);
						$insertSaleStatement->execute(['itemNumber' => $itemNumber, 'itemName' => $itemName, 'quantity' => $quantity, 'customerID' => $customerID, 'customerName' => $customerName, 'saleDate' => $saleDate]);
						
						// UPDATE stock na tabela item
						$stockUpdateSql = 'UPDATE item SET stock = :stock WHERE itemNumber = :itemNumber';
						$stockUpdateStatement = $conn->prepare($stockUpdateSql);
						$stockUpdateStatement->execute(['stock' => $newQuantity, 'itemNumber' => $itemNumber]);
						
						echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Detalhes da recolha atualizados!</div>';
						exit();
						
					} else {
						echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Tecnico não existe.</div>';
						exit();
					}
				}
				
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>O item já existe no banco de dados. Clique no botão <strong>Atualizar</strong> para atualizar os detalhes. Ou use um número de item diferente.</div>';
				exit();
			} else {
				// Item não existe, portanto, você não pode fazer uma venda a partir dele
				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>O item não existe no inventário!</div>';
				exit();
			}

		} else {
			// Um ou mais campos obrigatórios estão vazios. Portanto, exiba uma mensagem de erro
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Por favor, insira todos os campos marcados com um(*).</div>';
			exit();
		}
	}
?>