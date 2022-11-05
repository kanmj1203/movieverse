<html>
    <body>
        <?php
//  header('Content-Type: text/html; charset=UTF-8');
 
//  //변수에 한글이 포함될 경우 아래 코드를 추가한다.
//  putenv("LANG=ko_KR.UTF-8");
//  setlocale(LC_ALL, 'ko_KR.utf8');


//  $변수1 = "AAA";
//  $변수2 = "가나다";
//  $변수3 = "가 나 다"; //공백이 있을경우 문자열로 묶어줘야 함 //exec("python3 python.py ".$변수1." ".$변수2." \"".$변수3."\"", $output);
// exec("cd ./py/MovieConnect.py", $output, $status);

// //이렇게 하는 이유는 경로를 지정해주고 python3를 실행해야 정상적으로 작동.


//  //$rt=exec("ls");
//  //echo $rt;
// if($status){
// echo(json_encode($output));
// echo $output;
//  //print_r($output);
//  echo $output[0]. ""; //Success1 good
//  echo $output[1]. ""; //Success2
//  print_r($output);
// }


$method = "GET";

$data = array(
    'api_key' => '13e4eba426cd07a638195e968ac8cf19',
    'with_watch_providers' => 8,
    'watch_region' => 'KR',
    'language' => 'ko',
    'page' => 1
);

$url = "https://api.themoviedb.org/3/discover/movie" . "?" . http_build_query($data, '', );

$ch = curl_init();                                 //curl 초기화
curl_setopt($ch, CURLOPT_URL, $url);               //URL 지정하기
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환 
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      //connection timeout 10초 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   //원격 서버의 인증서가 유효한지 검사 안함 
//curl_setopt($ch, CURLOPT_SSLVERSION, 3); // SSL 버젼 (https 접속시에 필요)
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
 
$response = curl_exec($ch);

$sResponse = json_decode($response , true);		//배열형태로 반환

$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);
 
// return $response;
print_r($sResponse);
 

        ?>
    </body>
</html>