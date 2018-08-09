<?php

function getProfile($userID)
{
	include('connect.php');
	
	$sql = "SELECT * FROM profiles P join users U on P.userID = U.userID WHERE P.userID=".$userID;
	$result = $conn->query($sql);
	
	if($result->num_rows > 0)
	{
		return $result->fetch_assoc();
	}
}

function AddExp($exp, $userID)
{
	include('connect.php');
	
	$user = getProfile($userID);

	$curExp = $user['exp'];
	$expToNext = $user['expToNext'];
	$level = $user['level'];
	$points = $user['points'];
	
	
	if($curExp + $exp >= $expToNext)
	{
		$newExp = $curExp + $exp - $expToNext; 
		$expToNext += ($expToNext/2);
		$level++;
		$points += 3;
	}
	else
	{
		$newExp = $curExp + $exp;
	}
	
	$sql = "UPDATE profiles SET exp=$newExp, expToNext=$expToNext, level=$level, points=$points WHERE userID=$userID";	
	$conn->query($sql);
}

function FightEnemy($enemyID)
{
	include('connect.php');
	
	$result = $conn->query("SELECT * FROM arena WHERE enemyID=$enemyID");
	
	if($result->num_rows > 0)
	{
		$user = getProfile($_SESSION['userID']);
		$enemy = $result->fetch_assoc();
		
		
		$userHP = $user['level'] * 10 + $user['defence'];
		$enemyHP = $enemy['enemyHP'];
		
		echo "<h4>Doszło do starcia: <span style='color: blue;'>$user[profileName]</span> VS <span style='color: red;'>$enemy[enemyName]</span></h4>";
		echo
		"
		<table border style='text-align: center;'>
			<tr>
				<th>Kto</th>
				<th style='color: blue;'>$user[profileName]</th>
				<th style='color: red;'>$enemy[enemyName]</th>
			</tr>
			<tr>
				<td><b>Atak<b/></td>
				<td style='color: blue;'>$user[attack]</td>
				<td style='color: red;'>$enemy[enemyAttack]</td>
			</tr>
			<tr>
				<td><b>Obrona<b/></td>
				<td style='color: blue;'>$user[defence]</td>
				<td style='color: red;'>$enemy[enemyDefence]</td>
			</tr>
			<tr>
				<td><b>Życie<b/></td>
				<td style='color: blue;'>$userHP</td>
				<td style='color: red;'>$enemyHP</td>
			</tr>
		</table><br>
		";
		
		while($userHP > 0 && $enemyHP > 0)
		{
			$userAttack = $user['attack'] + rand(-$user['attack']/3, $user['attack']/3);
			$enemyAttack = $enemy['enemyAttack'] + rand(-$enemy['enemyAttack']/3, $enemy['enemyAttack']/3);
			
			if($userHP > 0)
			{
				echo "<div style='color: blue;'>$user[profileName] ($userHP) atakuje $enemy[enemyName] ($enemyHP) i zadaje $userAttack obrażeń</div>";
				$enemyHP -= $userAttack;
			}
			
			if($enemyHP > 0)
			{
				echo "<div style='color: red;'>$enemy[enemyName] ($enemyHP) atakuje $user[profileName] ($userHP) i zadaje $enemyAttack obrażeń</div>";
				$userHP -= $enemyAttack;
			}
		}
		
		if($userHP < 0) $userHP = 0;
		elseif($enemyHP < 0) $enemyHP = 0;
		
		echo
		"<br>
		<table border style='text-align: center;'>
			<tr>
				<th>Kto</th>
				<th style='color: blue;'>$user[profileName]</th>
				<th style='color: red;'>$enemy[enemyName]</th>
			</tr>
			<tr>
				<td><b>Życie<b/></td>
				<td style='color: blue;'>$userHP</td>
				<td style='color: red;'>$enemyHP</td>
			</tr>
		</table><br>
		";
		
		$fileName = 'area.log';
		$file = fopen($fileName, 'a');
		$data = date('d M Y H:i:s');

		echo "Wynik końcowy:<br>";
		if($userHP > 0)
		{
			echo "<h3 style='color: blue'>Zwycięsko z pojedynku wychodzi: $user[profileName]</h3>";
			echo
			"
			<style>
				td
				{
					min-width: 32px;
				}
			</style>
			
			<table border  style='text-align: center;'>
				<tr>
					<th>Zdobyte złoto</th>
					<td>$enemy[enemyMoney]</td>
				</tr>
				<tr>
					<th>Zdobyta sława</th>
					<td>$enemy[enemyFame]</td>
				</tr>
				<tr>
					<th>Zdobyty exp</th>
					<td>$enemy[enemyExp]</td>
				</tr>
			</table>
			";
		
			$sql = "UPDATE profiles SET fame=fame+$enemy[enemyFame], money=money+$enemy[enemyMoney] WHERE userID=$_SESSION[userID]";
			$conn->query($sql);
			AddExp($enemy['enemyExp'], $_SESSION['userID']);
			
			fputs($file, "[$data] $user[profileName] walczył z $enemy[enemyName]. Wynik walki: Zwycięstwo\n");
		}
		else
		{
			echo "<h3 style='color: red;'>Zwycięsko z pojedynku wychodzi: $enemy[enemyName]</h3>";
			fputs($file, "[$data] $user[profileName] walczył z $enemy[enemyName]. Wynik walki: Porażka\n");
		}
	
		fclose($file);
	}
}

?>