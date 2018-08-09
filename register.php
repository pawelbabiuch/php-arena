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
	
	$email = '';
	if(isset($_POST['email']))
		$email = $_POST['email'];
	
	$class = $_POST['class'];
	
	if($_POST['password'] !==  $_POST['rePassword'])
	{
		echo "<b><p style='color: red;'>Hasła różnią się od siebie!</p></b>";
	}
	else
	{
		include('connect.php');
		
		$pass = md5($_POST['password']);
		$sql = "INSERT INTO users (userLogin, userPassword, UserEmail) VALUES ('$login', '$pass', '$email')";
		$result = $conn->query($sql);
		
		if ($result !== TRUE)
		{
				$sql = "SELECT userLogin FROM users WHERE userLogin='$login'";
				$result = $conn->query($sql);
				
				if($result->num_rows > 0)
					echo "<b><p style='color: red;'>Użytkownik o podanym loginie już istnieje.</p></b>";	
				else
					echo "<b><p style='color: red;'>Wystąpił nieoczekiwany błąd podczas rejestracji.</p></b>";	
		}
		else
		{
				$lastIndex = $conn->insert_id;
				$sql = "INSERT INTO profiles (userID, profileName, class) VALUES ('$lastIndex', '$login', '$class')";
				$result = $conn->query($sql);
				
				
				if($result !== TRUE)
				{
					$conn->rollback();
					echo "<b><p style='color: red;'>Wystąpił nieoczekiwany błąd podczas rejestracji.</p></b>";	
				}
				else
				{
					echo "<b><p style='color: green;'>Zarejestrowano pomyślnie :-)</p></b>";
					
					$fileName = 'register.log';
					$file = fopen($fileName, 'a');
					$data = date('d M Y H:i:s');
					fputs($file, "[$data] $login - zarejestrował się jako $_POST[class]\n");
					fclose($file);
					
					
					header( "refresh:2;url=login.php" );
				}
		}
	}
}
?>
<h2>Rejestracja</h2>

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
			<td>Powtórz hasło:</td>
			<td><input type='password' name='rePassword' placeholder='tajneHase1ko' pattern='[A-Za-z0-9]{5,15}' required></td>
		</tr>
		<tr>
			<td>Email:</td>
			<td><input type='email' name='email' placeholder='jan.kowalski@arena.pl' pattern='{5,64}'></td>
		</tr>
		<tr>
			<td>Klasa postaci:</td>
			<td>
				<select name='class'>
					<option>Wojownik</option>
					<option>Lowca</option>
					<option>Paladyn</option>
					<option>Zlodziej</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><input type='radio' required></td>
			<td>Akceptuję <a href='rules.php' target='_blank'>regulamin</a>.</td>
		</tr>
		<tr>
			<td><input type='submit' value='Zarejestruj się!'></td>
			<td><input type='reset'></td>
		</tr>
	</table>
</form>

<?php
require_once('footer.php');
?>