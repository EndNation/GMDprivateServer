<!DOCTYPE HTML>
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
<title>Add Roles :)</title>
</head>
<body>
<?php
function chkarray($source)
{
	if ($source == "")
	{
		$target = "0";
	}
	else
	{
		$target = $source;
	}
	return $target;
}

include "../incl/lib/connection.php";
require_once "../incl/lib/exploitPatch.php";
$ep = new exploitPatch();

if(!empty($_POST["rolena"]) AND !empty($_POST["plrna"]))
{
	//accounts - accountID
	$rolena = $ep->remove($_POST["rolena"]);
	$plrna = $ep->remove($_POST["plrna"]);
	
	$query = $db->prepare("SELECT * FROM roles WHERE roleName = :rna");
	$query->execute(['rna' => $rolena]);
	$roleinfo = $query->fetch();
	$query = $db->prepare("SELECT * FROM accounts WHERE userName = :una");
	$query->execute(['una' => $plrna]);
	$accinfo = $query->fetch();
	$roleid = $roleinfo["roleID"];
	$accid = $accinfo["accountID"];
	$query = $db->prepare("DELETE FROM roleassign WHERE accountID = :acci");
	$query->execute(['acci' => $accid]);
	$query = "INSERT INTO `roleassign`(`assignID`, `roleID`, `accountID`) VALUES (NULL, :rolid, :acid)";
	$result = $db->prepare($query);
	$params = array(
		':rolid' => $roleid,
		':acid' => $accid
	);
	$result->execute($params);
	if ($result)
	{
		echo "Successfully given ".$plrna." the role: ". $roleinfo["roleName"];
	}
	else
	{
		echo "I have no idea how one can make this fail :)";
	}
}
else
{
	echo '<form action="addRoles.php" method="post">
	<h1>TheDarkSid3r Private Server</h1>
	<h2>Add a role to a player</h2>
	Role Name: <input type="text" name="rolena"><br>
	Player Name: <input type="text" name="plrna"><br>
	<input type="submit" value="Submit"></form>
	<p>To remove role from a player, click <a href="http://crimorybotz.ddns.net/database/tools/revRoles.php">here</a>!</p>';
}
?>
</body>
</html>
