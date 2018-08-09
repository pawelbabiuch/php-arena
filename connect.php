<?php

	$host = 'localhost';
	$user = 'root';
	$pass = '';
	$dbName = 'arena';

	
	$conn = new mysqli($host, $user, $pass, $dbName);
	
	if($conn->connect_error)
	{
		// Dla bezpieczeństwa kodu powinniśmy usunąć informacje o błędzie, tak aby użytkownik nie widział tego błędu (np. mgóbły zaszkodzic)
		echo '<br>Error: <br>' . $conn->connect_error;
		exit;
	}
	
	/*
	Inną metodą połączenia z bazą danych jest PDO,
	jednak dla mnie wygodniejsza jest podana powyższa metoda (kwestia przyzwyczajenia)
	
	$connect = new PDO('mysql:host=localhost;dbname=arena', 'root', '');
	*/
?>