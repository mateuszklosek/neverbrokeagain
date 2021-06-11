<?php
session_start();


if(isset($_SESSION['logged_id'])) {
	
	
	require_once 'database.php';
	
	$user_id = $_SESSION['logged_id'];
	

	$categoriesQuery = $db->prepare('SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id = :user_id');
	$categoriesQuery->bindValue(':user_id', $user_id, PDO::PARAM_INT);
	$categoriesQuery->execute();
	$categories = $categoriesQuery ->fetchAll();
	
	

	if (isset($_POST['amount'])) {
	
		$amount = $_POST['amount'];
		$date_of_income = $_POST['incomeDate'];
		$income_comment = $_POST['comment'];
		$incomeCategory = $_POST['incomesCategory'];
		
		$query = $db->prepare('INSERT INTO incomes VALUES (NULL, :user_id, :income_category_assigned_to_user_id, :amount, :date_of_income, :income_comment)');
		$query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$query->bindValue(':income_category_assigned_to_user_id', $incomeCategory, PDO::PARAM_STR);
		$query->bindValue(':amount', $amount, PDO::PARAM_STR);
		$query->bindValue(':date_of_income', $date_of_income, PDO::PARAM_STR);
		$query->bindValue(':income_comment', $income_comment, PDO::PARAM_STR);
		$query->execute();
		$_SESSION['przychod_dodany']="Przychód został dodany pomyślnie!"; 
		
	}

		

	} else {	
		header('Location: index.php');
		exit();	
	}
?>


<!DOCTYPE html>
<html lang="pl">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>Dodawanie przychodu</title>
	<meta name="description" content="Aplikacja do zarządzania finansami.">
	<meta name="keywords" content="pieniadze, finanse, zarzadzanie, oszczedzanie">
	<meta name="author" content="Mateusz Kłosek">
	
	<meta http-equiv="X-Ua-Compatible" content="IE=edge,chrome=1">
	
	<script src="script.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<link rel="stylesheet" href="main.css">
	<link rel="stylesheet" href="css/fontello.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Chango&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
	
	
	
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
		
		<nav class="navbar navbar-light bg-light navbar-expand-md text-center">
		
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
			
			<div class="container col-11 col-lg-5 text-center mt-5">
			
				<header>
						<h3 class="h4 mt-4">Dodawanie przychodu</h3>
				</header>
				
				
				
					<form method="post">
					
					<?php
								if (isset($_SESSION['przychod_dodany']))
								{
									echo '<div class="text-success center mt-2">'.$_SESSION['przychod_dodany'].'</div>';
									unset($_SESSION['przychod_dodany']);
								}
						?>

					<div class="input-center">
						<span class="span-style">
							<i class="bi bi-wallet-fill"></i>
						</span>
						<div class="input-style" style="float: left;">
							<input type="number" class="form-control"  placeholder="Kwota" min="0" max="999999.99" step="0.01" aria-label="kwota" id="amount" name="amount" value="Kwota" required autofocus >
						</div>
						<div class="clearclass">
						</div>
					</div>
					
					<div class="input-center">
						<span class="span-style">
							<i class="bi bi-calendar"></i>
						</span>
						<div class="input-style" style="float: left;">
							<input id="transactionDate" type="date" class="form-control" aria-label="data" name="incomeDate"  value="Data" min="2000-01-01" required>
						</div>
						<div class="clearclass">
						</div>
					</div>
					
					<div class="input-center">
						<span class="span-style">
							<i class="bi bi-cart"></i>
						</span>
						<div class="input-style" style="float: left;">
							<select id="expensesCategory" data-live-search="true" name="incomesCategory">
								<option  selected disabled>Kategoria:</option>
								<?php
									foreach ($categories as $category) {
										echo "<option value='{$category["id"]}'> {$category["name"]} </option>";
									}
								?>
							
								<!-- <option value="0" selected disabled>Kategoria:</option>
								<option value="1">Wynagrodzenie</option>
								<option value="2">Odsetki bankowe</option>
								<option value="3">Sprzedaż internetowa</option>
								<option value="4">Prezent</option>
								<option value="5">Inne</option>	-->				
							</select>
						</div>
						<div class="clearclass">
						</div>	
					</div>
					
						<div style="margin-top: 15px;">
							<div><label for="comment"> Komentarz (opcjonalnie):</label></div>
							<textarea  name="comment" id="comment" rows="4" cols="80" maxlength="120" minlength="1"></textarea>
						</div>
						<div style="margin-bottom: 30px;">
							<input id="a1" type="submit" value="Dodaj">
							<input id="a2" type="reset" value="Anuluj">
						</div>
						
					</form>

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