<?php
	session_start();
	require_once('../../inc/config/constants.php');
	require_once('../../inc/config/db.php');
	
	$loginUsername = '';
	$loginPassword = '';
	$hashedPassword = '';
	
	if(isset($_POST['loginUsername'])){
		$loginUsername = $_POST['loginUsername'];
		$loginPassword = $_POST['loginPassword'];
		
		if(!empty($loginUsername) && !empty($loginUsername)){
			
			// Scaniza username
			$loginUsername = filter_var($loginUsername, FILTER_SANITIZE_STRING);
			
			// Verifique se o nome de usuário está vazio
			if($loginUsername == ''){
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Por favor, insira o nome de usuário</div>';
				exit();
			}
			
			// Verifique se a senha está vazia
			if($loginPassword == ''){
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Por favor, digite a senha</div>';
				exit();
			}
			
			// Criptografar a password
			$hashedPassword = md5($loginPassword);
			
			// Verifique as credenciais fornecidas
			$checkUserSql = 'SELECT * FROM user WHERE username = :username AND password = :password';
			$checkUserStatement = $conn->prepare($checkUserSql);
			$checkUserStatement->execute(['username' => $loginUsername, 'password' => $hashedPassword]);
			
			// Verifique se o usuário existe ou não
			if($checkUserStatement->rowCount() > 0){
				// Credenciais válidas. Assim, inicie a sessão
				$row = $checkUserStatement->fetch(PDO::FETCH_ASSOC);

				$_SESSION['loggedIn'] = '1';
				$_SESSION['fullName'] = $row['fullName'];
				
				echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Login com successo! Redirecting  você para a página inicial...</div>';
				exit();
			} else {
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Username / Password Incorretos</div>';
				exit();
			}
			
			
		} else {
			echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Por favor, digite o nome de usuário e a senha</div>';
			exit();
		}
	}
?>