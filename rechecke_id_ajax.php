<?php
	require("db_connect.php");
	
	$identification=$_POST['id'];
	
if($_POST['id'] != NULL){
	if($db->query("select count(*) from user where identification='$identification'")->fetchColumn() > 0){
		echo "사용할 수 없는 아이디입니다.";
	} else {
		echo "사용 가능한 아이디입니다.";
	}
}else{
	echo "아이디를 입력해 주세요.";
}

 ?>