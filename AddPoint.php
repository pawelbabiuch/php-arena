<?php

session_start();

if(empty($_SESSION) || empty($_GET))
{
	header('Location: index.php');
	exit;
}

if($_SESSION['userID'] != $_GET['id'] && $_SESSION['accountType'] == 0)
{
	header('Location: index.php');
	exit;
}

include('f.php');
$user = getProfile($_GET['id']);

if($user['points'] <= 0)
{
	echo "<p style='color: red;'>Nie masz do rozdania już żadnych punktów</p>";
	header( "refresh:1;url=profile.php?id=$_GET[id]" );
	exit;
}
else
{
	include('connect.php');
	
	$att = 0;
	$def = 0;
	
	if($_GET['pkt'] == 'attack')
		$att++;
	elseif($_GET['pkt'] == 'defence')
		$def++;
		
	$sql = "UPDATE profiles SET attack=attack+$att, defence=defence+$def, points=points-1 WHERE userID=$_GET[id]";
	$result = $conn->query($sql);
	
	
	if($result !== FALSE)
	{
			echo "<p style='color: green;'>Dodano punkt.</p>"; 
			header( "Location: profile.php?id=$_GET[id]" );
			exit;
	}
}

?>