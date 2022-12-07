<!doctype html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>

<?php
session_start();
	if(!$_SESSION["userId"]){
		?>
	<script>
		alert('로그인 후 작성할 수 있습니다.');
		history.back();
	</script>
<?php
	}

	$tr =isset($_REQUEST["textreview"]) ? $_REQUEST["textreview"] : "";
	$sv =isset($_REQUEST["starvalue"]) ? $_REQUEST["starvalue"] : "";
	$id =isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";
	$choice =isset($_REQUEST["choice"]) ? $_REQUEST["choice"] : "";
	require("db_connect.php");

	if(!($tr && $sv )) {

?>
	<script>
		alert('별점과 리뷰가 빈 곳 없이 입력해야 합니다.');
		location.href = 'choice.php?choice=<?=$choice?>&id=<?=$id?>';
	</script>

<?php
	}else{

		$count=$db->query("select count(*) from review ")->fetchColumn()+1;

			$date =   date("Y-m-d H:i:s");

	$re=$db->query("select count(*) from review where choice_content='$choice' and content_id=$id and member_num=$_SESSION[userNum]")->fetchColumn();
		
if($re>0){
$sv=$sv/16;

 $db->exec("update  review set  
								review_date = '".$date."'	,
								member_num = '".$_SESSION['userNum']."'	,
								review_content = '".$tr."'	,
								star_rating = '".$sv."'	,
								choice_content = '".$choice."'	
						where choice_content = '".$choice."' and content_id='$id' and member_num='".$_SESSION['userNum']."'");

						echo "
						<script>
							alert('리뷰가 수정되었습니다');
							location.href = 'choice.php?choice=$choice&id=$id';
						</script>
					";
					}else{

// $db->exec("insert into review_like (
// review_date,member_num,review_content,star_rating,choice_content, content_id, like)
// values('$date','$_SESSION[userNum]','$tr',$sv/16,'$choice', $id, 0)");

$db->exec("insert into review (
		  review_date,member_num,review_content,star_rating,choice_content, content_id)
	values('$date','$_SESSION[userNum]','$tr',$sv/16,'$choice', $id)");

$review_num = $db->query("select review_num from review where choice_content='$choice' and content_id=$id and member_num=$_SESSION[userNum]")->fetchColumn();
	

$db->exec("update  review set  
like_num = $review_num
where choice_content = '".$choice."' and content_id='$id' and member_num='".$_SESSION['userNum']."'");


	echo "
	<script>
		alert('리뷰가 작성되었습니다');
		location.href = 'choice.php?choice=$choice&id=$id';
	</script>
";
}

}
?>
</body>
</html>
