<?php
	session_start();
	unset($_SESSION["userId"]);
	unset($_SESSION["userName"]);
	unset($_SESSION["userPw"]);
	unset($_SESSION["userNum"]);
	unset($_SESSION["userEmail"]);

	 
	 
	 
	//login_main.php로 이동
	header("Location:index.php");
	exit();
?>
