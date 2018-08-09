<?php
require_once('header.php');

echo '<h2>Edytuj profil</h2>';

if(empty($_GET['id']) || empty($_SESSION))
	header('Location: index.php');
if($_GET['id'] != $_SESSION['userID'] && $_SESSION['accountType'] == 0)
	header('Location: index.php');

include('connect.php');

$sql = "SELECT U.banDays, U.userPassword, U.userEmail, U.accountType, P.profileName, P.profileID, P.money, P.attack, P.defence, P.fame
			FROM users U join profiles P on U.userID = P.userID
			WHERE U.userID = $_GET[id]";

$result = $conn->query($sql);

if($result->num_rows == 0)
	echo "<b><p style='color: red;'>Taki użytkownik nie istnieje.</p></b>";			
else
{
	$user = $result->fetch_assoc();
	
	if(empty($_POST))
	{
		echo 
		"<form action='#' method='POST'>
			<table>
				<tr>
					<td>Zmień nazwę użytkownika:</td>
					<td><input type='text' name='login' value='$user[profileName]' placeholder='RyszardWojownik' pattern='[A-Za-z]{5,15}' required> (dane do logowania nie ulegną zmianie)</td>
				</tr>
				<tr>
					<td>Zmień hasło:</td>
					<td><input type='password' name='pass' placeholder='tajnehaselko' pattern='[A-Za-z0-9]{5,15}'></td>
				</tr>
				<tr>
					<td>Zmień email:</td>
					<td><input type='email' name='email' value='$user[userEmail]' placeholder='ryszard@arena.pl'></td>
				</tr>
			</table>";
			if($_SESSION['accountType'] == 1)
				echo
				"
					<hr><h3>Opcje dostępne dla admina:</h3>
					<table>
						<tr>
							<td>Zmień gotówkę:</td>
							<td><input type='number' name='money' value='$user[money]' min=0></td>
						</tr>
						<tr>
							<td>Dodaj doświadczenie:</td>
							<td><input type='number' name='exp' value=0 min=0 max=100></td>
						</tr>
						<tr>
							<td>Zmień atak:</td>
							<td><input type='number' name='attack' value='$user[attack]' min=5></td>
						</tr>
						<tr>
							<td>Zmień obronę: </td>
							<td><input type='number' name='defence' value='$user[defence]' min=5></td>
						</tr>
						<tr>
							<td>Zmień typ konta:</td>
							<td>
								<select name='accountType' selected=$user[accountType]>
									<option value=0 >Zwykły użytkownik</option>
									<option value=1 >Administrator</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Zmień sławę</td>
							<td><input type='number' name='fame' value='$user[fame]' min=0></td>
						</tr>
						<tr>
							<td>Zablokuj konto do dnia:</td>
							<td><input type='date' name='banDays' value='$user[banDays]' ></td>
						</tr>
					</table>
				";
			else
				echo
				"
					<input type='hidden' name='banDays' value='$user[banDays]' >
					<input type='hidden' name='fame' value='$user[fame]' >
					<input type='hidden' name='accountType' value='$user[accountType]' >
					<input type='hidden' name='money' value='$user[money]' >
					<input type='hidden' name='attack' value='$user[attack]' >
					<input type='hidden' name='defence' value='$user[defence]' >
					<input type='hidden' name='exp' value='0' >

				";
		echo
		"<hr>
			<tr>
				<input type='hidden' name='oldPass' value='$user[userPassword]' >
				<td><input type='password' name='checkPass' placeholder='Podaj hasło aby zapisać'  pattern='[A-Za-z0-9]{5,15}'></td>
				<td><input type='submit' value='Zmień dane'></td>
			</tr>
		";
		echo '</form>';
	}
	else
	{
		if($_SESSION['accountType'] == 1 || md5($_POST['checkPass']) == $user['userPassword'])
		{
			if($_POST['exp'] > 0)
			{
				include('f.php');
				AddExp($_POST['exp'], $_GET['id']);
			}
	
			if(empty($_POST['pass']))
				$password = $_POST['oldPass'];
			else
				$password = md5($_POST['pass']);
			
			$sql = "SELECT profileName FROM profiles WHERE profileName='$_POST[login]' AND userID != $_GET[id]";
			$result = $conn->query($sql);
			
			if($result->num_rows == 0)
			{
				$sql = "UPDATE profiles SET profileName='$_POST[login]', money=$_POST[money], attack=$_POST[attack], defence=$_POST[defence], fame=$_POST[fame] WHERE userID=$_GET[id]";
				$sql2 = "UPDATE users SET userPassword='$password', userEmail='$_POST[email]', banDays='$_POST[banDays]', accountType=$_POST[accountType] WHERE userID=$_GET[id]";
				$conn->query($sql);
				$conn->query($sql2);
				echo "<b><p style='color: green;'>Dane zostały zmienione</p></b>";	
			}
			else
			{
				echo "<b><p style='color: red;'>Istnieje użytkownik o takiej nazwie.</p></b>";	
			}

			header( "refresh:2;url=profile.php?id=$_GET[id]" );
		}
		else
		{
			header( "refresh:2;url=profile.php?id=$_GET[id]" );
		}
	}
}	

require_once('footer.php');
?>