<?php
require_once('header.php');


if(empty($_SESSION['userID']))
{
	header('Location: index.php');
	exit;
}
elseif($_SESSION['accountType'] == 0)
{
	header('Location: index.php');
	exit;
}



?>
<h2>Logi gry</h2>



<form action='logs.php' method='POST'>
	<table>
		<tr>
			<td><input type='radio' name='logsType' value='register.log' checked> <b>Logi rejestracji</b></td>
			<td><input type='radio' name='logsType' value='area.log' > <b>Logi walk</b></td>
			<td><input type='radio' name='logsType' value='shop.log' > <b>Logi kupna i sprzedaży</b></td>
		</tr>
		<tr><td><br></td></tr>
		<tr>
			<td><input type='submit' value='Wczytaj logi'></td>
		</tr>
	</table>
</form>
<?php

if(!empty($_POST))
{
	$data = file($_POST['logsType']);
	
	echo "<h3>Wczytane dane z pliku: $_POST[logsType]<h3>";
	
	echo "<table style='border: 1px solid black;'>";
	
	for($i = count($data)-1; $i >= 0; $i--)
	{
		if($i % 2 == 0)
			echo "<tr><td bgcolor='#DDD'>$data[$i]</td></tr>";
		else
			echo "<tr><td>$data[$i]</td></tr>";			
	}
	
	echo "</table>";
}

require_once('footer.php');
?>