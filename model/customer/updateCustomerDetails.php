<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	// Verifica se a consulta POST foi recebida
	if(isset($_POST['customerDetailsCustomerID'])) {
		
		$customerDetailsCustomerID = htmlentities($_POST['customerDetailsCustomerID']);
		$customerDetailsCustomerFullName = htmlentities($_POST['customerDetailsCustomerFullName']);
		$customerDetailsCustomerMobile = htmlentities($_POST['customerDetailsCustomerMobile']);
		$customerDetailsStatus = htmlentities($_POST['customerDetailsStatus']);
		
		// Verifica se os campos obrigatórios não estão vazios
		if(isset($customerDetailsCustomerFullName) && isset($customerDetailsCustomerMobile)  {
			
			// Valida o número do celular
			if(filter_var($customerDetailsCustomerMobile, FILTER_VALIDATE_INT) === 0 || filter_var($customerDetailsCustomerMobile, FILTER_VALIDATE_INT)) {
				// O número do celular é válido
			} else {
				//O número do celular não é válido
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Por favor insira um número de telemóvel válido</div>';
				exit();
			}
			
			// Verifique se o campo CustomerID está vazio. Em caso afirmativo, exiba uma mensagem de erro
			// Temos que dizer isso especificamente ao usuário porque a marca (*) não é adicionada a esse campo
			if(empty($customerDetailsCustomerID)){
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Insira o TecnicoID para atualizar esse tecnico.</div>';
				exit();
			}
			
			
			// Verifique se o CustomerID fornecido está no banco de dados
			$customerIDSelectSql = 'SELECT customerID FROM customer WHERE customerID = :customerDetailsCustomerID';
			$customerIDSelectStatement = $conn->prepare($customerIDSelectSql);
			$customerIDSelectStatement->execute(['customerDetailsCustomerID' => $customerDetailsCustomerID]);
			
			if($customerIDSelectStatement->rowCount() > 0) {
				// CustomerID está disponível no banco de dados. Portanto, podemos ir em frente e ATUALIZAR seus detalhes
				// Construir a consulta update query
				$updateCustomerDetailsSql = 'UPDATE customer SET fullName = :fullName, mobile = :mobile,  status = :status WHERE customerID = :customerID';
				$updateCustomerDetailsStatement = $conn->prepare($updateCustomerDetailsSql);
				$updateCustomerDetailsStatement->execute(['fullName' => $customerDetailsCustomerFullName, 'mobile' => $customerDetailsCustomerMobile , 'status' => $customerDetailsStatus, 'customerID' => $customerDetailsCustomerID]);
				
				// ATUALIZE o nome do cliente na tabela de vendas também
				$updateCustomerInSaleTableSql = 'UPDATE sale SET customerName = :customerName WHERE customerID = :customerID';
				$updateCustomerInSaleTableStatement = $conn->prepare($updateCustomerInSaleTableSql);
				$updateCustomerInSaleTableStatement->execute(['customerName' => $customerDetailsCustomerFullName, 'customerID' => $customerDetailsCustomerID]);
				
				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Detalhes do tecnico atualizados.</div>';
				exit();
			} else {
				// CustomerID não está no banco de dados. Portanto, pare a atualização e saia
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>TenicoID não existe no banco de dados. Portanto, a atualização não é possível.</div>';
				exit();
			}
			
		} else {
			// Um ​​ou mais campos obrigatórios estão vazios. Portanto, exiba a mensagem de erro
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Por favor, insira todos os campos marcados com um (*)</div>';
			exit();
		}
	}
?>