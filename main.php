<?php
session_start();

require_once 'database.php';

if(!isset($_SESSION['logged_id'])) {

	if (isset($_POST['email'])) {
		
		$login = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		$password = filter_input(INPUT_POST, 'password');
		
		$_SESSION['given_email'] = $_POST['email'];
		
			//echo $login . " " . $password;

		$userQuery = $db->prepare('Select id, password FROM users WHERE email = :login');
		$userQuery->bindValue(':login', $login, PDO::PARAM_STR);
		$userQuery->execute();
		
		//echo $userQuery->rowCount();
		
		$user = $userQuery->fetch();
		
		//echo $user['id'] . " " . $user['password'];
		
		if ($user && password_verify($password, $user['password'])){
		$_SESSION['logged_id'] = $user['id'];
		unset($_SESSION['bed_attempt']);
		unset($_SESSION['given_email']);
		} else {
			$_SESSION['bad_attempt'] = true;
			header('Location: index.php');
			exit(); 
		} 
		

		

	} else {	
		header('Location: index.php');
		exit();	
	}
}

$usersQuery = $db->query('SELECT * FROM users');
$users = $usersQuery->fetchAll();

//print_r($users);
 
?>

<!DOCTYPE html>
<html lang="pl">

<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>Strona głowna</title>
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

	<header>
	
		<div class="text-center">
		
			<div class="text-center my-5 mx-3">
				<a href="main.html"><img class="img-fluid  border rounded"  src="img/Logo1.png" alt="logo"></a>
			</div>
		
		</div>
		
		<nav class="navbar navbar-light bg-light navbar-expand-lg text-center">
		
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Przełącznik nawigacji">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div class="collapse navbar-collapse" id="mainmenu">
			
				<ul class="navbar-nav mr-auto">
				
					<li class="nav-item">
						<a href="main.php">Strona główna</a>
					</li>
					<li class="nav-item">
						<a href="incomes.php">Dodaj przychód</a>
					</li>
					<li class="nav-item">	
						<a href="expenses.php">Dodaj wydatek</a>
					</li>
					<li class="nav-item">
						<a href="balance.php">Przeglądaj bilans</a>
					</li>
					<li class="nav-item">
						<a href="settings.php">Ustawienia</a>
					</li>
					<li class="nav-item">
						<a href="logout.php">Wyloguj się</a>
					</li>
					
				</ul>
			
			</div>
			
		</nav>
			
		</header>
		
		<main>
	
			<section>
			
				<div class="container col-11 col-lg-8 text-center mt-5">
					
					<header>
						<h3 class="h4 mt-4">Witaj w aplikacji Never Broke Again! </h3>
					</header>
			
				
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eget rhoncus mi, in vestibulum lorem. Nulla urna mauris, egestas nec erat vel, tempus ullamcorper dolor. Maecenas eu mattis arcu. Aliquam dapibus quis risus eget consequat. Curabitur eu convallis urna, vitae scelerisque est. Nunc eget posuere urna. Nulla facilisi. Phasellus blandit eleifend aliquet. Curabitur porttitor pharetra pretium. Nam ac eros laoreet, consequat felis at, auctor metus.</p>
					
					<p>Etiam condimentum sed lectus at laoreet. Fusce pellentesque porta purus a venenatis. Quisque erat augue, malesuada nec ultrices vitae, consequat sed metus. Donec at ipsum viverra mauris feugiat euismod. Morbi ultrices tellus libero, et gravida tortor laoreet eget. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi auctor interdum ornare. Praesent vel urna volutpat, accumsan erat at, pharetra urna. Pellentesque egestas sodales nibh vitae sodales. Suspendisse laoreet risus neque, viverra dictum leo condimentum vitae. Sed sem diam, blandit eu vestibulum in, tempor nec lacus. Nullam lacinia commodo elit, sed euismod leo. Suspendisse porttitor sem mi, fringilla viverra diam tincidunt ut.</p>
					
					<p>In dui turpis, varius nec neque id, mollis cursus neque. Pellentesque eget laoreet nulla. Nam lectus ex, vehicula ut euismod et, rhoncus in lectus. Donec luctus, sapien a venenatis vulputate, sapien ante condimentum lectus, ut molestie enim velit vitae magna. Suspendisse varius neque pulvinar enim ornare, nec lobortis enim lobortis. Ut eu ex neque. Vestibulum feugiat ligula et arcu rhoncus, quis maximus mauris pellentesque. Vivamus fermentum ultrices lacus vel vulputate. Morbi ultrices dolor nulla, ac lobortis nisl vestibulum sed. Vestibulum iaculis, lectus eget condimentum sodales, lorem nulla fermentum tellus, volutpat congue lacus dolor et quam. Phasellus ac risus blandit nisi rutrum suscipit non eu mauris. Vestibulum fringilla non neque vitae vestibulum.</p>
					
					<p>Fusce quis vehicula purus, ut fermentum quam. Suspendisse cursus dui ac est convallis, sit amet egestas lorem sodales. Praesent nec nunc mattis, hendrerit mauris quis, dignissim nisi. Pellentesque semper faucibus urna vel tempus. Suspendisse egestas lacus ornare ligula mattis, et pulvinar urna sodales. Suspendisse tristique eget lacus sit amet dapibus. Nam quis imperdiet velit. Vestibulum consectetur rutrum tortor, sit amet fringilla nisi rhoncus id. Aenean sit amet odio elit. Nulla orci quam, eleifend quis sapien sed, vestibulum elementum urna. Sed dapibus ligula vitae turpis bibendum, in tempus magna bibendum. Aenean ut purus diam. Praesent porta velit ut dui fringilla egestas. Donec dignissim non sapien at imperdiet. Quisque bibendum massa ligula, vel elementum eros iaculis quis. Maecenas velit nisl, imperdiet vitae dui sed, convallis placerat enim.</p>
					
					<p>Nunc mollis, massa scelerisque elementum condimentum, mauris ipsum accumsan purus, in semper leo erat vel turpis. Etiam varius feugiat diam eu sagittis. Curabitur dapibus sollicitudin dictum. In tincidunt at mauris vel dictum. Vivamus id imperdiet sem. Nam viverra ac massa ac ultricies. Nam condimentum commodo faucibus. Integer eget facilisis massa, sit amet vulputate purus. Duis in eros pulvinar eros porttitor pellentesque non volutpat dui. Nam laoreet scelerisque leo, accumsan porttitor tortor dignissim tempus. Nunc sit amet rutrum lorem. Cras malesuada risus sit amet aliquet vestibulum. Donec tellus nibh, pretium sed diam vitae, aliquet tempus risus.</p>
					
					<p>Nunc mollis, massa scelerisque elementum condimentum, mauris ipsum accumsan purus, in semper leo erat vel turpis. Etiam varius feugiat diam eu sagittis. Curabitur dapibus sollicitudin dictum. In tincidunt at mauris vel dictum. Vivamus id imperdiet sem. Nam viverra ac massa ac ultricies. Nam condimentum commodo faucibus. Integer eget facilisis massa, sit amet vulputate purus. Duis in eros pulvinar eros porttitor pellentesque non volutpat dui. Nam laoreet scelerisque leo, accumsan porttitor tortor dignissim tempus. Nunc sit amet rutrum lorem. Cras malesuada risus sit amet aliquet vestibulum. Donec tellus nibh, pretium sed diam vitae, aliquet tempus risus.</p>
					
					<p>Nunc mollis, massa scelerisque elementum condimentum, mauris ipsum accumsan purus, in semper leo erat vel turpis. Etiam varius feugiat diam eu sagittis. Curabitur dapibus sollicitudin dictum. In tincidunt at mauris vel dictum. Vivamus id imperdiet sem. Nam viverra ac massa ac ultricies. Nam condimentum commodo faucibus. Integer eget facilisis massa, sit amet vulputate purus. Duis in eros pulvinar eros porttitor pellentesque non volutpat dui. Nam laoreet scelerisque leo, accumsan porttitor tortor dignissim tempus. Nunc sit amet rutrum lorem. Cras malesuada risus sit amet aliquet vestibulum. Donec tellus nibh, pretium sed diam vitae, aliquet tempus risus.</p>
					

				</div>
			</section>
			
		</main>
		
		<footer class="text-center mb-5" style="font-size: 12px;">
			All rights reserved © 2021, Never broke again created by Mati
		</footer>



	

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

</body>
</html>