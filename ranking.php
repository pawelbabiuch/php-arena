<?php
require_once('header.php');
require_once('connect.php');

echo '<h2>Ranking graczy</h2>';

$sql = 'SELECT profileID, userID, profileName, level, fame FROM profiles ORDER BY fame DESC, level DESC';
$result = $conn->query($sql);

echo
'<table border=2>
<tr>
	<td><b>Nazwa gracza</b></td>
	<td><b>Sława gracza</b></td>
	<td><b>Poziom gracza</b></td>';
if(!empty($_SESSION['userID']))
				echo "<td><b>Podgląd profilu</b></td>";
		if(!empty($_SESSION['accountType']) && $_SESSION['accountType'] == 1)
				echo "<td><b>Edycja</b></td>";
echo '</tr>
';
while($user = $result->fetch_assoc())
{
	if(!empty($_SESSION) && $user['userID'] == $_SESSION['userID'])
		echo '<tr bgcolor=#CCC>';
	else
		echo '<tr>';
	echo
	"
			<td>$user[profileName]</td>
			<td>$user[fame]</td>
			<td>$user[level]</td>";
		if(!empty($_SESSION['userID']))
				echo "<td><a href='profile.php?id=$user[userID]'>Zobacz profil</a></td>";
		if(!empty($_SESSION['accountType']) && $_SESSION['accountType'] == 1)
				echo "<td><a href='edit.php?id=$user[userID]'>Edytuj profil</a></td>";
			
		echo"</tr>";
}
echo '</table>';

require_once('footer.php');
?>