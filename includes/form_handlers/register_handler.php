<?php


$fname = ""; // nome proprio
$lname = ""; // sobrenome
$em = ""; // email
$em2= ""; // email 2
$password = ""; // password
$password2 = ""; // password 2
$date = ""; // Sign up date ( Data de registo no website)
$error_array = array(); // holds error messages ( para passar a informaçao dos erros)


if(isset($_POST['register_button'])){

	// registration form values

	// strip_tags serve para questões de segurança
	$fname = strip_tags($_POST['reg_fname']); // remover html tags
	$fname = str_replace(' ', '', $fname); // remover espaços
	$fname = ucfirst(strtolower($fname)); 
	$_SESSION['reg_fname'] = $fname; 


	$lname = strip_tags($_POST['reg_lname']);
	$lname = str_replace(' ', '', $lname); 
	$lname = ucfirst(strtolower($lname));
	$_SESSION['reg_lname'] = $lname; 

	$em = strip_tags($_POST['reg_email']); 
	$em = str_replace(' ', '', $em);
	$em = ucfirst(strtolower($em)); 
	$_SESSION['reg_email'] = $em; 

	$em2 = strip_tags($_POST['reg_email2']); 
	$em2 = str_replace(' ', '', $em2);
	$em2 = ucfirst(strtolower($em2));
	$_SESSION['reg_email2'] = $em2;


	$password = strip_tags($_POST['reg_password']);
	$password2 = strip_tags($_POST['reg_password2']);

	$date = date("Y-m-d"); // data atual
//-//
if($em == $em2){
	// check if email is in valid format ( checka se o email está no formato correto)

	if(filter_var($em,FILTER_VALIDATE_EMAIL)) {
		$em = filter_var($em,FILTER_VALIDATE_EMAIL);

		// checkar se o email já está em uso
		$e_check = mysqli_query($con, "SELECT email FROM users WHERE email = '$em' ");

		//count the number of rows returned 
		$num_rows = mysqli_num_rows($e_check);	

		if($num_rows > 0 ){
			array_push($error_array,"O email introduzido já está em uso<br>" ) ; // guardar as mensagens de erro
		}

	} 
	else {
		array_push($error_array, "Formato de email invalido<br>"); // guardar as mensagens de erro
	}
}
else {
array_push($error_array, "Os emails introduzidos são diferentes diferentes<br>"); // guardar as mensagens de erro

} 

if(strlen($fname) > 25 || strlen($fname) < 2 ) {
	array_push($error_array, "O teu nome deve ter entre 2 a 25 caracteres<br>"); // guardar as mensagens de erro
	}

if(strlen($lname) > 25 || strlen($lname) < 2 ) {
	array_push($error_array, "O teu sobrenome deve ter entre 2 a 25 caracteres<br>"); // guardar as mensagens de erro
	}

// passwords match 
if($password != $password2) {
	array_push($error_array, "As passwords digitadas são diferentes<br>"); // guardar as mensagens de erro
} 
else {
	if(preg_match('/[^A-Za-z0-9]/', $password)){
		array_push($error_array, "A password só pode conter letras ou numeros<br>"); // guardar as mensagens de erro
	}
}

if(strlen($password > 30 || strlen($password) < 5 )) {
	array_push($error_array, "A password deve ter entre 5 a 30  caracteres<br>"); // guardar as mensagens de erro
}


if(empty($error_array)){

// password encriptada
$password = md5($password); 


$username = strtolower($fname . "_" . $lname);
$check_username_query = mysqli_query($con,"SELECT username FROM users WHERE username = '$username'");


$i = 0;

while (mysqli_num_rows($check_username_query) != 0){

	$i++; //add 1 to i
	$username = $username . "_" . $i;
	$check_username_query = mysqli_query($con,"SELECT username FROM users WHERE username = '$username'");


}

$rand = rand(1,2);

if($rand == 1)
	$profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
else if ($rand == 2)
	$profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";

$query = mysqli_query($con, "INSERT INTO users VALUES ('','$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0','no', ',')");


array_push($error_array, "<span style='color: #14C800;'> Registo efetuado! Podes fazer login! </span><br>");

//limpar sessao de variaveis quando se clica em registar
$_SESSION['reg_fname'] = "";
$_SESSION['reg_lname'] = "";
$_SESSION['reg_email'] = "";
$_SESSION['reg_email2'] = "";

}

}

?>