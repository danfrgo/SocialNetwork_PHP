<?php
class User {
	private $user;
	private $con;

	public function __construct($con, $user) { # cria todos os objectos abaixo para a classe User (definida acima)
		$this->con = $con;
		$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$user' "); 
		$this->user = mysqli_fetch_array($user_details_query); 
	}

	public function getUsername() {
		return $this->user['username'];
	}

	public function getNumPosts() { # contar o numero de posts por utilizador
		$username = $this->user['username'];
		$query = mysqli_query($this->con, "SELECT num_posts FROM users WHERE username ='$username' ");
		$row = mysqli_fetch_array($query);
		return $row['num_posts'];
	}


	# apresentar o primeiro e ultimo nome por baixo do "faça o seu comentario"
	public function getFirstAndLastName() { # podemos usar este codigo e adapta-lo para retornar o nome de um utilizador, email, retornar o numero de amigos em comum ou até numero de posts
		$username = $this->user['username']; 
		$query = mysqli_query($this->con, "SELECT first_name, last_name FROM users WHERE username='$username' "); # 
		$row = mysqli_fetch_array($query);
		return $row['first_name'] . " " . $row['last_name']; 
	}

	public function isClosed() {
		$username = $this->user['username']; 
		$query = mysqli_query($this->con,"SELECT user_closed FROM users WHERE username = '$username'" );
		$row = mysqli_fetch_array($query);

		if($row['user_closed'] == 'yes')
			return true;
		else
			return false;
	}

}


?>