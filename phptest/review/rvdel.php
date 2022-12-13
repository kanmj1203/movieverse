<?php
	$review_num = $_REQUEST["review_num"];
	
	require("../db_connect.php");
	$member_num = empty($_REQUEST["member_num"]) ? 1 : $_REQUEST["member_num"];
	// 지정된 레코드에서 파일명 필드 값 읽기 --> 해당 파일 삭제
	//  $db->query("select * from review where review_num=$review_num")->fetchColumn();
	
	
	// DB 테이블에서 해당 레코드 삭제
	$db->exec("delete from review where review_num=$review_num");
				
					
	// 메인 페이지로 돌아가기
	echo "
    <script>
        alert(\"리뷰가 삭제되었습니다.\");
        history.back();
    </script>
";
	// header("Location:view.php?member_num=$member_num");
	// exit();
?>
