<?php
	if (count($_POST) > 0) {
		$login = htmlspecialchars(trim($_POST['login']));
		$pass = htmlspecialchars(trim($_POST['pass']));
		$db = new PDO('sqlite:database.sqlite3');
		$q = $db->prepare("INSERT INTO users (login, pass) VALUES (:login, :pass)");
		$q->bindParam(':login', $login);
		$q->bindParam(':pass', md5('jH1%cv2'.$pass));
		$q->execute();
	}
	header("Location: ../index.html");
?>