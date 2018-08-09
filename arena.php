<?php
require_once('header.php');
require_once('f.php');
?>
<h2>Arena</h2>
<?php

if(empty($_SESSION))
	header('Location: index.php');

$user = getProfile($_SESSION['userID']);
if($user['banDays'] >= date("Y-m-d H:i:s"))
{
	header('Location: index.php');
	exit;
}

if(empty($_POST))
{
	echo '<h4>Walcz z losowym przeciwnikiem:</h4>';
	include('connect.php');

	$sql = 'SELECT * FROM arena ORDER BY enemyAttack, enemyDefence, enemyHP';
	$result = $conn->query($sql);

	if($result->num_rows > 0)
	{
		
		echo "
		<form action='arena.php' method='POST'>
		<select name='enemy'>";
		while($enemy = $result->fetch_assoc())
		{
			echo "<option value='$enemy[enemyID]'>$enemy[enemyName], fame: $enemy[enemyFame]</option>";
		}
		echo "
		<input type='submit' value='Walcz!' >
		</select>";
	}
}
else
{
	echo FightEnemy($_POST['enemy']);
}


require_once('footer.php');
?>