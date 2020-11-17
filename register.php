<?php
require 'config/config.php';
require 'includes/form_handlers/register_handler.php';
require 'includes/form_handlers/login_handler.php';
?>


<html>
<head>
	<title>Welcome to Swirlfeed!</title>
	<link rel="stylesheet" type="text/css" href="assets/css/register_style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="assets/js/register.js"></script>
</head>
<body>

<?php
	if(isset($_POST['register_button'])){
		echo ' 
		<script>
		$(document).ready(function() {
			$("#first").hide();
			$("#second").show();

		});
		</script>
		' ;
	}

?>


<div class="wrapper">



	<div class="login_box">

		<div class="login_header">
		<h1>Swirlfeed!</h1>
		Faça login ou registe-se abaixo!
	 	</div>

	 	<div id="first">
	 		
	 		<form  action="register.php" method="POST">
					<input type="email" name="log_email" placeholder="Endereço de email" value="<?php 
					if(isset($_SESSION['log_email'])) {
					echo $_SESSION['log_email']; 
					} ?>" required>
					<br>
					<input type="password" name="log_password" placeholder="Password">
					<br>
					<?php if( in_array("Email ou password incorreta<br>", $error_array)) echo "Email ou password incorreta<br>"; ?>
					<input type="submit" name="login_button" value="Login">
					<br>
					<a href="#" id="signup" class="signup">Precisa de uma conta? Registe-se aqui!</a>
			</form>


	 	</div>

		




	 	<div id="second">
			<form action="register.php" method="POST"> 
					<input type="text" name="reg_fname" placeholder="Nome Proprio" 
					value="<?php if(isset($_SESSION['reg_fname'])) {
						echo $_SESSION['reg_fname'];
					} ?>" required>

					<?php if(in_array("O teu nome deve ter entre 2 a 25 caracteres<br>", $error_array)) echo "O teu nome deve ter entre 2 a 25 caracteres<br>";    ?>	
					<br>
					<input type="text" name="reg_lname" placeholder="Sobrenome" 	
					value="<?php if(isset($_SESSION['reg_lname'])) {
						echo $_SESSION['reg_lname'];
					} ?>" required>

					<?php if(in_array("O teu sobrenome deve ter entre 2 a 25 caracteres<br>", $error_array)) echo "O teu sobrenome deve ter entre 2 a 25 caracteres<br>"; ?>
					
					<br>

					<input type="email" name="reg_email" placeholder="Email"
						value="<?php if(isset($_SESSION['reg_email'])) {
						echo $_SESSION['reg_email'];
	
					} ?>"  required>

					<br>

					<input type="email" name="reg_email2" placeholder="Confirmar email" 
							value="<?php if(isset($_SESSION['reg_email2'])) {
						echo $_SESSION['reg_email2'];
					} ?>"  required>
					<br>
					<?php if(in_array("O email introduzido já está em uso<br>", $error_array)) echo "O email introduzido já está em uso<br>";
					else if(in_array("Formato de email invalido<br>", $error_array)) echo "Formato de email invalido<br>";
					else if(in_array("Os emails introduzidos são diferentes diferentes<br>", $error_array)) echo "Os emails introduzidos são diferentes diferentes<br>";?>


					<br>

					<input type="password" name="reg_password" placeholder="Password" required>
					<br>

					<input type="password" name="reg_password2" placeholder="Confirmar Password" required>
					<br>

					<?php if(in_array("As passwords digitadas são diferentes<br>", $error_array)) echo "As passwords digitadas são diferentes<br>";
					else if(in_array("A password só pode conter letras ou numeros<br>", $error_array)) echo "A password só pode conter letras ou numeros<br>";
					else if(in_array("A password deve ter entre 5 a 30  caracteres<br>", $error_array)) echo "A password deve ter entre 5 a 30  caracteres<br>";?>



					<input type="submit" name="register_button" value="Register">
					<br>


				<?php if(in_array("<span style='color: #14C800;'> Registo efetuado! Podes fazer login! </span><br>", $error_array)) echo "<span style='color: #14C800;'> Registo efetuado! Podes fazer login! </span><br>"; ?>

				<a href="#" id="signin" class="signin">Já tem uma conta? Faça login aqui!</a>

			</form>
		</div>


		</div>

	
</div>


</body>
</html>