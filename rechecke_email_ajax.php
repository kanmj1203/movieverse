<?php
	require("db_connect.php");
	
	$email=$_POST['email'];
	
if($_POST['email'] != NULL){
	if($db->query("select count(*) from user where email='$email'")->fetchColumn() > 0){
		echo "사용할 수 없는 이메일입니다.";
	} else {
		echo "사용 가능한 이메일입니다.";
	}
}else{
	echo "이메일을 입력해 주세요.";
}

 ?>