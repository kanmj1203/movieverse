<?php
	require("db_connect.php");
	
	$name=$_POST['name'];
	
if($_POST['name'] != NULL){
	if($db->query("select count(*) from user where nickname='$name'")->fetchColumn() > 0){
		echo "사용할 수 없는 닉네임입니다.";
	} else {
		echo "사용 가능한 닉네임입니다.";
	}
}else{
	echo "닉네임을 입력해 주세요.";
}

 ?>