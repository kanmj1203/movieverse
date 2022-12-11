<?php
	$id=isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";
	$pw=isset($_REQUEST["pw"]) ? $_REQUEST["pw"] : "";
	
	if ($id == "" || $pw == "") {
		
		echo '<script>
		alert("빈 칸 없이 입력해 주세요.");
		location.href = "login.php";
		</script>';
		
	} else {

		require("db_connect.php");  
		$query=$db->query("select * from user where identification='$id'and passwd='$pw'");
		if($row=$query->fetch()) {
			//로그인 처리
			session_start();
			$_SESSION["userId"]	=	$row["identification"];
			$_SESSION["userName"]	=	$row["nickname"];
			$_SESSION["userPw"]	=		$row["passwd"];
			$_SESSION["userNum"]	=		$row["member_num"];
			$_SESSION["userEmail"]	=		$row["email"];
			//login_main.php로 이동
			header("Location:index.php");
			exit();
		}else{

			echo '<script>
			alert("존재하지 않는 계정 입니다.");
			location.href = "login.php";
			</script>';
			}
	}
?>

