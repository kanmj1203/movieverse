<!doctype html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

<?php

// 비밀번호 찾기 페이지에서 새 비밀번호 설정
	$pw_find_form = isset($_REQUEST["pw_find_form"]) ? $_REQUEST["pw_find_form"] : false;
	$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";
	$email = isset($_REQUEST["email"]) ? $_REQUEST["email"] : "";

// 로그인 후 마이페이지에서 변경
	$userpw =isset($_REQUEST["userpw"]) ? $_REQUEST["userpw"] : "";
	$new_pw =isset($_REQUEST["new_pw"]) ? $_REQUEST["new_pw"] : "";
	$ps_ok =isset($_REQUEST["ps_ok"]) ? $_REQUEST["ps_ok"] : "";
	
	require("db_connect.php");
	session_start();

	if ($pw_find_form) {
		if ($new_pw != "" && $ps_ok != "") {
			// 빈칸 아니라면
			if ($new_pw == $ps_ok){
			$db->exec("update user set passwd='$ps_ok' where email='$email' and identification = '$id'");
			?>
			<script>
				alert('비밀번호가 변경되었습니다.');
				location.href="login.php";
			</script>	
			<?php
			}
			?>
			<script>
				alert('비밀번호가 일치하지 않습니다.');
				history.back();
			</script>	
			<?php
		} else {
			?>
			<script>
				alert('빈 칸 없이 입력해 주세요.');
				history.back();
			</script>
			<?php
		}
	} else {
		if(!($ps_ok && $new_pw && $ps_ok)) {//하나라도 빈칸 있으면
			
				?>
					<script>
						alert('빈 칸 없이 입력해 주세요.');
						history.back();
					</script>
				<?php
		}else{
			if($userpw!=$_SESSION["userPw"]){
					?>
					<script>
						alert('현재 비밀번호가 틀립니다.');
						history.back();
					</script>
					<?php	
			}else{
					if($new_pw!=$ps_ok){
					
					?>
						<script>
							alert('새 비밀번호가 일치하지 않습니다.');
							history.back();
						</script>
					<?php			
				}else{
				$db->exec("update user set passwd='$ps_ok' where identification='$_SESSION[userId]'");
				$_SESSION["userPw"]	=	$ps_ok;
				?>
				<script>
					alert('비밀번호가 변경되었습니다.');
					location.href="myinfo.php";
				</script>	
				<?php
				}
			}
		}	
	}
	?>
</body>
</html>
