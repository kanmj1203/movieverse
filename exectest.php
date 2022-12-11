<html>
    <head>
    <meta charset="utf-8">
    <style>
        @import url(https://fonts.googleapis.com/css?family=Raleway:400,500,700);
.snip1273 {
  font-family: 'Raleway', Arial, sans-serif;
  position: relative;
  margin: 10px;
  min-width: 310px -60px;
  max-width: 310px;
  width: 100%;
  color: #ffffff;
  text-align: left;
  background-color: #000000;
  font-size: 16px;
}
.snip1273 * {
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  -webkit-transition: all 0.4s ease-in;
  transition: all 0.4s ease-in;
}
.snip1273 img {
  position: relative;
  max-width: 100%;
  vertical-align: top;
}
.snip1273 figcaption {
  position: absolute;
  top: 0;
  right: 0;
  width: 100%;
  height: 100%;
  z-index: 1;
  opacity: 0;
  padding: 20px 30px;
}
.snip1273 figcaption:before,
.snip1273 figcaption:after {
  width: 1px;
  height: 0;
}
.snip1273 figcaption:before {
  right: 0;
  top: 0;
}
.snip1273 figcaption:after {
  left: 0;
  bottom: 0;
}
.snip1273 h3,
.snip1273 p {
  line-height: 1.5em;
}
.snip1273 h3 {
  margin: 0 0 5px;
  font-weight: 700;
  text-transform: uppercase;
}
.snip1273 p {
  font-size: 0.8em;
  font-weight: 500;
  margin: 0 0 15px;
}
.snip1273 a {
  position: absolute;
  top: 0;
  bottom: 0;
  right: 0;
  left: 0;
  z-index: 1;
}
.snip1273:before,
.snip1273:after,
.snip1273 figcaption:before,
.snip1273 figcaption:after {
  position: absolute;
  content: '';
  background-color: #ffffff;
  z-index: 1;
  -webkit-transition: all 0.4s ease-in;
  transition: all 0.4s ease-in;
  opacity: 0.8;
}
.snip1273:before,
.snip1273:after {
  height: 1px;
  width: 0%;
}
.snip1273:before {
  top: 0;
  left: 0;
}
.snip1273:after {
  bottom: 0;
  right: 0;
}
.snip1273:hover img,
.snip1273.hover img {
  opacity: 0.4;
}
.snip1273:hover figcaption,
.snip1273.hover figcaption {
  opacity: 1;
}
.snip1273:hover figcaption:before,
.snip1273.hover figcaption:before,
.snip1273:hover figcaption:after,
.snip1273.hover figcaption:after {
  height: 100%;
}
.snip1273:hover:before,
.snip1273.hover:before,
.snip1273:hover:after,
.snip1273.hover:after {
  width: 100%;
}
.snip1273:hover:before,
.snip1273.hover:before,
.snip1273:hover:after,
.snip1273.hover:after,
.snip1273:hover figcaption:before,
.snip1273.hover figcaption:before,
.snip1273:hover figcaption:after,
.snip1273.hover figcaption:after {
  opacity: 0.1;
}
        </style>
    </head>
    <body>
    <figure class="snip1273">
  <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/331810/sample72.jpg" alt="sample72"/>
  <figcaption>
    <h3>Fletch Skinner</h3>
    <p>I don't need to compromise my principles, because they don't have the slightest bearing on what happens to me anyway. </p>
  </figcaption>
  <a href="#"></a>
    </figure>

    <script>
          /* Demo purposes only */
  $(".hover").mouseleave(
    function () {
      $(this).removeClass("hover");
    }
  );
        </script>
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
*/
$api_key = '13e4eba426cd07a638195e968ac8cf19';
$data = array(
    'api_key' => $api_key,
    // 'with_watch_providers' => 8,
    // 'with_watch_providers' => 337,
    // 'with_watch_providers' => 97,
    'with_watch_providers' => '8|337|97|356',
    'watch_region' => 'KR',
    'language' => 'ko',
    'page' => 1,
    'sort_by' => 'popularity.desc',
);

$provide = array(
    'api_key' => $api_key,
    // 'provider_id' => 8,
    // 'provider_id' => 337,
    // 'provider_id' => 97,
    // 'provider_id' => 356,
);

$genre = array(
    'api_key' => $api_key,
    'language'=> 'ko',
);

$choice_provider = array(
  'api_key' => $api_key,
);

$similar_random_page = rand(1,500);
$similar_array = array(
  'api_key' => $api_key,
  'language'=> 'ko',
  'page' => 1,
  'watch_region' => 'KR',
  'with_watch_providers' => '8|337|97|356',
);

$prov_array = array(
  'api_key' => $api_key,
  'language'=> 'ko',
  'page' => 1,
  'watch_region' => 'KR',
  // 'with_watch_providers' => '8|337|97|356',
);

$movie_search_array = array(
  'api_key' => $api_key,
  'language'=> 'ko',
  'region' => 'KR',
  'page' => 1,
  'watch_region' => 'KR',
  // 'with_watch_providers' => '8|337|97|356',
  // 'id' => '1049233&436270',
);

$choice_id = 119051;

$base_url_aaa = 'https://api.themoviedb.org/3';
$base_url = 'https://api.themoviedb.org/3/discover/tv';
$provide_base_url = 'https://api.themoviedb.org/3/watch/providers/tv';
$provide_link_url = 'https://api.themoviedb.org/3/watch/providers/movie?api_key='.$api_key.'&language=Ko';
$movie_details = 'https://api.themoviedb.org/3/tv/94997?api_key='.$api_key.'&language=Ko';
$movie_details_provider = 'https://api.themoviedb.org/3/tv/1402/watch/providers?api_key='.$api_key.'&language=Ko';
$genre_url = "https://api.themoviedb.org/3/genre/movie/list?" . http_build_query($genre, '', );
$choice_provider_url = "$base_url_aaa/tv/$choice_id/watch/providers?" . http_build_query($choice_provider, '', );
$similar = 'https://api.themoviedb.org/3/tv/1402/similar?' . http_build_query($similar_array, '', );;

$prov_check = "https://api.themoviedb.org/3/watch/providers/movie?". http_build_query($prov_array, '', );

$movie_search = "https://api.themoviedb.org/3/search/movie?id=1049233&". http_build_query($movie_search_array, '', );


$url = $base_url . "?" . http_build_query($data, '', );
$url2 = $provide_base_url . "?" . http_build_query($provide, '', );
$url3 = $provide_link_url;
$url4 = $movie_details;
$url5 = $movie_details_provider;
$url6 = $genre_url;
$url7 = $choice_provider_url;
$url8 = $similar;
$url9 = $prov_check;
$url10 = $movie_search;

$ch = curl_init();                                 //curl 초기화
curl_setopt($ch, CURLOPT_URL, $url9);               //URL 지정하기
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
 
$providers_id = ['8', '337', '97', '356'];
// return $response;
// function getTitle($count) {
    // $a = $sResponse['results'];
    // for ($i=0; $i<count($sResponse); $i++) {
        // print($sResponse['results'][$i]['title']);
        // $a =  $sResponse['results'];
        // print_r($sResponse['results'][$i]);
        print("<br><br>");
        print_r($sResponse);
        // print_r($sResponse);
        // print_r($sResponse['cast']);
      //   foreach($sResponse['crew'] as $c){
      //   print_r(strstr($c['job'], 'Director') ? $c['name'].' : '.$c['job'] : '');
      // }
        // print_r($sResponse['cast'][0]['name'].' : '.$sResponse['cast'][0]['character']);
        print("<br><br>");
        // print_r($sResponse['created_by'][0]);



        //크롤링, url5로 하면 가져올수있음
include('./simple_html_dom/simple_html_dom.php'); 
        //가져올 url 설정
$platform_url = $sResponse['results']['KR']['link']; 
$platform_html = @file_get_html($platform_url); 

$open_ott_page = [];
unset($arr_result); 
$arr_result = $platform_html->find('.ott_filter_best_price > div > a');   //1위 ~ 3위 랭킹순위 및 프로그램명 가져오기
if(count($arr_result) > 0){                         //위의 이미지에서 a 태그에 포함되는 html dom 객체를 가져옴
    foreach($arr_result as $e){

        //children 속성을 이용해 맨 처음(0)의 태그 가져오기(<span class="rank_num">1</span>값 가져옴)
        $df = $e->href;     //위의 값 중 1 값을 가져옴
        array_push($open_ott_page, [$e->href, $e->children(0)->src]);
        // echo $df;
        echo "<br/><br/>";
        // ->children(0)->plaintext.':'
        //children 속성을 이용해 맨 두번째(1)의 태그(<div class="tv_info">) 안의 두번째(1)의 태그 가져오기(<p class="program">미스트롯</p>값 가져옴)
             //위의 값 중 미스트롯 값을 가져옴
    } 

  //   foreach($platform_html->find('.ott_filter_best_price > div > a > img') as $e){
  //     $i=0;
  //     //children 속성을 이용해 맨 처음(0)의 태그 가져오기(<span class="rank_num">1</span>값 가져옴)
  //     array_push($open_ott_page[$i], $e->src);
  //     // echo $df;

  //          $i++;
  // } 
} else { 
    echo "<br/>"; 
} 

foreach($open_ott_page as $ott_url) {
        ?>
 <div><?=$ott_url[0]?></div>
 <br>
  <div><?=$ott_url[1]?>
                            </div>

<?php
}
?>

<?php


?>


        <div style="width : 100%;">
        <img style="width : 70%;" src="https://image.tmdb.org/t/p/original/<?=$sResponse['backdrop_path']?>">
        <div>
        <?php
        // print($sResponse['results']);
    // }
    // }
    // foreach($providers_id as $a) {
    // print_r($sResponse["results"]);
                        // $provider_logo_path = [];
                        // for ($i=0; $i < count($sResponse["results"]); $i++) {
                        //     foreach ($providers_id as $aa) {
                        //         if ($aa == $sResponse["results"][$i]['provider_id']) {
                        //             array_push($provider_logo_path, $sResponse["results"][$i]['logo_path']);
                                    
                        //     } else {

                        //     }
                        // }
                        // // print_r($sResponse["results"][$i]);
                        // }
                        // foreach($provider_logo_path as $prov_logo_path) {
                        //     ?>
                        // <img src="https://image.tmdb.org/t/p/original/<?=$prov_logo_path?>">
                        // <?php
                        // }
    // print("<br><br>");
    // }
// }
// print("<br><br><br>" . $url);

        ?>
        
        <main>
        <div style="width : auto; height : 330px;
	overflow:hidden;
	text-align:center;
	margin: 0 auto;">
            <!-- https://www.themoviedb.org/t/p/w220_and_h330_face/sLTAEFtjentQ5satiGdmv7o2f1C.jpg -->
                    <img 
                    style="width : auto; height :300px; display : flex; object-fit:cover;"
                    src=" https://image.tmdb.org/t/p/original/mEdMHGy1FfCUc7PskFO0tibm8jp.jpg" 
                    alt="">
            </div>
        <?php
        
                for ($i = 0; $i < count($sResponse['results']); $i++) {
                ?>
            <!-- <div><?= print_r($a) ?></div> -->
            <div style="width : auto; height : 330px;
	overflow:hidden;
	text-align:center;
	margin: 0 auto;">
            <!-- https://www.themoviedb.org/t/p/w220_and_h330_face/sLTAEFtjentQ5satiGdmv7o2f1C.jpg -->
                    <img 
                    style="width : 300px; height : auto; display : flex; object-fit:cover;"
                    src=" https://image.tmdb.org/t/p/original/<?=$sResponse['results'][$i]['backdrop_path']?>" 
                    alt="">
            </div>
    <?php
        }
        ?>
        </main>
    </body>
</html>

<!-- <a href="https://click.justwatch.com/a?cx=eyJzY2hlbWEiOiJpZ2x1OmNvbS5zbm93cGxvd2FuYWx5dGljcy5zbm93cGxvdy9jb250ZXh0cy9qc29uc2NoZW1hLzEtMC0wIiwiZGF0YSI6W3sic2NoZW1hIjoiaWdsdTpjb20uanVzdHdhdGNoL2NsaWNrb3V0X2NvbnRleHQvanNvbnNjaGVtYS8xLTItMCIsImRhdGEiOnsicHJvdmlkZXIiOiJOZXRmbGl4IiwibW9uZXRpemF0aW9uVHlwZSI6ImZsYXRyYXRlIiwicHJlc2VudGF0aW9uVHlwZSI6IjRrIiwiY3VycmVuY3kiOiJLUlciLCJwcmljZSI6MCwib3JpZ2luYWxQcmljZSI6MCwiYXVkaW9MYW5ndWFnZSI6IiIsInN1YnRpdGxlTGFuZ3VhZ2UiOiIiLCJjaW5lbWFJZCI6MCwic2hvd3RpbWUiOiIiLCJpc0Zhdm9yaXRlQ2luZW1hIjpmYWxzZSwicGFydG5lcklkIjo2LCJwcm92aWRlcklkIjo4LCJjbGlja291dFR5cGUiOiJqdy1jb250ZW50LXBhcnRuZXItZXhwb3J0LWFwaSJ9fSx7InNjaGVtYSI6ImlnbHU6Y29tLmp1c3R3YXRjaC90aXRsZV9jb250ZXh0L2pzb25zY2hlbWEvMS0wLTAiLCJkYXRhIjp7InRpdGxlSWQiOjEwNDAxOSwib2JqZWN0VHlwZSI6InNob3ciLCJqd0VudGl0eUlkIjoidHMxMDQwMTkiLCJzZWFzb25OdW1iZXIiOjAsImVwaXNvZGVOdW1iZXIiOjB9fV19&amp;r=http%3A%2F%2Fwww.netflix.com%2Ftitle%2F80214497&amp;uct_country=kr" title="Watch 1899 on Netflix" target="_blank" rel="noopener"><img src="/t/p/original/t2yyOv40HZeVlLjYsCsPHnWLk4W.jpg" width="50" height="50"></a> -->
<!-- document.querySelector("#ott_offers_window > section > div.header_poster_wrapper > div > div.ott_provider > div > ul > li:nth-child(1) > div > a") -->