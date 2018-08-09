<!DOCTYPE html>
<html lang='pl'>
	<head>
		<title>Gra - Arena</title>
		<meta charset='utf-8'>
	</head>
	<body>
	<?php
		session_start();
		
		if(empty($_SESSION['userID']))
			echo '<h1>Witaj na arenie, Wojowniku!</h1>';
		else
		{
			include('connect.php');
			
			$sql = "SELECT P.profileName FROM profiles P join users U on U.userID = P.userID WHERE P.userID='$_SESSION[userID]'";
			$result = $conn->query($sql);
			
			$user = $result->fetch_assoc();
			echo "<h1>Witaj na arenie, $user[profileName]!</h1>";
		}
		echo '<hr>';
		
		echo "<a href='index.php'>Strona główna</a> | ";
		echo "<a href='ranking.php'>Ranking graczy</a> | ";
		
		if(empty($_SESSION['userID']))
		{
			echo "<a href='login.php'>Zaloguj</a> | ";
			echo "<a href='register.php'>Zarejestruj</a> | ";
		}
		else
		{
			echo "<a href='profile.php?id=$_SESSION[userID]'>Profil</a> | ";
			echo "<a href='arena.php'>Arena</a> | ";
			echo "<a href='eq.php'>Ekwipunek</a> | ";
		//	echo "<a href='#'>Walcz z graczem</a> | ";
			echo "<a href='shop.php'>Sklep</a> | ";
			if($_SESSION['accountType'] == 1)
				echo "<a href='logs.php'>Logi</a> | ";
			echo "<a href='logout.php'>Wyloguj</a> | ";
		}
		
		echo "<a href='rules.php' target='_blank'>Regulamin</a>";
	?>
	<hr>
