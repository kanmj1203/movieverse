<?php
	
	require("db_connect.php");  

	$iset = '';
	$find_id_email = isset($_REQUEST["find_id_email"]) ? $_REQUEST["find_id_email"] : '';

	$find_pw_id = isset($_REQUEST["find_pw_id"]) ? $_REQUEST["find_pw_id"] : '';
	$find_pw_email = isset($_REQUEST["find_pw_email"]) ? $_REQUEST["find_pw_email"] : '';

	// 아이디 찾기
	if($find_id_email != ''){
		$query=$db->query("select * from user where email='$_REQUEST[find_id_email]'");
		while ($row = $query->fetch()) {
		$iset=$row['identification'];
		}
		if($iset != ''){
			
			header("Location:id_pw_find_result.php?id=$iset");
			exit();
		} 
		echo '<script>
		alert("해당 계정을 찾을 수 없습니다.");
		// location.href = "id_pw_find.php";
		</script>';
	}

	// 비밀번호 찾기(변경)
	if ($find_pw_id != '' && $find_pw_email != '') {
		$query=$db->query("select * from user where identification='$_REQUEST[find_pw_id]' and email='$_REQUEST[find_pw_email]'");
		while ($row = $query->fetch()) {
			$iset=$row['identification'];
			$eset=$row['email'];
			}
			if($iset != '' && $eset != ''){
				
				header("Location:pw_change_form.php?id=$iset&email=$eset");
				exit();
			} 
			echo '<script>
			alert("해당 계정을 찾을 수 없습니다.");
			location.href = "pw_find.php";
			</script>';
	}

		echo '<script>
		alert("빈 칸 없이 입력해 주세요.");
		location.href = "id_pw_find.php";
		history.back();
		</script>';
		
	
	function id(){
		require("db_connect.php");  
		$query=$db->query("select * from user where email='$_REQUEST[pw]'");
	while ($row = $query->fetch()) {
	$iset=$row['passwd'];
		header("Location:id_pw_find_result.php?pw=$iset");
		exit();
	}
	}
		
?>

