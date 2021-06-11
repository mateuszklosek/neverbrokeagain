<?php

session_start();

if(isset($_SESSION['logged_id'])) {
header('Location: main.php');	
exit();
} 

?>

<!DOCTYPE html>
<html lang="pl">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>Logowanie</title>
	<meta name="description" content="Aplikacja do zarządzania finansami.">
	<meta name="keywords" content="pieniadze, finanse, zarzadzanie, oszczedzanie">
	<meta name="author" content="Mateusz Kłosek">
	
	<meta http-equiv="X-Ua-Compatible" content="IE=edge, chrome=1">
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<link rel="stylesheet" href="main.css">
	<link rel="stylesheet" href="css/fontello.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Chango&display=swap" rel="stylesheet">
	
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
						<h1 class="h4" style="margin-top:45px;"> Logowanie użytkownika </h1>
						</header>
						
						<form class="row text-center" method="post" action="main.php">
						
							<div class="input-center">
								<span class="span-style">
									<i class="demo-icon icon-mail-alt"></i> 
								</span>
								<div class="input-style" style="float: left;">
										<input type="email" class="form-control" placeholder="E-mail" aria-label="email" name="email" <?= isset($_SESSION['bad_attempt']) ? 'value="'.$_SESSION['given_email'] . '"' : '' ?> required>
								</div>
								<div class="clearclass">
								</div>
							</div>
							
							
							<div class="input-center">
								<span class="span-style">
									<i class="demo-icon icon-lock"></i>
								</span>
								<div class="input-style" style="float: left;">
									<input type="password" class="form-control" placeholder="Hasło" aria-label="hasło" name="password"  required>
								</div>
								<div class="clearclass">
								</div>
							</div>
								
							<div style="margin-top:15px; width: 100%;" >
								<input type="checkbox" class="custom-control-input" id="rememberMeCheckbox" name="remember_me" checked="checked" >
								<label class="custom-control-label" for="rememberMeCheckbox"> Zapamiętaj mnie!</label>
							</div>
							
							<div class="input-center">
								<input type="submit"  value="Zaloguj się">
							</div>
							
							
							<?php
									if (isset($_SESSION['bad_attempt'])) {
										echo '<p class="input-center mt-2 text-danger">Niepoprawny login lub hasło!</p>';
										unset($_SESSION['bad_attempt']);
									}
								?>

						</form>
						
						<span class="menu-span">
							<a href="/signup/index">Zapomniałes hasła?</a>
						</span>
						
						<span class="menu-span mb-4">
							Nie masz konta? <a href="registration.php">Zarejestruj się.</a>
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