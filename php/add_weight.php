<?php
	session_start();
	if (count($_POST) > 0) {
		$weight = htmlspecialchars(trim($_POST['weight']));
		$id = $_SESSION['id'];
		$d = getdate();
		$day = $d['mday'].'.'.$d['mon'].'.'.$d['year'];
		$sort = time();
		$db = new PDO('sqlite:database.sqlite3');
		$q = $db->prepare("INSERT INTO information (user_id, day, weight, sort) VALUES ($id, :day, :weight, $sort)");
		$q->bindParam(':day', $day);
		$q->bindParam(':weight', $weight);
		$res = $q->execute();
		if ($res) {
			header("Location: ../add_weight.html");
		} else {
		}
	}
?>
