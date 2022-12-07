<!doctype html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<!-- function clickLike($click_like_num) {
    if ($_SESSION['userNum'] != ""){
        $like_query = $db->query("select count(*) from review_like where like_review_num=$click_like_num and like_user_num='$_SESSION[userNum]'");
        if ($like_query == '0') {
            $db->exec("insert into review_like (
                like_user_num, like_review_num)
                values($_SESSION[userNum], $click_like_num)");
        } else {
            $db->query("delete from review_like where like_review_num=$click_like_num and like_user_num='$_SESSION[userNum]'");
        }
    } else {
        echo "
        <script>
            alert('로그인이 필요합니다.');
        </script>
    ";
    }
} -->
<?php
require("db_connect.php");
session_start();
	if(!$_SESSION["userId"]){
		?>
	<script>
		alert('로그인 후 이용할 수 있습니다.');
		history.back();
	</script>
<?php
	} else {
	
		$like_num=$_REQUEST["like_num"];
		// $content_id=$_REQUEST["content_id"];
		$like_user_num = $_SESSION["userNum"];

		$my_review_check = '';
		// $re=$db->query("SELECT * FROM review, review_like WHERE review.like_num=$req and good.id=$id and good.member_num=$_SESSION[userNum] and review.$tv_movie=$id AND review.member_num=$req;  ")->fetchColumn();
		$my_review_check_query = $db->query("select * from review where like_num=$like_num");
		while ($row = $my_review_check_query->fetch()) {
			$my_review_check = $row['member_num'];
		}
		if ($my_review_check == $like_user_num ) {
			echo "
			<script>
				alert('자신이 작성한 리뷰에는 표시할 수 없습니다.');
			history.back();
			</script>
			";
		} else {
			$like_query = $db->query("select count(*) from review_like where like_review_num=$like_num and like_user_num='$_SESSION[userNum]'")->fetchColumn();
			if($like_query != '0'){
			$db->exec("delete from review_like where like_review_num=$like_num and like_user_num='$_SESSION[userNum]'");
			echo "
				<script>
					alert('해당 리뷰의 좋아요가 해제되었습니다.');
				history.back();
				</script>
			";


			}else{

				$db->exec("insert into review_like (
					like_user_num, like_review_num)
					values($_SESSION[userNum], $like_num)");

			echo "
				<script>
					alert('해당 리뷰에 좋아요가 표시되었습니다.');
					history.back();
				</script>
			";
			}
		}
	}




?>
</body>
</html>
