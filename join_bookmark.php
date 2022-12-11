<!doctype html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<?php
require("db_connect.php");
session_start();
// 선택한 컨텐츠 id param 가져오기
$choice_id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";
// 영화인지 드라마인지 (movie or tv)
$choice_content = isset($_REQUEST["choice"]) ? $_REQUEST["choice"] : "";

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
    $base_url . "/$choice_content/$choice_id?" . http_build_query($data[0], '', ),

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
    $title_change = $choice_content == 'movie' ? 'title' : 'name';

  	$id =isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";
    $choice =isset($_REQUEST["choice"]) ? $_REQUEST["choice"] : "";
    $poster = $content['poster_path'];
    $overview = $content['overview'];
    $title = $content[$title_change];
        

// $original_title_change = $choice_content == 'movie' ? 'original_title' : 'original_name';

	$date =   date("Y-m-d H:i:s");

  $query3 = $db->query("select *  from bookmark where member_num=$_SESSION[userNum] and choice_num = $id");

  while ($row = $query3->fetch()) {
      if($row['choice_num']==$id){
        $db->query("delete from bookmark where member_num=$_SESSION[userNum] and choice_num = $id");
        echo "
            <script>
                alert(\"북마크 삭제가 되었습니다.\");
                history.back();
            </script>
        ";
		 exit;
	  }
	  
  }
  
  

$db->exec("insert into bookmark(member_num,choice_num,join_date,choice,poster,overview, title) 
            values('$_SESSION[userNum]',$id,'$date','$choice','$poster', '".addslashes($overview)."', '".addslashes($title)."')");
	echo "
            <script>
                alert(\"북마크 추가가 되었습니다.\");
                history.back();
            </script>
        ";
  
  
?>



</body>
</html>
