<!doctype html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

<?php
	$id =isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";
	$pw =isset($_REQUEST["pw"]) ? $_REQUEST["pw"] : "";
	$email =isset($_REQUEST["email"]) ? $_REQUEST["email"] : "";
	$name =isset($_REQUEST["name"]) ? $_REQUEST["name"] : "";
	

	require("db_connect.php");

	if(!($id && $pw && $name)) {
		
?>
	<script>
		alert('빈칸 없이 입력해야 합니다.');
		history.back();
	</script>
<?php
	}else if($db->query("select count(*) from user where identification='$id'")->fetchColumn() > 0){
?>		
	<script>
		alert('이미 등록된 아이디입니다');
		history.back();
	</script>	
<?php
	}else if($db->query("select count(*) from user where nickname='$name'")->fetchColumn() > 0){
?>		
	<script>
		alert('이미 등록된 닉네임입니다');
		history.back();
	</script>	
<?php
	}else if($db->query("select count(*) from user where email='$email'")->fetchColumn() > 0){
		?>		
			<script>
				alert('이미 등록된 이메일입니다');
				history.back();
			</script>	
		<?php
	}else{
	$date =   date("Y-m-d H:i:s");
		  $db->exec("insert into user (email,passwd,nickname,join_date,img_link,identification) 
	values('$email','$pw','$name','$date','sample.jpg','$id')"); 
		
?>
	<script>
		alert('가입이 완료되었습니다.');
		location.href="login.php";
	</script>	
<?php
	}
?>
</body>
</html>
