<?php
class Post {
	private $user_obj;  
	private $con;

	public function __construct($con, $user) { # cria todos os objectos abaixo para a classe Post (definida acima)
		$this->con = $con;
		$this->user_obj = new User($con, $user);
	}



	public function submitPost($body, $user_to) { 
		$body = strip_tags($body); #remove as tags HTML
		$body = mysqli_real_escape_string($this->con, $body); /*  $item = "Zak's and Derick's Laptop"; Escaped string: Zak\'s and Derick\'s Laptop */

		$body = str_replace('\r\n','\n', $body); // line break - 2 linhas de codigo
		$body = nl2br($body);  // line break - 2 linhas de codigo
		$check_empty = preg_replace('/\s+/', '', $body); #apaga todos os espaços

		if($check_empty != "") {
			# se o utilizador tentar comentar em branco nao ira conseguir
			# o mesmo se passa se o utilizar carregar no space para dar varios espaços, o sistema ira considerar como estanto vazio, nao podendo assim comentar



			// data atual e hora
			$date_added = date("Y-m-d H:i:s"); 

		
			$added_by = $this->user_obj->getUsername();

			// se o user estive no proprio perfil, o utilizador destino nao é ninguem
			if($user_to == $added_by) {
				$user_to = "none";
			}

			// inserir post
			$query = mysqli_query($this->con, "INSERT INTO posts VALUES('', '$body', '$added_by', '$user_to', '$date_added', 'no', 'no', '0') "); # as primeiras '' ficam em branco pois sao para o ID
			$returned_id = mysqli_insert_id($this->con); // retorna que o post foi submetido

			//inserir notificaçoes




			// update contagem de posts do utilizador
			$num_posts = $this->user_obj->getNumPosts();   // vai retornar o numero de posts
			$num_posts++; // contador
			$update_query = mysqli_query($this->con, "UPDATE users SET num_posts = '$num_posts' WHERE username = '$added_by' ");


		}

	}

	public function loadPostsFriends($data, $limit) {
		$page = $data['page'];
		$userLoggedIn = $this->user_obj->getUsername();


		if($page == 1) 
			$start = 0;
		else
			$start = ($page - 1) * $limit; // o limit é 10


		$str = ""; // string a retornar
		$data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted = 'no' ORDER BY id DESC ");

		if(mysqli_num_rows($data_query) > 0) { 


			$num_iterations = 0; //numero de resultados checkados  (nao necessariamente postados)
			$count = 1; // conta quantos resultados foram carregados

			while($row = mysqli_fetch_array($data_query)) {
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];

			// Prepare user_to string so it can be included even if not posted to a user
			if($row['user_to'] == "none") {
				$user_to = "";
				} else {
					$user_to_obj = new User($con, $row['user_to']);
					$user_to_name = $user_to_obj->getFirstAndLastName();
					$user_to = "to <a href='" . $row['user_to'] ."'>" . $user_to_name . "</a>";
				}
				
			// verificar se o user que fez um post tem a conta fechada
			$added_by_obj = new User($this->con, $added_by);
			if($added_by_obj->isClosed()) {
				continue;
			}



			if($num_iterations++ < $start)
				continue;


			// once 10 posts have been loaded , break
			if($count > $limit) {
				break;
			} else {
				$count++;
			}


			$user_details_query = mysqli_query($this->con,"SELECT first_name, last_name, profile_pic FROM users WHERE username = '$added_by' ");
			$user_row = mysqli_fetch_array($user_details_query);
	 		$first_name = $user_row['first_name'];
	 		$last_name = $user_row['last_name'];
	 		$profile_pic = $user_row['profile_pic'];

			// timeframe mes hora minutos e segundos de cada post
			$date_time_now = date("Y-m-d H:i:s");
			$start_date = new DateTime($date_time); // hora do comentario //DateTime é uma classe predefinida pelo PHP
			$end_date = new Datetime($date_time_now); // hora atual
			$interval  = $start_date->diff($end_date); // diferença entre as horas
			if($interval->y >= 1) {
				if($interval ==1)
					$time_message = $interval->y . " 1 ano atras ";
				else 
					$time_message = $interval->y . " anos atras ";
			}
			else if($interval->m >= 1){
				if($interval->d == 0) {
					$days = "  atras ";
				}
				else if($interval->d == 1) {
					$days = $interval->d . " 1 dia atras  ";

				}
					else {
					$days = $interval->d . " dias atras ";
					
				}

				if($interval->a == 1) {
					$time_message = $interval->m . " mes " . $days;				
				} else {
					$time_message = $interval->m . " meses " . $days;	
				}

			}
			 else if($interval->d >= 1) {
			 	if($interval->d == 1) {
					$time_message =  " Ontem ";

				}
					else {
					$time_message = " dias atras ";
					

			}

		}
		else if($interval->h >= 1) {
			if($interval->h == 1) {
					$time_message = $interval->h . " 1 hora atras  ";

				}
					else {
					$time_message = $interval->h . " horas atras ";

				}
				}


				else if($interval->i >= 1) {
					if($interval->i == 1) {
							$time_message = $interval->i . " 1 minuto atras  ";

						}
							else {
							$time_message = $interval->i . " minutos atras ";

				}
				}

				else {

					if($interval->s < 30) {
							$time_message = " Agora  ";

						}
							else {
							$time_message = $interval->s . " segundos atras ";

				}
				}

				$str .= "<div class='status_post'>
							<div class='post_profile_pic'>
								<img src='$profile_pic' width='50'>
							</div>	

							<div class='posted_by' style='color:#ACACAC;'>
								<a href='$added_by'> $first_name $last_name </a> $user_to &nbsp;&nbsp;&nbsp;&nbsp;$time_message
							</div>	
							<div id='post_body'>
								$body
								<br>
							</div>


						</div>	
						<hr>
						" ;
			}

			if($count > $limit) 
				$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							<input type='hidden' class='noMorePosts' value='false'>";
			else 
				$str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre;'> Nao ha mais posts para mostrar! </p>";

		}
			

			echo $str;


			}



	}


?>