<?php 

ob_start(); // turn on output buffering (ativa o buffer de saida) - https://www.php.net/manual/pt_BR/function.ob-start.php

session_start();

$timezone = date_default_timezone_set("Europe/Lisbon");

$con = mysqli_connect("localhost", "root", "", "social");

if(mysqli_connect_errno()){

	/* mysqli_connect_errno() serve para determinar o erro ocorrido */
	echo "Falha de conexao: " . mysqli_connect_errno();
}