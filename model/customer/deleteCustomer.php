<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	if(isset($_POST['customerDetailsCustomerID'])){
		
		$customerDetailsCustomerID = htmlentities($_POST['customerDetailsCustomerID']);
		
		// Verifique se os campos obrigatórios não estão vazios
		if(!empty($customerDetailsCustomerID)){
			
			// scanizar o ID do tecnico
			$customerDetailsCustomerID = filter_var($customerDetailsCustomerID, FILTER_SANITIZE_STRING);

			// Verifique se o cliente está na base de dados
			$customerSql = 'SELECT customerID FROM customer WHERE customerID=:customerID';
			$customerStatement = $conn->prepare($customerSql);
			$customerStatement->execute(['customerID' => $customerDetailsCustomerID]);
			
			if($customerStatement->rowCount() > 0){
				
				// O cliente existe no banco de dados. Daí iniciar o processo DELETE
				$deleteCustomerSql = 'DELETE FROM customer WHERE customerID=:customerID';
				$deleteCustomerStatement = $conn->prepare($deleteCustomerSql);
				$deleteCustomerStatement->execute(['customerID' => $customerDetailsCustomerID]);

				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Tecnico apagado.</div>';
				exit();
				
			} else {
				// O cliente não existe, portanto, diga ao usuário que ele não pode excluir esse cliente 
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>O tecnico não existe no banco de dados. Portanto, não pode excluir.</div>';
				exit();
			}
			
		} else {
			// tecnicoID está vazio. Portanto, exiba a mensagem de erro
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Porfavor insira o TecnicoID</div>';
			exit();
		}
	}
?>