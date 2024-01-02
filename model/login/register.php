<?php
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	$registerFullName = '';
	$registerUsername = '';
	$registerPassword1 = '';
	$registerPassword2 = '';
	$hashedPassword = '';
	
	if(isset($_POST['registerUsername'])){
		$registerFullName = htmlentities($_POST['registerFullName']);
		$registerUsername = htmlentities($_POST['registerUsername']);
		$registerPassword1 = htmlentities($_POST['registerPassword1']);
		$registerPassword2 = htmlentities($_POST['registerPassword2']);
		
		if(!empty($registerFullName) && !empty($registerUsername) && !empty($registerPassword1) && !empty($registerPassword2)){
			
			// scanizar o nome
			$registerFullName = filter_var($registerFullName, FILTER_SANITIZE_STRING);
			
			// checkar se o nome esta vazio
			if($registerFullName == ''){
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Introduza o seu nome.</div>';
				exit();
			}
			
			// checkar se o username esta vazio
			if($registerUsername == ''){
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Introduza o seu nome de utilizador.</div>';
				exit();
			}
			
			// checkar se ambas as password estao vazias
			if($registerPassword1 == '' || $registerPassword2 == ''){
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Introduza ambas as palavras-passe.</div>';
				exit();
			}
			
			// checkar se o username é valido
			$usernameCheckingSql = 'SELECT * FROM user WHERE username = :username';
			$usernameCheckingStatement = $conn->prepare($usernameCheckingSql);
			$usernameCheckingStatement->execute(['username' => $registerUsername]);
			
			if($usernameCheckingStatement->rowCount() > 0){
				// O nome de usuário já existe. Portanto, não é possível criar um novo usuário
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Nome de usuário não disponível. Selecione um nome de usuário diferente.</div>';
				exit();
			} else {
				// Verifique se as palavras-passe são iguais
				if($registerPassword1 !== $registerPassword2){
					echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>As palavras-passe não correspondem.</div>';
					exit();
				} else {
					// Comece a inserir o usuário no banco de dados
					//Criptografar a senha
					$hashedPassword = md5($registerPassword1);
					$insertUserSql = 'INSERT INTO user(fullName, username, password) VALUES(:fullName, :username, :password)';
					$insertUserStatement = $conn->prepare($insertUserSql);
					$insertUserStatement->execute(['fullName' => $registerFullName, 'username' => $registerUsername, 'password' => $hashedPassword]);
					
					echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Registo concluído!</div>';
					exit();
				}
			}
		} else {
			// Um ou mais campos obrigatórios estão vazios. Portanto, exiba uma mensagem de erro
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Introduza todos os campos marcados com um (*).</div>';
			exit();
		}
	}
?>