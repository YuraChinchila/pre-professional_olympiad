<?php
	session_start();
	$id = $_SESSION['id'];
	$db = new PDO('sqlite:database.sqlite3');
	$res = $db->query("SELECT * FROM information WHERE user_id=$id ORDER BY sort");
	$elem = [];
	while ($cur = $res->fetchObject()) {
		$elem[] = $cur;
	}
	print_r($elem);
?>