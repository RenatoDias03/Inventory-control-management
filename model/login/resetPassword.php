<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	$resetPasswordUsername = '';
	$resetPasswordPassword1 = '';
	$resetPasswordPassword2 = '';
	$hashedPassword = '';
	
	if(isset($_POST['resetPasswordUsername'])){
		$resetPasswordUsername = htmlentities($_POST['resetPasswordUsername']);
		$resetPasswordPassword1 = htmlentities($_POST['resetPasswordPassword1']);
		$resetPasswordPassword2 = htmlentities($_POST['resetPasswordPassword2']);
		
		if(!empty($resetPasswordUsername) && !empty($resetPasswordPassword1) && !empty($resetPasswordPassword2)){
			
			// Verifique se o nome de usuário está vazio
			if($resetPasswordUsername == ''){
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Introduza o nome de username.</div>';
				exit();
			}
			
			// Verifique se as palavras-passe estão vazias
			if($resetPasswordPassword1 == '' || $resetPasswordPassword2 == ''){
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Introduza ambas as palavras-passe.</div>';
				exit();
			}
			
			// Verifique se o nome de usuário está disponívele
			$usernameCheckingSql = 'SELECT * FROM user WHERE username = :username';
			$usernameCheckingStatement = $conn->prepare($usernameCheckingSql);
			$usernameCheckingStatement->execute(['username' => $resetPasswordUsername]);
			
			if($usernameCheckingStatement->rowCount() < 1){
				// O nome de usuário não existe. Portanto, não é possível redefinir a senha
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>O username não existe.</div>';
				exit();
			} else {
				// Verifique se as palavras-passe são iguais
				if($resetPasswordPassword1 !== $resetPasswordPassword2){
					echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>As palavras-passe não correspondem.</div>';
					exit();
				} else {
					// Comece a ATUALIZAR a senha para o banco de dados
					//Criptografar a senha
					$hashedPassword = md5($resetPasswordPassword1);
					$updatePasswordSql = 'UPDATE user SET password = :password WHERE username = :username';
					$updatePasswordStatement = $conn->prepare($updatePasswordSql);
					$updatePasswordStatement->execute(['password' => $hashedPassword, 'username' => $resetPasswordUsername]);
					
					echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Redefinição de palavra-passe concluída. Por favor, faça login com a sua nova senha.</div>';
					exit();
				}
			}
		} else {
			// Um ou mais campos obrigatórios estão vazios. Portanto, exiba uma mensagem de erro
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Introduza todos os campos marcado com um (*).</div>';
			exit();
		}
	}
?>