<?php

session_start();

if(isset($_SESSION['logged_id'])) {


	require_once 'database.php';

	$user_id = $_SESSION['logged_id'];
	$expenses_balance = 0;
	$incomes_balance =0;

	if (isset($_POST['periodOfTime'])) {
		
		//tworzenie zmiennych dat
		$endDate = new DateTime();
		$startDate = new DateTime();
		
		//sprawdzenie okresu wybranego przez usera
		
		if ($_POST['periodOfTime'] == "currentMonth"){
		$startDate->modify('first day of this month');	
		}
		
		if ($_POST['periodOfTime'] == "previousMonth"){
		$startDate->modify('first day of previous month');	
		$endDate->modify('last day of previous month');	
		}
		
		if ($_POST['periodOfTime'] == "currentYear"){
		$startDate->modify('first day of January');	
		}
		
		if ($_POST['periodOfTime'] == "customPeriod"){
			if($_POST['startDate'] <= $_POST['endDate']){
				$endDate->modify($_POST['endDate']);
				$startDate->modify($_POST['startDate']);
			} else {
				$endDate->modify($_POST['startDate']);
				$startDate->modify($_POST['endDate']);
			}
		}
		$firstDate = $startDate->format('Y-m-d');
		$secondDate = $endDate->format('Y-m-d');
		
		
		$expenseQuery = $db->prepare('SELECT amount, expenses.date_of_expense, expenses.expense_comment, expenses_category_assigned_to_users.name, payment_methods_assigned_to_users.name
		FROM expenses, expenses_category_assigned_to_users, payment_methods_assigned_to_users
		WHERE expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id AND expenses.payment_method_assigned_to_user_id = payment_methods_assigned_to_users.id AND expenses.user_id = :user_id AND expenses.date_of_expense BETWEEN :firstDate AND :secondDate');
		$expenseQuery->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$expenseQuery->bindValue(':firstDate', $firstDate, PDO::PARAM_STR);
		$expenseQuery->bindValue(':secondDate', $secondDate, PDO::PARAM_STR);
		$expenseQuery->execute();
		$eQuery= $expenseQuery ->fetchAll();
		
		$incomeQuery = $db->prepare('SELECT amount, incomes.date_of_income, incomes.income_comment, incomes_category_assigned_to_users.name
		FROM incomes, incomes_category_assigned_to_users
		WHERE incomes.income_category_assigned_to_user_id = incomes_category_assigned_to_users.id AND incomes.user_id = :user_id AND incomes.date_of_income BETWEEN :firstDate AND :secondDate');
		$incomeQuery->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$incomeQuery->bindValue(':firstDate', $firstDate, PDO::PARAM_STR);
		$incomeQuery->bindValue(':secondDate', $secondDate, PDO::PARAM_STR);
		$incomeQuery->execute();
		$iQuery= $incomeQuery ->fetchAll();

		foreach ($eQuery as $eBalance) {$expenses_balance = $expenses_balance + $eBalance['amount'];};
		foreach ($iQuery as $iBalance) {$incomes_balance = $incomes_balance + $iBalance['amount'];};
		$balance = $incomes_balance - $expenses_balance;
		$balance_formated = number_format($balance, 2);	
	
	}
}

//expenses.payment_method_assigned_to_user_id, AND expenses.user_id= $user_id AND expenses.date_of_expense BETWEEN $firstDate AND $secondDate"

/*$expenseQuery = $db->prepare("Select expenses.expense_category_assigned_to_user_id, expenses.amount, expenses.date_of_expense, expenses.expense_comment, expenses_category_assigned_to_users.name  
	FROM expenses, expenses_category_assigned_to_users 
	WHERE expenses.user_id = expenses_category_assigned_to_users.user_id")->fetchAll(PDO::FETCH_ASSOC); 
	
	$expenseQuery = $db->prepare('SELECT expenses.expense_category_assigned_to_user_id, amount, expenses.date_of_expense, expenses.expense_comment, expenses_category_assigned_to_users.name
	FROM expenses, expenses_category_assigned_to_users
	WHERE expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id AND expenses.user_id = :user_id AND expenses.date_of_expense BETWEEN :firstDate AND :secondDate');
	$expenseQuery->bindValue(':user_id', $user_id, PDO::PARAM_INT);
	$expenseQuery->bindValue(':firstDate', $firstDate, PDO::PARAM_STR);
	$expenseQuery->bindValue(':secondDate', $secondDate, PDO::PARAM_STR);
	$expenseQuery->execute();
	$eQuery= $expenseQuery ->fetchAll();
	print_r($eQuery);
	
	*/
?>


<!DOCTYPE html>
<html lang="pl">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>Bilans</title>
	<meta name="description" content="Aplikacja do zarządzania finansami.">
	<meta name="keywords" content="pieniadze, finanse, zarzadzanie, oszczedzanie">
	<meta name="author" content="Mateusz Kłosek">
	
	<meta http-equiv="X-Ua-Compatible" content="IE=edge,chrome=1">
	
	<script src="balance.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<link rel="stylesheet" href="main.css">
	<link rel="stylesheet" href="css/fontello.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Chango&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
	
	<script src="https://cdn.anychart.com/js/8.0.1/anychart-core.min.js"></script>
	<script src="https://cdn.anychart.com/js/8.0.1/anychart-pie.min.js"></script>
	
	
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
						<a href="index.php">Wyloguj się</a>
					</li>
					
				</ul>
			
			</div>
			
		</nav>
					
	</header>

	<main>
		
		<section>
				
			<div class="container col-11 col-lg-8 text-center mt-5">
			
				<header>
						<h3 class="h4 mt-4">Przegląd finansów</h3>
				</header>
				
				<form method="post">
				
				<div class="input-center">
					<span class="span-style">
						<i class="bi bi-calendar"></i>
					</span>
						<div class="input-style" style="float: left;">
							<select id="periodOfTime" data-live-search="true" name="periodOfTime">
								<option value="" selected disabled>Wybierz okres</option>
								<option value="currentMonth">Obecny miesiąc</option>
								<option value="previousMonth">Poprzedni miesiąc</option>
								<option value="currentYear">Obecny rok</option>
								<option value="customPeriod">Niestandardowy</option>
							</select>
						</div>
						<div class="clearclass">
						</div>
					</div>

			
					<div class="modal fade" id="dateModal" tabindex="-1" role="dialog" aria-labelledby="okresRozliczeniowy" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
						
							<div class="modal-content ">
							
								<div class="modal-header">
									<h4 id="okresRozliczeniowy" class="modal-title text-dark">Wybierz zakres dat</h4>
									<button type="button" class="close" data-dismiss="modal" aria-label="Zamknij">
									  <span aria-hidden="true">&times;</span>
									</button>
								</div>
								
								<div class="modal-body">
									<div class="input-center">
										<div class="input-group-prepend">
											<span class="span-style"> <i class="bi bi-hourglass-top"></i> </span>
										</div>
										<input id="startDate" type="date" class="input-span" aria-label="data" name="startDate" required>
									</div>	
									
									<div class="input-center">
										<div class="input-group-prepend">
											<span class="span-style"> <i class="bi bi-hourglass-bottom"></i></i> </span>
										</div>
										<input id="endDate" type="date" class="input-span" aria-label="data" name ="endDate" required>
									</div>		
								</div>
								
								<div class="modal-footer input-center">
									<input type="submit" id="modalCloseBtn" value="Potwierdź">
								</div>
								
							</div>
							
						</div>
					</div>
					
				</form>
				
				<?php
								
					if (isset($endDate, $startDate)){
						echo '<h3 class="h4 mt-4">' . "Bilans z okresu: " . $startDate->format('Y-m-d') . " - " . $endDate->format('Y-m-d') . "</h4>";
						unset($endDate, $startDate);
					}
					
				?>
				
				
				
				
				<div style="margin-top: 50px;" class="table-div">
				
					<div class="table-title">
					<h3 class="h4 mt-4">Przychody </h3>
					</div>
					
					<table id="myTable2" class="table-style" style="margin: auto;" >
						<thead style="text-align:center; background-color: grey; cursor: pointer;">
						<tr>
						<th style="width:240px;" onclick="sortTable(0)">Kategoria<span> <i class="demo-icon icon-arrow-combo"></i></span></th>
						<th style="width:240px;" onclick="sortTable(1)">Kwota<span> <i class="demo-icon icon-arrow-combo"></i></span></th>
						<th style="width:240px;" onclick="sortTable(2)">Data<span> <i class="demo-icon icon-arrow-combo"></i></span></th>
						<th style="width:240px;" onclick="sortTable(3)">Komentarz<span> <i class="demo-icon icon-arrow-combo"></i></span></th>
						</tr>
						</thead>
						<tbody  style="text-align:center;">
						<?php
						if(isset($iQuery)){
							foreach ($iQuery as $income) {
								echo "<tr><td>{$income[3]}</td><td>{$income[0]}</td><td>{$income[1]}</td><td>{$income[2]}</td></tr>";
						}};
						?>
						</tr>
						</tbody>
					</table>
				</div>
				
				<div style="margin-top: 50px; margin-bottom:100px;" class="table-div">
				
					<div class="table-title">
					<h3 class="h4 mt-4">Wydatki</h3>
					</div>
					
					<table id="myTable3" class="table-style" style="margin: auto;" >
						<thead style="text-align:center; background-color: grey; cursor: pointer;">
						<tr>
						<th style="width:192px;" onclick="sortTable1(0)">Kategoria<span> <i class="demo-icon icon-arrow-combo"></i></span></th>
						<th style="width:192px;" onclick="sortTable1(1)">Kwota<span> <i class="demo-icon icon-arrow-combo"></i></span></th>
						<th style="width:192px;" onclick="sortTable1(2)">Metoda płatności<span> <i class="demo-icon icon-arrow-combo"></i></span></th>
						<th style="width:192px;" onclick="sortTable1(3)">Data<span> <i class="demo-icon icon-arrow-combo"></i></span></th>
						<th style="width:192px;" onclick="sortTable1(4)">Komentarz<span> <i class="demo-icon icon-arrow-combo"></i></span></th>
						</tr>
						</thead>
						<tbody  style="text-align:center;">
						<?php
						if(isset($eQuery)){
							foreach ($eQuery as $expense) {
								echo "<tr><td>{$expense[3]}</td><td>{$expense[0]}</td><td>{$expense[4]}</td><td>{$expense[1]}</td><td>{$expense[2]}</td></tr>";
						}};
						?>
						</tbody>
					</table>
				</div>
				
				<?php
				if(isset($balance_formated)){
					echo " <h3 class='h4 mt-4'> BILANS: {$balance_formated} PLN </h3> ";
					if($balance_formated > 0){
					echo " <h3 class='h4 mt-4 text-success'> Gratulacje! Realizacja marzeń jest w zasięgu twoich rąk! </h3> ";
					} else {
					echo " <h3 class='h4 mt-4 text-danger'> Uwaga! Jesteś bankrutem! </h3> ";	
					}
				}
				
				?>
				<div class="row">
				
				<?php
					if(isset($balance)){
						echo "<div  class='col-12 center '  id='piechart1'></div>";
					}
				?>
				
				</div>
			
			</div>
				
		</section>
			
	</main>
	
	<footer class="text-center mb-5" style="font-size: 12px;">
		All rights reserved © 2021, Never broke again created by Mati
	</footer>

	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


	<script>
	function sortTable(n) {
	  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
	  table = document.getElementById("myTable2");
	  switching = true;
	  // Set the sorting direction to ascending:
	  dir = "asc";
	  /* Make a loop that will continue until
	  no switching has been done: */
	  while (switching) {
		// Start by saying: no switching is done:
		switching = false;
		rows = table.rows;
		/* Loop through all table rows (except the
		first, which contains table headers): */
		for (i = 1; i < (rows.length - 1); i++) {
		  // Start by saying there should be no switching:
		  shouldSwitch = false;
		  /* Get the two elements you want to compare,
		  one from current row and one from the next: */
		  x = rows[i].getElementsByTagName("TD")[n];
		  y = rows[i + 1].getElementsByTagName("TD")[n];
		  /* Check if the two rows should switch place,
		  based on the direction, asc or desc: */
		  if (dir == "asc") {
			if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
			  // If so, mark as a switch and break the loop:
			  shouldSwitch = true;
			  break;
			}
		  } else if (dir == "desc") {
			if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
			  // If so, mark as a switch and break the loop:
			  shouldSwitch = true;
			  break;
			}
		  }
		}
		if (shouldSwitch) {
		  /* If a switch has been marked, make the switch
		  and mark that a switch has been done: */
		  rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
		  switching = true;
		  // Each time a switch is done, increase this count by 1:
		  switchcount ++;
		} else {
		  /* If no switching has been done AND the direction is "asc",
		  set the direction to "desc" and run the while loop again. */
		  if (switchcount == 0 && dir == "asc") {
			dir = "desc";
			switching = true;
		  }
		}
	  }
	}
	</script>

	<script>
	function sortTable1(n) {
	  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
	  table = document.getElementById("myTable3");
	  switching = true;
	  // Set the sorting direction to ascending:
	  dir = "asc";
	  /* Make a loop that will continue until
	  no switching has been done: */
	  while (switching) {
		// Start by saying: no switching is done:
		switching = false;
		rows = table.rows;
		/* Loop through all table rows (except the
		first, which contains table headers): */
		for (i = 1; i < (rows.length - 1); i++) {
		  // Start by saying there should be no switching:
		  shouldSwitch = false;
		  /* Get the two elements you want to compare,
		  one from current row and one from the next: */
		  x = rows[i].getElementsByTagName("TD")[n];
		  y = rows[i + 1].getElementsByTagName("TD")[n];
		  /* Check if the two rows should switch place,
		  based on the direction, asc or desc: */
		  if (dir == "asc") {
			if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
			  // If so, mark as a switch and break the loop:
			  shouldSwitch = true;
			  break;
			}
		  } else if (dir == "desc") {
			if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
			  // If so, mark as a switch and break the loop:
			  shouldSwitch = true;
			  break;
			}
		  }
		}
		if (shouldSwitch) {
		  /* If a switch has been marked, make the switch
		  and mark that a switch has been done: */
		  rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
		  switching = true;
		  // Each time a switch is done, increase this count by 1:
		  switchcount ++;
		} else {
		  /* If no switching has been done AND the direction is "asc",
		  set the direction to "desc" and run the while loop again. */
		  if (switchcount == 0 && dir == "asc") {
			dir = "desc";
			switching = true;
		  }
		}
	  }
	}
	</script>

	<script type="text/javascript">

		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawChart);
		
		 //var exp= echo json_encode($exp) 
		 var exp = parseInt('<?php echo $expenses_balance; ?>');
		 var inc = parseInt('<?php echo $incomes_balance; ?>');
		 console.log(exp);

		function drawChart() {
			 // Optional; add a title and set the width and height of the chart
		  var data = google.visualization.arrayToDataTable([
		  ['What', 'How'],
		  ['Wydatki', exp],
		  ['Przychody', inc],
		]);
	 
		  // Optional; add a title and set the width and height of the chart
		  var options = { 'width':500, 'height':300, backgroundColor: 'transparent', legend: {textStyle: {color: 'lightgrey'}},  titleTextStyle: {color: 'lightgrey', fontSize:'16'}};

		  // Display the chart inside the <div> element with id="piechart"
		  var chart = new google.visualization.PieChart(document.getElementById('piechart1'));
		  chart.draw(data, options);
		}
		
	</script>


	<script>
		$('#periodOfTime').change(function(){
			if (this.value == "customPeriod"){
				document.getElementById("periodOfTime").setAttribute("onclick","");
				$('#dateModal').modal({
					show: true
				
				});
			} else
			{
				document.getElementById("periodOfTime").setAttribute("onclick","this.form.submit()");
			}
		});
	</script>



</body>
</html>