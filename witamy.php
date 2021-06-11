<?php

	session_start();
	
	if (!isset($_SESSION['udanarejestracja']))
	{
		header('Location: index.php');
		exit();
	}
	else
	{
		unset($_SESSION['udanarejestracja']);
	}

	
	
	//Usuwanie zmiennych pamiętających wartości wpisane do formularza
	if (isset($_SESSION['fr_imie'])) unset($_SESSION['fr_imie']);
	if (isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
	if (isset($_SESSION['fr_haslo1'])) unset($_SESSION['fr_haslo1']);
	if (isset($_SESSION['fr_haslo2'])) unset($_SESSION['fr_haslo2']);
	
	//Usuwanie błędów rejestracji
	if (isset($_SESSION['e_imie'])) unset($_SESSION['e_imie']);
	if (isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
	if (isset($_SESSION['e_haslo'])) unset($_SESSION['e_haslo']);
	if (isset($_SESSION['e_bot'])) unset($_SESSION['e_bot']);
	
?>
<!DOCTYPE html>
<html lang="pl">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>Witamy!</title>
	<meta name="description" content="Aplikacja do zarządzania finansami.">
	<meta name="keywords" content="pieniadze, finanse, zarzadzanie, oszczedzanie">
	<meta name="author" content="Mateusz Kłosek">
	
	<meta http-equiv="X-Ua-Compatible" content="IE=edge, chrome=1">
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<link rel="stylesheet" href="main.css">
	<link rel="stylesheet" href="css/fontello.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Chango&display=swap" rel="stylesheet">
	<script src="script.js"></script>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</script>
	
	

	
	<!--[if lt IE 9]>
	<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<![endif]-->
	
</head>

<body>


	<main>
	
		<div class="container col-11 col-lg-6 col-xl-4 text-center">
		
			<header>
			<img class="img-fluid mt-5 border rounded" src="img/Logo1.png" alt="logo">
				<!-- <h1>NEVER BROKE AGAIN</h1>-->
			</header>
	
			<section>
				
				<div class="row">
				
					<header style="width: 100%" class="text-center">
						<h1 class="h4" style="margin-top:45px;"> Gratulacje! Konto zostało utworzone! </h1>
					</header>	

					<span class="menu-span mb-4 h1">
						<a href="index.php">Zaloguj się!</a>
					</span>
				
				</div>
			
			</section>
			
		</div>
	
	</main>
	<footer class="text-center" style="font-size: 12px;">
			All rights reserved © 2021, Never broke again created by Mati
	</footer>
	
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

</body>


</html>