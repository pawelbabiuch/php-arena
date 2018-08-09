<?php
require_once('header.php');

if(!empty($_SESSION['userID']))
{
	header('Location: index.php');
	exit;
}

if(!empty($_POST))
{
	
	$login = $_POST['login'];
	$password = md5($_POST['password']);
	
	include('connect.php');
	
	$sql = "SELECT userID, userLogin, userPassword, accountType FROM users WHERE userLogin='$login' AND userPassword='$password' LIMIT 1";

	$result = $conn->query($sql);
	
	if($result->num_rows !== 1)
		echo "<b><p style='color: red;'>Użytkownik o podanym loginie nie istnieje. (Sprawdź poprawność wpisanego hasła)</p></b>";			
	else
	{
		$user = $result->fetch_assoc();
		$_SESSION['userID'] = $user['userID'];
		$_SESSION['accountType'] = $user['accountType'];
		echo "<b><p style='color: green;'>Zalogowano pomyślnie :-)</p></b>";	
		header( "refresh:2;url=index.php" );
	}
}
?>
<h2>Logowanie</h2>

<form action='#' method='POST'>
	<table>
		<tr>
			<td>Nazwa użytkownika:</td>
			<td><input type='text' name='login' placeholder='WalecznyRyszard' pattern='[A-Za-z]{5,15}' required></td>
		</tr>
		<tr>
			<td>Hasło:</td>
			<td><input type='password' name='password' placeholder='tajneHase1ko' pattern='[A-Za-z0-9]{5,15}' required></td>
		</tr>
		<tr>
			<td><input type='submit' value='Zaloguj się!'></td>
			<td><input type='reset'></td>
		</tr>
	</table>
</form>

<?php
require_once('footer.php');
?>