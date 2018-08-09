<?php
require_once('header.php');

echo '<h2>Profil</h2>';

if(empty($_GET['id']) || empty($_SESSION))
	header('Location: index.php');

include('connect.php');

$sql = "SELECT U.banDays, U.accountType, P.profileName, P.profileID, P.money, P.attack, P.defence, P.fame, P.level, P.exp, P.expToNext, P.class, P.points
			FROM users U join profiles P on U.userID = P.userID
			WHERE U.userID = $_GET[id]";

$result = $conn->query($sql);

if($result->num_rows == 0)
	echo "<b><p style='color: red;'>Taki użytkownik nie istnieje.</p></b>";			
else
{
	$user = $result->fetch_assoc();
	
	echo 
	"<table>
		<tr>
			<td>Nazwa użytkownika:</td>";
			if( $user['accountType'] == 1)
				echo "<td style='color: blue;'><b>$user[profileName]</b> (admin)</td>";
			else
				echo "<td><b>$user[profileName]</b></td>";
		echo "
		</tr>
		<tr>
			<td>Siła ataku:</td>
			<td><b>$user[attack] </b>";
		if($user['points'] > 0)
			echo "<a href='AddPoint.php?id=$_GET[id]&pkt=attack'> Dodaj</a> (punkty: $user[points])";
		
		echo
		"</td>
		</tr>
		<tr>
			<td>Siła obrony:</td>
			<td><b>$user[defence] </b>";
			
		if($user['points'] > 0)
			echo "<a href='AddPoint.php?id=$_GET[id]&pkt=defence'> Dodaj</a> (punkty: $user[points])";
		
		echo
		"</td>
		</tr>
		<tr>
			<td>Poziom postaci:</td>
			<td><b>$user[level]</b></td>
		</tr>";
		if($_SESSION['userID'] == $_GET['id'] || $_SESSION['accountType'] == 1)
		{
			echo 
			"
			<tr>
				<td>Życie:</td>
				<td><b>".($user['level'] * 10 + $user['defence'])."</b></td>
			</tr>
			<tr>
				<td>Złoto:</td>
				<td><b>$user[money]</b></td>
			</tr>
			<tr>
				<td>Exp:</td>
				<td><b>$user[exp] <progress max='$user[expToNext]' value='$user[exp]'></progress> $user[expToNext]</b></td>
			</tr>
			";
		}
		echo
		"<tr>
			<td>Sława:</td>
			<td><b>$user[fame]</b></td>
		</tr>
		<tr>
			<td>Klasa postaci:</td>
			<td><b>$user[class]</b></td>
		</tr>";
		if($user['banDays'] >= date("Y-m-d H:i:s") && ($_SESSION['userID'] == $_GET['id'] || $_SESSION['accountType'] == 1))
		{
			$banDaysDate = date_format(date_create($user['banDays']), 'Y-m-d');
			
			echo
			"
				<tr style='color: red;'>
					<td>Konto zablokowane do:</td>
					<td><b>$banDaysDate</b></td>
				</tr>
			";
		}
		if($_SESSION['userID'] == $_GET['id'] || $_SESSION['accountType'] == 1)
		{
			include('f.php');
			$u = getProfile($_SESSION['userID']);
			echo "<tr><td></td><td><a href='edit.php?id=$_GET[id]'>Edytuj profil</a></td></tr>";	
		}
	echo "</table>";
	
}	

require_once('footer.php');
?>