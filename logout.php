<?php
	session_start();
	unset($_SESSION['authorization']);

	header("Location: /");
?>