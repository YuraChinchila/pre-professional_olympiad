<?php
	session_start();
	if (count($_POST) > 0) {
		$login = htmlspecialchars(trim($_POST['login']));
		$pass = htmlspecialchars(trim($_POST['pass']));
		$pass = md5('jH1%cv2'.$pass);
		$db = new PDO('sqlite:database.sqlite3');
		$res = $db->query("SELECT id FROM users WHERE login='$login' AND pass='$pass'");
		$elem = [];
		while ($cur = $res->fetchObject()) {
			$elem[] = $cur;
		}
		if (count($elem) > 0) {
			$id = $elem[0]->id;
			$_SESSION['id'] = $id;
			header("Location: ../add_weight.html");
		}
	}
?>