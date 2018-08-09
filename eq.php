<?php
require_once('header.php');
require_once('f.php');

echo '<h2>Ekwipunek</h2>';

if(empty($_SESSION))
	header('Location: index.php');

include('connect.php');

$user = getProfile($_SESSION['userID']);

if($user['banDays'] >= date("Y-m-d H:i:s") && $_SESSION['accountType'] == 0)
{
	header('Location: index.php');
	exit;
}


if(empty($_GET))
{
	$result = $conn->query(
	"
	SELECT E.eQID, E.eQOwner, E.equip, S.itemName, S.itemValue, S.itemAttack, S.itemDefence, S.itemType, IT.itemTypeName
	FROM equipments E join shop S on E.itemID = S.itemID
								   join itemtypes IT on S.itemType = IT.itemTypeID
	WHERE E.eQOwner = $_SESSION[userID]
	ORDER BY E.equip DESC, S.itemType, S.itemValue DESC
	");
	$i = 1;

	if($result->num_rows > 0)
	{
		echo
		"<table border=1>
			<tr style='background-color: #666; color: #DDD'>
				<th>lp</th>
				<th>Nazwa</th>
				<th>Obrażenia</th>
				<th>Obrona</th>
				<th>Typ przedmiotu</th>
				<th>Wartość</th>
				<th>Załóż/Zdejmij</th>
				<th>Sprzedaj</th>
			</tr>";
		while($item = $result->fetch_assoc())
		{
			if($i % 2 == 1)
				echo '<tr bgcolor=#CCC>';
			else
				echo '<tr bgcolor=#EEE>';
			
			echo
			"		<td>$i</td>
					<td>$item[itemName]</td>
					<td>$item[itemAttack]</td>
					<td>$item[itemDefence]</td>
					<td>$item[itemTypeName]</td>
					<td>$item[itemValue]</td>";
					
			if($item['equip'] == 0)
				echo "<td><a href='eq.php?e=$item[eQID]'>Załóż</a></td>
						  <td><a href='eq.php?sell=$item[eQID]'>Sprzedaj za pół ceny</a></td>";
			else
				echo "<td><a href='eq.php?u=$item[eQID]'>Zdejmij</a></td>
						  <td>-</td>";		
			echo "
				</tr>
			";
			
			$i++;
		}
		echo '</table>';
	}
	else
		echo "<b><p style='color: blue;'>Nie masz aktualnie żadnych kupionych przedmiotów.</p></b>";
}
elseif(!empty($_GET['e']))
{
	$item = $conn->query("SELECT * FROM equipments E join shop S on E.itemID = S.itemID WHERE eQID=$_GET[e]"); // Item który chcemy zalozyc.
	$i = $item->fetch_assoc();
	
	$item2 = $conn->query("SELECT * FROM equipments E join shop S on E.itemID = S.itemID WHERE  E.eQOwner = $_SESSION[userID] AND E.equip=1 AND S.itemType=$i[itemType]"); // aktualnie założony przedmiot
	
	if($item2->num_rows > 0)
	{
		$i2 = $item2->fetch_assoc();
		$sql = "UPDATE profiles SET attack=attack-$i2[itemAttack], defence=defence-$i2[itemDefence] WHERE userID=$_SESSION[userID]";
		$conn->query($sql);	// Odejmowanie statystyk dla gracza
	}
	
	if($i['eQOwner'] == $_SESSION['userID']) 
	{
		$sql = "UPDATE equipments E join shop S on E.itemID = S.itemID SET E.equip=0 WHERE E.eQOwner=$_SESSION[userID] AND E.equip=1 AND S.itemType=$i[itemType]";
		$conn->query($sql);	// Zdejmowanie przedmiotu aktualnego.
		$sql = "UPDATE equipments SET equip=1 WHERE eQOwner=$_SESSION[userID] AND eQID=$_GET[e]";
		$conn->query($sql);	// Zakładanie wybranego przedmiotu.
		$sql = "UPDATE profiles SET attack=attack+$i[itemAttack], defence=defence+$i[itemDefence] WHERE userID=$_SESSION[userID]";
		$conn->query($sql);	// Dodawanie statystyk dla gracza
	}
	
	header('Location: eq.php');
}
elseif(!empty($_GET['u']))
{
	$item = $conn->query("SELECT * FROM equipments E join shop S on E.itemID = S.itemID WHERE eQID=$_GET[u]");
	$i = $item->fetch_assoc();
	
	if($i['eQOwner'] == $_SESSION['userID']) 
	{
		$sql = "UPDATE profiles SET attack=attack-$i[itemAttack], defence=defence-$i[itemDefence] WHERE userID=$_SESSION[userID]";
		$conn->query($sql);	// Odejmowanie statystyk dla gracza
		$sql = "UPDATE equipments SET equip=0 WHERE eQOwner=$_SESSION[userID] AND eQID=$_GET[u]";
		$conn->query($sql);	// Zdejmowanie wybranego przedmiotu.
	}
	
	header('Location: eq.php');
}
elseif(!empty($_GET['sell']))
{
	$item = $conn->query("SELECT * FROM equipments E join shop S on E.itemID = S.itemID WHERE eQID=$_GET[sell]");
	$i = $item->fetch_assoc();
	
	if($i['eQOwner'] == $_SESSION['userID'] && $i['equip'] == 0) 
	{
		$itemValue = $i[itemValue]/2;
		$sql = "UPDATE profiles SET money=money+$itemValue WHERE userID=$_SESSION[userID]";
		$conn->query($sql);	// dodawanie pieniędzy dla gracza
		$sql = "DELETE FROM equipments WHERE eQOwner=$_SESSION[userID] AND eQID=$_GET[sell]";
		$conn->query($sql);	// usuwanie wybranego przedmiotu.
		
		
		$fileName = 'shop.log';
		$file = fopen($fileName, 'a');
		$data = date('d M Y H:i:s');
		fputs($file, "[$data] $user[userLogin] - sprzedał przedmiot '$i[itemName]' za $itemValue$\n");
		fclose($file);
	}
	
	header('Location: eq.php');
}

include('footer.php');
?>