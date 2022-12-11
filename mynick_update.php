<!doctype html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

<?php

// 닉네임 변경
	$new_nickname =isset($_REQUEST["new_nickname"]) ? $_REQUEST["new_nickname"] : "";
	$now_nickname =isset($_REQUEST["now_nickname"]) ? $_REQUEST["now_nickname"] : "";
	
	require("db_connect.php");
	session_start();


		if(!($new_nickname)) {//하나라도 빈칸 있으면
			
				?>
					<script>
						alert('빈 칸 없이 입력해 주세요.');
						history.back();
					</script>
				<?php
		}else{

            $db->exec("update user set nickname='$new_nickname' where identification='$_SESSION[userId]'");
            $_SESSION["userName"]= $new_nickname;
            ?>
            <script>
                alert('닉네임이 변경되었습니다.');
                location.href="myinfo.php";
            </script>	
            <?php

		}	
	
	?>
</body>
</html>
