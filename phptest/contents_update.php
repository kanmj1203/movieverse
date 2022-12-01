<?php

    require("db_connect.php");

/*
넷플 : 
{
    "display_priority": 0,
    "logo_path": "/9A1JSVmSxsyaBK4SUFsYVqbAYfW.jpg",
    "provider_name": "Netflix",
    "provider_id": 8
    },

왓챠 :
{
    "display_priority": 3,
    "logo_path": "/cNi4Nv5EPsnvf5WmgwhfWDsdMUd.jpg",
    "provider_name": "Watcha",
    "provider_id": 97
},

디즈니 : 
{
    "display_priority": 1,
    "logo_path": "/dgPueyEdOwpQ10fjuhL2WYFQwQs.jpg",
    "provider_name": "Disney Plus",
    "provider_id": 337
},

웨이브 :
{
    "display_priority": 2,
    "logo_path": "/8N0DNa4BO3lH24KWv1EjJh4TxoD.jpg",
    "provider_name": "wavve",
    "provider_id": 356
},
*/

$method = "GET";
$api_key = '13e4eba426cd07a638195e968ac8cf19';

// 영화 데이터
$data = array( 
    // 최신순
    array(
        'api_key' => $api_key,
        'with_watch_providers' => 8,
        'with_watch_providers' => 337,
        'with_watch_providers' => 97,
        'with_watch_providers' => 356,
        'sort_by' => 'release_date.desc',
        'watch_region' => 'KR',
        'language' => 'ko',
        'page' => 1
    ),
    // 인기순
    array(
        'api_key' => $api_key,
        'with_watch_providers' => [8, 337, 97, 356],
        // 'with_watch_providers' => 337,
        // 'with_watch_providers' => 97,
        // 'with_watch_providers' => 356,
        'sort_by' => 'popularity.desc',
        'watch_region' => 'KR',
        'language' => 'ko',
        'page' => 1
    ),
    // 플랫폼 가져오기
    array(
        'api_key' => $api_key
    )
);

// 드라마/시리즈 데이터
// $tv_data = array(
//     'api_key' => '13e4eba426cd07a638195e968ac8cf19',
//     'with_watch_providers' => 8,
//     'watch_region' => 'KR',
//     'language' => 'ko',
//     'page' => 1
// );

// URL 지정
$base_url = 'https://api.themoviedb.org/3';

$url = array(
    // 최신순
    $base_url . "/discover/movie?" . http_build_query($data[0], '', ),
    $base_url . "/discover/tv?" . http_build_query($data[0], '', ),
    // 인기순
    $base_url . "/discover/movie?" . http_build_query($data[1], '', ),
    $base_url . "/discover/tv?" . http_build_query($data[1], '', ),
    // 플랫폼
    $base_url . "/watch/providers/tv?" . http_build_query($data[2], '', )
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

// 시나리오
$overview = $sResponse[2]['results'][0]['overview'];
// 공백문자, 줄바꿈 치환
$overview = str_replace(" ", "&nbsp;", $overview); //공백
$overview = str_replace("\n", "<br>", $overview); //줄바꿈

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

             
        ?>
