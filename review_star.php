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
	// $title =isset($_REQUEST["title"]) ? $_REQUEST["title"] : "";
	require("db_connect.php");

	
$method = "GET";
$api_key = '13e4eba426cd07a638195e968ac8cf19';


$data = array( 
    // 데이터 가져오기
    array(
        'api_key' => $api_key,
        'watch_region' => 'KR',
        'language' => 'ko',
        // 'page' => 1
    ),

    // array(
    //     'api_key' => $api_key
    // )
);


// URL 지정
$base_url = 'https://api.themoviedb.org/3';

$url = array(
    // 선택한 컨텐츠 정보
    $base_url . "/$choice/$id?" . http_build_query($data[0], '', ),

);

// TMDB API에서 데이터 불러오기
for($i = 0; $i < count($url); $i++){
    $ch = curl_init();                                 //curl 초기화
    curl_setopt($ch, CURLOPT_URL, $url[$i]);               //URL 지정하기
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환 
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      //connection timeout 10초 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   //원격 서버의 인증서가 유효한지 검사 안함 
    //curl_setopt($ch, CURLOPT_SSLVERSION, 3); // SSL 버젼 (https 접속시에 필요)
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    $response = curl_exec($ch);

    $sResponse[$i] = json_decode($response , true);		//배열형태로 반환

    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
}

// 플랫폼 id 리스트
$providers_id = [8, 337, 97, 356];

// TMDB에서 이미지 가져오기
$tmdb_img_base_url = "https://image.tmdb.org/t/p/original/";

// // 시나리오
// $overview = $sResponse[2]['results'][0]['overview'];
// // 공백문자, 줄바꿈 치환
// $overview = str_replace(" ", "&nbsp;", $overview); //공백
// $overview = str_replace("\n", "<br>", $overview); //줄바꿈

// return $response;
// function getTitle($count) {
    // $a = $sResponse['results'];
    // for ($i=0; $i<count($sResponse['results']); $i++) {
    //     // print($sResponse['results'][$i]['title']);
    //     // $a =  $sResponse['results'];
    //     print_r($sResponse['results'][$i]);
    //     print("<br><br>");
        
    // }
// }
// print("<br><br><br>" . $url);

$content = $sResponse[0];

// 제목 가져오는거 컨텐츠에 따라 결정
$title_change = $choice == 'movie' ? 'title' : 'name';
// $original_title_change = $choice_content == 'movie' ? 'original_title' : 'original_name';

$poster = $content['poster_path'];
$title = $content[$title_change];
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
$sv=$sv/12;

 $db->exec("update  review set  
								review_date = '".$date."'	,
								member_num = '".$_SESSION['userNum']."'	,
								review_content = '".$tr."'	,
								star_rating = '".$sv."'	,
								choice_content = '".$choice."',
								title = '".$title."',
								poster = '".$poster."'	
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
		  review_date,member_num,review_content,star_rating,choice_content, content_id, title, poster)
	values('$date','$_SESSION[userNum]','$tr',$sv/12,'$choice', $id, '".addslashes($title)."', '$poster')");

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
