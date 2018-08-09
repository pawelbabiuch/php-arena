<?php
require_once('header.php');
require_once('connect.php');
require_once('f.php');

if(empty($_SESSION['userID']))
{
	header('Location: index.php');
	exit;
}

echo '<h2>Sklep</h2>';
$user = getProfile($_SESSION['userID']);

if($user['banDays'] >= date("Y-m-d H:i:s") && $_SESSION['accountType'] == 0)
{
	header('Location: index.php');
	exit;
}

if(!empty($_GET['buy']))
{
	$item = $conn->query("SELECT itemID, itemValue, itemName FROM shop WHERE itemID=$_GET[buy]");
	if($item->num_rows > 0)
	{			
		$i = $item->fetch_assoc();		
			
		if($user['money'] < $i['itemValue'])
		{
			header('Location: shop.php');
			exit;
		}
		
		$conn->query("UPDATE profiles SET money=money-$i[itemValue] WHERE profileID=$user[profileID]");
		$conn->query("INSERT INTO equipments (eQOwner, itemID) VALUES ($user[userID], $_GET[buy])");
		
		echo "<b><p style='color: green;'>Kupiłeś przedmiot</p></b>";
		
		$fileName = 'shop.log';
		$file = fopen($fileName, 'a');
		$data = date('d M Y H:i:s');
		fputs($file, "[$data] $user[userLogin] - kupił przedmiot '$i[itemName]' za $i[itemValue]$\n");
		fclose($file);
	}
	header( "refresh:1;url=shop.php" );
}
else
{
	$sql = 'SELECT S.itemID, S.itemName, S.itemValue, S.itemAttack, S.itemDefence, iT.itemTypeName FROM shop S join itemTypes iT on S.itemType = iT.itemTypeID ORDER BY S.itemType,  S.itemValue';
	$result = $conn->query($sql);
	$user = getProfile($_SESSION['userID']);
	$i = 1;

	echo
	"	<table border=1>
				<tr style='background-color: #666; color: #DDD;'>
					<th>lp</th>
					<th>Nazwa</th>
					<th>Obrażenia</tf>
					<th>Obrona</tf>
					<th>Wartość</tf>
					<th>Typ przedmiotu</tf>
					<th>Kup</th>
				</tr>";

	while($item = $result->fetch_assoc())
	{
		
		if($i %2 == 1)
			echo '<tr bgcolor=#CCC>';
		else
			echo '<tr bgcolor=#EEE>';
		echo 
		"	<td>$i</td>
			<td>$item[itemName]</td>
			<td>$item[itemAttack]</td>
			<td>$item[itemDefence]</td>
			<td>$item[itemValue]</td>
			<td>$item[itemTypeName]</td>";
		if($user['money'] >= $item['itemValue'])
				echo "<td><a href='shop.php?buy=$item[itemID]'>Kup</a></td>";
		else
				echo '<td>-</td>';
			
		echo '</tr>';
		
		$i++;
	}

	echo '</table><br>';
	
}

require_once('footer.php');
?>