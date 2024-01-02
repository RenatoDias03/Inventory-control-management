<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	if(isset($_POST['customerDetailsCustomerFullName'])){
		
		$fullName = htmlentities($_POST['customerDetailsCustomerFullName']);
		$mobile = htmlentities($_POST['customerDetailsCustomerMobile']);
		$status = htmlentities($_POST['customerDetailsStatus']);
		
		if(isset($fullName) && isset($mobile)) {
			// Validar número de telemóvel
			if(filter_var($mobile, FILTER_VALIDATE_INT) === 0 || filter_var($mobile, FILTER_VALIDATE_INT)) {
				// Valid mobile number
			} else {
				// O telemóvel está errado
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Porfavor insera um número de telefone válido.</div>';
				exit();
			}
			
			
			// Verifique se o nome completo está vazio ou não
			if($fullName == ''){
				// O nome completo está vazio
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Porfavor insira um nome completo.</div>';
				exit();
			}
			
			// Iniciar o processo de inserção
			$sql = 'INSERT INTO customer(fullName, mobile, status) VALUES(:fullName, :mobile, :status)';
			$stmt = $conn->prepare($sql);
			$stmt->execute(['fullName' => $fullName, 'mobile' => $mobile, 'status' => $status]);
			echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Técnico Adicionado com sucesso!</div>';
		} else {
			// Um ou mais campos estão vazios
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Por favor, insira todos os campos marcados com um (*).</div>';
			exit();
		}
	}
?>