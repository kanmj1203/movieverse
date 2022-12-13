<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>

<?php
	
	$member_num = $_REQUEST["member_num"];
	$email  = 	$_REQUEST["email"];
	$id  = 	$_REQUEST["id"];
	$passwd = 			$_REQUEST["passwd"];
	$nickname = 		$_REQUEST["nickname"];
	// 새로운 이미지
	$imgFile  = $_REQUEST["imgFile"];
	// 기존 이미지
	$img_link = $_REQUEST["img_link"];
	
	$tt = "";

$resFile = null;
session_start();
require("../db_connect.php");
if ( ! empty( $_FILES['imgFile']['name']  ) ) {

$tempFile = $_FILES['imgFile']['tmp_name'];


$fileTypeExt = explode("/", $_FILES['imgFile']['type']);


$fileType = $fileTypeExt[0];


$fileExt = $fileTypeExt[1];



$extStatus = false;

	switch($fileExt){
		case 'jpeg':
		case 'jpg':
		case 'gif':
		case 'bmp':
		case 'png':     
			$extStatus = true;
			break;
		
		default:
			echo "<script>
				alert('파일 업로드에 실패하였습니다.');
				history.back();</script>";
			break;
	} 

if($fileType == 'image'){
	if($extStatus){
		$resFile = "../../user_img/{$_FILES['imgFile']['name']}";
		$imageUpload = move_uploaded_file($tempFile, $resFile);
		
		if($imageUpload == true){
		}else{			
			echo "<script>
			alert('파일 업로드에 실패하였습니다.');
			history.back();</script>";
		}
	}else {
			echo "<script>
			alert('파일 확장자는 jpg, bmp, gif, png 이어야 합니다.');
			history.back();</script>";
	}	
}else {
	echo "<script>
			alert('이미지 파일이 아닙니다.');
			history.back();</script>";

	}
	if($resFile){
		$tt= $_FILES['imgFile']['name'];
	// echo "<script>
	// 		alert('파일 업로드 완료.');
	// 		history.back();</script>";

	}
}else{		
	$tt=$img_link;
}

	if ($email && $id && $passwd && $nickname && $tt) {
					
		$query = $db->exec("update user set 
						email='$email',
						identification='$id',
						passwd='$passwd',
						nickname='$nickname',
						img_link='$tt'
						where member_num =$member_num");		
		header("Location:view.php?member_num=$member_num");		//여기화면으로
		exit();								//exit 안할시 프로그램실행해서 에러발생시 문제발생
	}	
	
?>


<script>
	alert('모든 입력란에 값이 입력되어야 합니다.');
	history.back();
</script>

</body>
</html>