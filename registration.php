<?php
setLocale(LC_ALL, 'pl-PL');

session_start();

if (isset($_POST['email']))
{
	//Udana walidacja? Załóżmy, że tak!
	$wszystko_OK=true;
	
	//Sprawdź poprawność imienia
		$imie = $_POST['imie'];
		
		//Sprawdzenie długości nicka
		if ((strlen($imie)<3) || (strlen($imie)>20))
		{
			$wszystko_OK=false;
			$_SESSION['e_imie']="Imię musi posiadać od 3 do 20 znaków!";
		}
		
		if (ctype_alpha($imie)==false)
		{
			$wszystko_OK=false;
			$_SESSION['e_imie']="Imię może składać się tylko z liter";
		}
	
	// Sprawdź poprawność adresu email
	$email = $_POST['email'];
	$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
	
	if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
	{
		$wszystko_OK=false;
		$_SESSION['e_email']="Podaj poprawny adres e-mail!";
	}
	
	//Sprawdź poprawność hasła
	$haslo1 = $_POST['passwd1'];
	$haslo2 = $_POST['passwd2'];
	
	if ((strlen($haslo1)<8) || (strlen($haslo1)>20))
	{
		$wszystko_OK=false;
		$_SESSION['e_haslo']="Hasło musi posiadać od 8 do 20 znaków!";
	}
	
	if ($haslo1!=$haslo2)
	{
		$wszystko_OK=false;
		$_SESSION['e_haslo']="Podane hasła nie są identyczne!";
	}	

	$haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);

		//Bot or not? Oto jest pytanie!
	$sekret = "6LewHtwaAAAAAKFDClMm3YHafFc2PyIU3l4i5Qb9";
	
	$sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);
	
	$odpowiedz = json_decode($sprawdz);
	
	if ($odpowiedz->success==false)
	{
		$wszystko_OK=false;
		$_SESSION['e_bot']="Potwierdź, że nie jesteś botem!";
		//echo "Potwierdź, że nie jesteś botem!";
	}		
	
		//Zapamiętaj wprowadzone dane

		$_SESSION['fr_email'] = $email;
		$_SESSION['fr_imie'] = $imie;
		$_SESSION['fr_haslo1'] = $haslo1;
		$_SESSION['fr_haslo2'] = $haslo2;
		
		require_once "database.php";
		
		
		$userQuery = $db->prepare('Select id FROM users WHERE email = :login');
		$userQuery->bindValue(':login', $email, PDO::PARAM_STR);
		$userQuery->execute();
		
		$user = $userQuery->fetch();
		
		if ($user ){
			$wszystko_OK=false;
			$_SESSION['e_email']="Istnieje już konto przypisane do tego maila! Podaj inny adres.";
		}
		
		if ($wszystko_OK==true)
				{
					$query = $db->prepare('INSERT INTO users VALUES (NULL, :nick, :password, :email)');
					$query->bindValue(':nick', $imie, PDO::PARAM_STR);
					$query->bindValue(':password', $haslo_hash, PDO::PARAM_STR);
					$query->bindValue(':email', $email, PDO::PARAM_STR);
					$query->execute();
					
					
					$idQuery = $db->query('SELECT * FROM users ORDER BY id DESC LIMIT 1 ');
					$user_id= $idQuery->fetch();
					
					$_SESSION['e_id']=$user_id['id'];
					
					//pobranie deafoultowych kategorii przychodow
					
					$categoriesQuery = $db->query('SELECT name FROM incomes_category_default');
					$categories = $categoriesQuery->fetchAll();
					
					//przypisanie id usera do kategorii przychodow
					
					foreach ($categories as $category) {
						$UserCategoryQuery = $db->prepare('INSERT INTO incomes_category_assigned_to_users VALUES (NULL, :id, :category)');
						$UserCategoryQuery->bindValue(':id', $user_id['id'], PDO::PARAM_INT);
						$UserCategoryQuery->bindValue(':category', $category['name'], PDO::PARAM_STR);
						$UserCategoryQuery->execute();
					}
					
					//pobranie deafoultowych kategorii wydatkow
					
					$categoriesEQuery = $db->query('SELECT name FROM expenses_category_default');
					$categoriesE = $categoriesEQuery->fetchAll();
					
					//przypisanie id usera do kategorii wydatkow
					
					foreach ($categoriesE as $categoryE) {
						$UserCategoryEQuery = $db->prepare('INSERT INTO expenses_category_assigned_to_users VALUES (NULL, :id, :category)');
						$UserCategoryEQuery->bindValue(':id', $user_id['id'], PDO::PARAM_INT);
						$UserCategoryEQuery->bindValue(':category', $categoryE['name'], PDO::PARAM_STR);
						$UserCategoryEQuery->execute();
					}
					
					//pobranie deafoultowych kategorii metody platnosci
					
					$categoriesMQuery = $db->query('SELECT name FROM payment_methods_default');
					$categoriesM = $categoriesMQuery->fetchAll();
					
					//przypisanie id usera do kategorii metody platnosci
					
					foreach ($categoriesM as $categoryM) {
						$UserCategoryMQuery = $db->prepare('INSERT INTO payment_methods_assigned_to_users VALUES (NULL, :id, :category)');
						$UserCategoryMQuery->bindValue(':id', $user_id['id'], PDO::PARAM_INT);
						$UserCategoryMQuery->bindValue(':category', $categoryM['name'], PDO::PARAM_STR);
						$UserCategoryMQuery->execute();
					}
										
					$_SESSION['udanarejestracja']=true;
					header('Location: witamy.php');
					
				}
	
		
}	




?>
<!DOCTYPE html>
<html lang="pl">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>Rejestracja</title>
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
						<h1 class="h4" style="margin-top:45px;"> Rejestracja </h1>
					</header>
					
					<form class="row" method="post">
			
						<div class="input-center">
							<span class="span-style">
								<i class="demo-icon icon-user"></i>
							</span>
							<div class="input-style" style="float: left;">
									<input type="text" class="form-control" placeholder="Imię" aria-label="imie" name="imie"  value="<?php
										if (isset($_SESSION['fr_imie']))	
										{
											echo $_SESSION['fr_imie'];
											unset($_SESSION['fr_imie']);
										}
									?>" 
									required>
							</div>
							<div class="clearclass">
							</div>
						</div>
						
						<?php
								if (isset($_SESSION['e_imie']))
								{
									echo '<div class="text-danger center mt-2">'.$_SESSION['e_imie'].'</div>';
									unset($_SESSION['e_imie']);
								}
						?>
						
						<div class="input-center">
							<span class="span-style">
								<i class="demo-icon icon-mail-alt"></i> 
							</span>		
							<div class="input-style" style="float: left;">
								<input type="email" class="form-control" placeholder="E-mail" aria-label="email" name="email"  value="<?php
								
										if (isset($_SESSION['fr_email']))	
										{
											echo $_SESSION['fr_email'];
											unset($_SESSION['fr_email']);
										}
									?>" 
										required >
										
							</div>			
							<div class="clearclass">						
							</div>
						</div>
						
						<?php
								if (isset($_SESSION['e_email']))
								{
									echo '<div class="text-danger center mt-2">'.$_SESSION['e_email'].'</div>';
									unset($_SESSION['e_email']);
								}
						?>

						<div class="input-center">
							<span class="span-style">
								<i class="demo-icon icon-lock"></i>
							</span>
							<div class="input-password">
								<input type="password" class="form-control" placeholder="Hasło" aria-label="hasło" name="passwd1" id="passwd1" value="<?php
								
										if (isset($_SESSION['fr_haslo1']))	
										{
											echo $_SESSION['fr_haslo1'];
											unset($_SESSION['fr_haslo1']);
										}
									?>" 
									
										required >
								<i class="demo-icon icon-eye" onClick="showPwd('passwd1', this)"></i> 
							</div>
							<div class="clearclass">
							</div>
						</div>
						
						<div class="input-center">
							<span class="span-style">
								<i class="demo-icon icon-lock"></i>
							</span>
							<div class="input-password">
								<input type="password" class="form-control" placeholder="Powtórz hasło" aria-label="powtórz hasło" name="passwd2" id="passwd2" value="<?php
								
										if (isset($_SESSION['fr_haslo2']))	
										{
											echo $_SESSION['fr_haslo2'];
											unset($_SESSION['fr_haslo2']);
										}
									?>" 
									
										required >
										
								<i class="demo-icon icon-eye" onClick="showPwd('passwd2', this)"></i> 
							</div>
							<div class="clearclass">
							</div>
						</div>
						
						<?php
								if (isset($_SESSION['e_haslo']))
								{
									echo '<div class="text-danger center mt-2">'.$_SESSION['e_haslo'].'</div>';
									unset($_SESSION['e_haslo']);
								}
						?>
						
						
						<div class="g-recaptcha input-center mt-3" data-sitekey="6LewHtwaAAAAAGhwC7ZHVxb35AibFgO7v4O5DnIp"></div>
						
						<?php
								if (isset($_SESSION['e_bot']))
								{
									echo '<div class="text-danger center mt-2">'.$_SESSION['e_bot'].'</div>';
									unset($_SESSION['e_bot']);
								}
						?>

						<div class="input-center">
							<input type="submit"  value="Rejestracja"> 
						</div>

					</form>

					<span class="menu-span mb-4">
						Masz konto? <a href="index.php">Zaloguj się.</a>
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