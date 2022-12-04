<?php
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
        // 'watch_region' => 'KR',
        'language' => 'ko',
        // 'page' => 1
    ),
    // 인기순
    // array(
    //     'api_key' => $api_key,
    //     'with_watch_providers' => [8, 337, 97, 356],
    //     // 'with_watch_providers' => 337,
    //     // 'with_watch_providers' => 97,
    //     // 'with_watch_providers' => 356,
    //     'sort_by' => 'popularity.desc',
    //     'watch_region' => 'KR',
    //     'language' => 'ko',
    //     'page' => 1
    // ),
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
    // 선택한 컨텐츠 정보
    $base_url . "/$choice_content/$choice_id?" . http_build_query($data[0], '', ),
    // 선택한 컨텐츠 provider
    $base_url . "/$choice_content/$choice_id/watch/providers?" . http_build_query($data[1], '', ),
    // 
    $base_url . "/$choice_content/$choice_id/credits?" . http_build_query($data[1], '', ),
    
    // 인기순
    // $base_url . "/discover/movie?" . http_build_query($data[1], '', ),
    // $base_url . "/discover/tv?" . http_build_query($data[1], '', ),
    // 플랫폼
    $base_url . "/watch/providers/tv?" . http_build_query($data[1], '', )
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

// 크롤링 (플랫폼 클릭 시 해당 페이지로 이동)
include('./simple_html_dom/simple_html_dom.php'); 
        //가져올 url 설정
$platform_url = $sResponse[1]['results']['KR']['link']; 
$platform_html = @file_get_html($platform_url); 

$open_ott_page = [];

unset($arr_result); 
$arr_result = $platform_html->find('#ott_offers_window > section > div.header_poster_wrapper > div > div:nth-child(4) > div > ul > li.ott_filter_best_price > div > a');   //1위 ~ 3위 랭킹순위 및 프로그램명 가져오기
if(count($arr_result) > 0){                         //위의 이미지에서 a 태그에 포함되는 html dom 객체를 가져옴
    foreach($arr_result as $e){

        //children 속성을 이용해 맨 처음(0)의 태그 가져오기(<span class="rank_num">1</span>값 가져옴)
        array_push($open_ott_page, [$e->href, $e->children(0)->src]);     //위의 값 중 1 값을 가져옴
    } 
} 


$content = $sResponse[0];

// 제목 가져오는거 컨텐츠에 따라 결정
$title_change = $choice_content == 'movie' ? 'title' : 'name';
$original_title_change = $choice_content == 'movie' ? 'original_title' : 'original_name';
        
// 감독 가져오기 (영화면 credits에서 director가져와야 함)
// created_by
$director = [];
if ($choice_content == 'movie') {
    $count = 0;
    foreach($sResponse[2]['crew'] as $crew){
        if (strstr($crew['job'], 'Director')) {
            array_push($director, [$crew['name']." - ".$crew['job']]);
            $count++;
        };
    
    if($count > 4){
        array_push($director, ['...']);
        break;
    };
    }
} else {
    foreach($sResponse[0]['created_by'] as $create) {
        array_push($director, [$create['name']]);
    }
}

// 배우 가져오기 (credits에서 actor)
$actors = [];
$actors_count = 0;
$actors_all_count = count($sResponse[2]['cast']);
foreach($sResponse[2]['cast'] as $cast){
        array_push($actors, [$cast['name']." - <br>".$cast['character']]);
        $actors_count++;

        if($actors_count > 6){
            
            array_push($actors, ['...']);
            break;
        };
}

// 개봉일 추출 (영화는 $content['release_date']), Tv는 $content['last_air_date']
$open_date = $choice_content == 'movie' ? $content['release_date'] : $content['last_air_date'];

?>


<!DOCTYPE html>
<html>


<head>
    <meta charset="utf-8">
    <title>MovieVerse</title>
    <link rel="shortcut icon" href="./img/logo/logo_text_x.png">
    
    <link rel="stylesheet" type="text/css" href="css/choice.css">
    <link rel="stylesheet" type="text/css" href="css/basic.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<script>
List = [];
</script>
<script>
        const drawStar = (target) => {
            document.querySelector(`.star span`).style.width = `${target.value * 10}%`;

            let element = document.getElementById('box');
            let element2 = element.offsetWidth;

            document.getElementById('starvalue').value = element2;


            document.getElementById('starvalue').value = element2;
        }

        window.onload = function() { //윈도우가 열리면
            document.getElementById("A").onclick = function() {

                let element = document.getElementById('text_crear');
                document.getElementById('text_crear').value = "";
                document.querySelector('.star span').style.width = 0;
            alert('취소했습니다.');

            }
        }
</script>

<div>
<?php
	require("db_connect.php");
	session_start();
	$_SESSION["userId"] = empty($_SESSION["userId"]) ? "" : $_SESSION["userId"];
	$_SESSION["userNum"] = empty($_SESSION["userNum"]) ? "" : $_SESSION["userNum"];
  $query3 = $db->query("SELECT title FROM tv UNION SELECT title  FROM movie "); // 영화 제목 + 드라마 제목 전체 (중복 X)

  /*
  조회한 다수의 SELECT 문을 하나로 합치고싶을때 유니온(UNION) 을 사용 할 수 있습니다.
  UNION 은 결과를 합칠때 중복되는 행은 하나만 표시해줍니다.
  UNION ALL 은 중복제거를 하지 않고 모두 합쳐서 보여줍니다.
  */

	while ($row = $query3->fetch()) {

?>
    <script>
      List.push('<?=$row['title'];?>'); // 제목 리스트에 넣기
    </script>
<?php
}

// 북마크 (로그인인지, 북마크 체크되어 있는지)

$bookmark_link = '';
$bookmark_checked_class = '';
$bookmark=$db->query("select count(*) from  bookmark  where member_num='$_SESSION[userNum]' and choice_num = $choice_id and choice ='$choice_content'")->fetchColumn();
if($_SESSION["userId"]!=""){
    $bookmark_link = 'join_bookmark.php?id='.$choice_id.'&choice='.$choice_content;
    if($bookmark>0){
        $bookmark_checked_class = 'checked_bm';
    } else {
        $bookmark_checked_class = 'unchecked_bm'; 
    }
} else {
    $bookmark_link = 'login.php';
    $bookmark_checked_class = 'unchecked_bm'; 
}
?>

</div>
<!--검색-->
<script>
    $(function() {
        $("#searchInput").autocomplete({
            source: List,   // 자동완성 대상
            focus: function(event, ui) { //포커스 시 이벤트
                return false;
            },
            minLength: 1,   // 최소 글자 수
            delay: 100,     //글자 입력 후 이벤트 발생까지 지연 시간
        });
    });
</script>

<body>
    <?php

	$choice = $_GET['choice']; // 선택한 오브젝트 가져오기
	if($choice=='tv'){   // 카테고리에 따라서 GNB 색상 변경
		$dra= "#3482EA";
		$mo="black";
	}else if($choice=='movie'){
		$dra= "#black";
		$mo="#3482EA";
	}
?>
    <div class="all"> <!--전체 너비 설정-->
    <header class="header_scroll_top">
            <div class="head">  <!--header GNB-->
                <div class="header_left">
                    <ul>
                        <li><img class="logo" onclick="location.href='index.php'" src="img/logo/logo_txt.png"></li>
                        <li><a class="header_gnb" onclick="location.href='movie.php?bid=';"> 영화</a></li>
                        <li><a class="header_gnb" onclick="location.href='drama.php?bid=';"> 드라마/시리즈</a></li>

                        <?php 
                            if($_SESSION["userId"]=="admin") {
                        ?>
                            <li><a class="header_gnb" onclick="location.href='./phptest/list.php';"> 관리자 페이지</a></li>
                        <?php  
                        } 
                        ?>
                    </ul><!--header_left END-->
                </div>

                <div class="header_right">
                    <ul>   <!-- header_right -->
                    <!-- 검색 버튼 -->
                    <li>
                        <div class="search_button">
                            <input class="search_img" name="button" type="image" src="img/search_img.png" />
                        </div>
                    </li>
                    <li>
<?php 
// 사용자 프로필 사진
if($_SESSION["userId"]!=""){ // 로그인 됐을 경우
    $query3 = $db->query("select * from user where email='$_SESSION[userId]'"); 
    while ($row = $query3->fetch()) {
        $iset=$row['img_link'];
    }
?>
                        <img class="user_img" src="user_img/<?= $iset?>">
<?php
}else{ // 로그아웃일 경우
?>
                        <button class="login_btn" onclick="location.href='login.php';">로그인</button>
<?php	   
}
?>              
                    </li>
                </ul>  <!-- header_right END -->
            </div>
            
        </div> <!--head END-->
        
            <div class="hide" id="userDiv"><!--유저 정보 프로필-->
                <ul>
                    <li><h3><img class="userimg" src="user_img/<?= $iset?>"><?=$_SESSION["userName"]?></h3></li>
                    <li onclick="location.href='myinfo.php';">내 계정정보</li> 
                    <!-- <img class="userimg" src="img/user.png"> -->
                    <li onclick="location.href='pwre.php';">비밀번호 수정</li>
                    <!-- <img class="userimg" src="img/lock.png"> -->
                    <li onclick="location.href='mybook.php';">내 북마크</li>
                    <!-- <img class="userimg" src="img/bookmark.png"> -->
                    <li onclick="location.href='myreview.php';">작성한 평가</li>
                    <!-- <img class="userimg" src="img/review.png"> -->
                    <li onclick="location.href='log_out.php';">로그아웃 </li>
                    <!-- <img class="userimg" src="img/logout.png"> -->
                </ul>
            </div>  <!--userDiv END-->

            <!-- min-width:768px부터 header, 햄버거 메뉴 -->
            <div class="width_768px_logo"><img class="logo" onclick="location.href='index.php'" src="img/logo/logo_txt.png"></div>
                <div class="width_768px_search_button search_button">
                    <input class="search_img" name="button" type="image" src="img/search_img.png" />
                </div>
                <input type="checkbox" id="menu_icon">
                <label for="menu_icon">  
                    <!--label은 인라인 스타일-->
                    <span></span>
                    <span></span>
                    <span></span>
                </label>
            <div class="menu_sidebar">
                <ul class="menu_sidebar_wrapper">
                    <!-- 로그인 상태 여부 -->
<?php
if ($_SESSION["userId"] != "") {
?>
                <li class="menu_font_size"><img class="menu_userimg" src="user_img/<?= $iset?>"><h3><?=$_SESSION["userName"]?></h3></li>          
<?php
} else {
?>
                    <li class="menu_font_size menu_login_btn"><button onclick="location.href='login.php';">로그인</button></li>          
<?php
}
?>

                    <li class="menu_font_size"><a class="header_gnb" onclick="location.href='movie.php?bid=';"> 영화</a></li>
                    <li class="menu_font_size"><a class="header_gnb" onclick="location.href='drama.php?bid=';"> 드라마/시리즈</a></li>
<?php 
if($_SESSION["userId"]=="admin") {
?>
                    <li class="menu_font_size"><a class="header_gnb" onclick="location.href='./phptest/list.php';"> 관리자 페이지</a></li>
<?php  
} 
if($_SESSION["userId"]!=""){ // 로그인 됐을 경우
?>
                    <li class="menu_font_size"><a class="header_gnb" onclick="location.href='myinfo.php';">내 계정정보</a></li>
                    <li class="menu_font_size"><a class="header_gnb" onclick="location.href='pwre.php';">비밀번호 수정</a></li>
                    <li class="menu_font_size"><a class="header_gnb" onclick="location.href='mybook.php';">내 북마크</a></li>
                    <li class="menu_font_size"><a class="header_gnb" onclick="location.href='myreview.php';">작성한 평가</a></li>
                    <li class="menu_font_size"><a class="header_gnb" onclick="location.href='log_out.php';">로그아웃</a></li>
<?php
}
?>
                </ul>
                <ul class="menu_sidebar_wrapper">
                </ul>
            </div>
        </header>

        <!-- 검색창 -->
        <div class="search_modal">
            <div class="search_modal_close">
                <img src="./img/close_icon_white.png">
            </div>
            <div class="search_wrapper">
                    <form class="search" action="search_result.php" method="get">
                        <input id="searchInput" type="text" name="search" 
                        placeholder="제목, 배우를 검색해주세요"
                         onfocus="this.placeholder=''" 
                         onblur="this.placeholder='제목, 배우를 검색해주세요'"
                        size="70" required="required"/>
                        <input class="search_Img" name="button" type="image" src="img/search_img.png" />
                    </form>
            </div>
        </div>
        <!-- search_modal END -->
        <div id='wrap'>
            <div class="choice_img_div"><!--이미지-->
                    <!-- 인기 영화 메인화면에 출력 -->
                    <img class="choice_img" 
                    src="<?=$tmdb_img_base_url.$sResponse[0]['backdrop_path']?>"
                    alt="poster"
                    onerror="this.src='<?=$tmdb_img_base_url.$sResponse[0]['poster_path']?>'"
                    >
                <!-- <div class="choice_img_gradient_top"></div> -->
                <div class="choice_img_gradient_bottom"></div>
            </div>
            <!--choice_img_div END-->


            
            <div class="choice_container">
                <div class="choice_wrap_header">
                    <div class="main_title"><?=$content[$title_change]?>(<?=substr($open_date, 0 ,4)?>)</div>
                    <div class="choice_wrap_header_right">
                        <div>TMDB <?=round($content["vote_average"],1)?></div>
                        <div class="bookmark_header <?=$bookmark_checked_class?>">
                            <a href="<?=$bookmark_link?>">
                                <img src="./img/bookmark_icon.png" alt="bookmark_icon"/>북마크
                            </a>
                        </div>
                    </div>
                </div>
                <div class="choice_wrap">
                    <div class="choice_wrap_left">
                        <div class="poster_wrap"><img src="<?=$tmdb_img_base_url.$content["poster_path"]?>"/></div>
                                <div class="bookmark <?=$bookmark_checked_class?>">
                                    <a href="<?=$bookmark_link?>">
                                    <img src="./img/bookmark_icon.png" alt="bookmark_icon"/>북마크
                                    </a>
                                </div>
                        <div class="providers_link">
                            <?php
                            foreach($open_ott_page as $ott_url) {
                            ?>
                                <span><a href="<?=$ott_url[0]?>" target='_blank'>
                                    <img width="50" height="50" src="<?=$tmdb_img_base_url.$ott_url[1]?>"/>
                                </a></span>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <!-- choice_left END -->
                    <div class="choice_wrap_center">
                        <ul class="center_main_ul">
                            <li><div><?=$content[$original_title_change]?></div></li>
                            <li><div>상세정보</div></li>
                            <ul>
                                <li><div>감독 : 
                                    <span class="crew_cast">
                                        <?php
                                        foreach($director as $dir){
                                        ?>
                                        <span>
                                            <?=$dir[0]?>
                                        </span>
                                        <?php
                                        }
                                        ?>
                                    <span>
                                </div></li>
                                <li><div>언어 : <?=$content['original_language']?></div></li>
                                <li><div>개봉일 : <?=$open_date?></div></li>
                                <li><div>장르 : 
                                    <span class="genres">
                                        <?php
                                        foreach($content['genres'] as $genre){
                                        ?>
                                        <span>
                                            <?=$genre['name']?>
                                        </span>
                                        <?php
                                        }
                                        ?>
                                    </span>
                                </div></li>
                            </ul>
                            <li><div>시놉시스</div></li>
                            <ul>
                                <li><div><?=$content['overview']?></div></li>
                            </ul>
                        </ul>
                    </div>
                    <!-- choice_center END -->
                    <div class="choice_wrap_right">
                        <div>배우<span>(<?=$actors_all_count?>)</span></div>
                        <div> 
                            <span class="crew_cast">
                                <?php
                                foreach($actors as $act){
                                ?>
                                <span>
                                    <?=$act[0]?>
                                </span>
                                <?php
                                }
                                ?>
                            <span>
                        </div>
                    </div>
                    <!-- choice_right END -->
                </div>
                <!-- choice_wrapEND -->
<?php
//현재 열려있는 컨텐츠 별점 평균
$star_avg = '';
$star_query = $db->query("select truncate(avg(star_rating),1) as star_avg from review where choice_content='$choice_content' and content_id=$choice_id ");
while ($row = $star_query->fetch()) {
$star_avg = $row['star_avg'];
}

$default_text = '';
$review_readonly = '';
$review_action = 'login.php';
$review_button = '로그인';

$my_review_date = '';
$my_review_content = '';
$my_star_rating = 0;
$my_review_good = 0;
$my_review_hate = 0;

$review_revise = isset($_REQUEST['re']) ? $_REQUEST['re'] : '';

if ($_SESSION["userNum"]=="") {
    // 로그아웃 상태일 때
    $default_text = '작품에 대한 리뷰를 남기려면 로그인이 필요합니다.';
} else {
    // 로그인 상태일 때
    $review_state = $db->query("select count(*) from review where choice_content='$choice_content' and content_id=$choice_id and member_num = '$_SESSION[userNum]'");
    // 작성한 리뷰가 있다면
    if ($review_state != 0) {
            // 내가 작성한 리뷰 가져오기
            $my_review_query = $db->query("select * from review where choice_content='$choice_content' and content_id=$choice_id and member_num = '$_SESSION[userNum]'");
            while ($row = $my_review_query->fetch()) {
                $my_review_date = $row['review_date'];
                $my_review_content = $row['review_content'];
                $my_star_rating = $row['star_rating'];
                $my_review_good = $row['good'];
                $my_review_hate = $row['hate'];
            }
            // 수정하기 페이지라면
            if ($review_revise != '') {
                $review_action = 'review_star.php?choice='.$choice_content.'&id='.$choice_id.'';
                $review_button = '수정완료';
            } else {
                $review_button = '수정하기';
                $review_action = 'choice.php?choice='.$choice_content.'&id='.$choice_id.'&re=re';
                $review_readonly = 'readonly';
            }   
    } else {

        $review_button = '작성하기';
        $review_action = 'review_star.php?choice='.$choice_content.'&id='.$choice_id.'';
        $default_text = '별점과 리뷰를 입력해주세요.';
    }
}
?>
                <div class="review_container">
                    <div class="review_title">리뷰</div>
                    <div class="review_wrap">
                        <span class="star">
                            ★★★★★
                            <span style="width:<?=$my_star_rating*10?>%" id="box">★★★★★</span>
                            <input id="star_value" type="range" oninput="drawStar(this)" value="<?=$my_star_rating?>" step="1" min="0" max="10" >
                        </span>
                        <form class="" action="<?=$review_action?>" method="post">
                            <input id="starvalue" name="starvalue" type="hidden" value="" />
                            <input name="id" type="hidden" value="<?=$choice_id?>">
                            <textarea id="text_crear" class="text_review" name="textreview" 
                            value="<?=$my_review_content?>"
                            placeholder="<?=$default_text?>"
                            <?=$review_readonly?>
                            >
                                <?=$my_review_content?>
                            </textarea>

                            <div class="review_buttons">
                                <button type="submit" class="yesi"><?=$review_button?></button>
                            <?php
                            if ($_SESSION["userNum"]!="") {
                                if ($review_revise != '') {
                                ?>
                                <!-- <input id="A" type="button" class="noi" value="취소"/> -->
                                <button type="button" onclick="location.href='choice.php?choice=<?=$choice_content?>&id=<?=$choice_id?>" class="delbutton">취소</button>
                                
                                <?php
                                } else {
                                ?>
                                <button type="button" onclick="location.href='revew_del.php?choice=<?=$choice_content?>&id=<?=$choice_id?>'" class="delbutton">삭제</button>
                                <?php
                                }
                            }
                                ?>
                            </div>

                         </form>
                    </div>
                </div>
                <!-- review_container END -->

                <div class="review_container">
                    <div class="review_title">리뷰</div>
                    <!-- <div class="review_wrap">
                        <span class="star">
                            ★★★★★
                            <span id="box">★★★★★</span>
                            <input id="star_value" type="range" oninput="drawStar(this)" value="" step="1" min="0" max="10">
                        </span>
                        <form class="" action="review_star.php?choice=<?=$choice_content?>&id=<?=$choice_id?>" method="post">
                            <input id="starvalue" name="starvalue" type="hidden" value="" />
                            <input name="id" type="hidden" value="<?=$choice_id?>">
                            <textarea id="text_crear" class="text_review" name="textreview" value=""></textarea>

                            <div class="review_buttons">
                                <input id="A" type="button" class="noi" value="취소"/>
                                <button class="yesi">제출</button>
                            </div>
                        </form>
                    </div> -->
                </div>
            </div>
            <!-- choice_container END -->







        <?php
$pougodd=empty($_REQUEST["pougodd"]) ? "good" : $_REQUEST["pougodd"];
	$pxs=0;
	$choice = $_GET['choice'];
	$id = $_GET['id'];
		#서비스 중인 플랫폼 링크
	$arr = array();
	$q = 3;

	// $query3 = $db->query("select SUBSTRING_INDEX(SUBSTRING_INDEX($choice.site_path  ,'|', -$q), '|', 1) as substy FROM $choice WHERE $choice.id=$id;");
/*
 문자열 자르기 함수
SUBSTRING(문자열, 시작 위치)
SUBSTRING(문자열, 시작 위치, 시작 위치부터 가져올 문자수)
*/

//우리 별점 평균
$query3 = $db->query("select truncate(avg(star_rating),1) as star_avg from review where content_id=$choice_id");
while ($row = $query3->fetch()) {
$star_avg= $row['star_avg'];
}


		// $query3 = $db->query("SELECT * ,COUNT(genres.genres_name) as count, GROUP_CONCAT(genres.genres_name) AS genrs_names  from tv left join genres ON tv.genre_id LIKE  CONCAT('%',genres.genre_id,'%')  where id='$id'  group by tv.id

	while ($row = $query3->fetch()) {
    }
?>   
        <p  class="sinup"><b>평가하기</b></p>
 <?php
#로그인시 작성글이 없을시
	$_SESSION["userNum"] = empty($_SESSION["userNum"]) ? " " : $_SESSION["userNum"];

	if($_SESSION["userNum"]==" "){

	?>
	<div onclick="location.href='login.php';" style="margin-top:40px;     cursor: pointer;color: grey;padding:40px 0 0 20px;height: 57px;" id="text_crear" class="text_review" name="textreview" value="" readonly>작품의 감상평을 작성하려면 <b>로그인</b> 해주세요</div>
<div style="height:40px;"></div>
<?php
	}
	$myreiview=$db->query("select count(*) from  review  where member_num='$_SESSION[userNum]' and content_id=$choice_id and choice_content = '$choice_content' ")->fetchColumn();

	if(!$myreiview and $_SESSION["userNum"]!= " "){
?>

        <?php
	}else{
                #로그인시 작성글이 있을시
        $query3 = $db->query("select *,DATE(review_date)as review_date  from review AS rv ,user AS us  WHERE rv.member_num=us.member_num and us.member_num='$_SESSION[userNum]' and choice_content='$choice_content'");


        while ($row = $query3->fetch()) {
            $ress=$db->query("select count(*) from review where id=$id and good_yes=$row[member_num] and member_num= $_SESSION[userNum]")->fetchColumn();

        $content = str_replace(" ", "&nbsp;", $row['review_content']);
                    $content = str_replace("\n", "<br>", $content);
        $re = empty($_REQUEST["re"]) ? "" : $_REQUEST["re"];
            if($re==""){
            if($ress>0){
                $good_img="goodyes.png";
            }else{
                $good_img="good.png";
            }
        ?>
        <div class="other_reviewss">
                    <div class="other_reviews">

            <span style="    cursor: default;    left: 120px;" class="star">
                    ★★★★★
                    <span style="width:<?=$row['star_rating']*10?>%" id="box">★★★★★</span>

                </span>

                        <p class="date"><?=$row['review_date']?></p>
                        <div class="review"><?=$row['review']?></div>


                        <img class="review_user_img" src="user_img/<?=$row['img_link']?>">
                        <div class="nickname"><?=$row['nickname']?></div>
                    <img  style="  cursor: pointer;  width: 20px;margin:0px 0 0 470px;"onclick="location.href='good.php?choice=<?=$choice?>&id=<?=$id?>&member_num=<?=$row['member_num']?>'"src="img/<?=$good_img?>">
                        <div style="color: #707070;margin:-24px 0 0 500px;"><?=$row['good'] ?></div>
                                                    <button onclick="location.href='choice.php?choice=<?=$choice?>&id=<?=$id?>&re=re'"class="rebutton">수정</button>
                <button onclick="location.href='revew_del.php?choice=<?=$choice?>&id=<?=$id?>'" class="delbutton">삭제</button>

                    </div>

        </div>
                <?php
            }else{
                #수정 페이지
            ?>

                <span class="star">
                    ★★★★★
                    <span style="width:<?=$row['star_rating']*10?>%" id="box">★★★★★</span>
                    <input id="star_value" type="range" oninput="drawStar(this)" value="" step="1" min="0" max="10">
                </span>
                <form class="" action="review_star.php?choice=<?=$choice  ?>" method="post">
                    <input id="starvalue" name="starvalue" type="hidden" value="<?=$row['star_rating']*16?>">
                    <input name="id" type="hidden" value="<?=$id?>">
                    <textarea id="text_crear" class="text_review" name="textreview" value=""><?=$row['review']?></textarea>

                    <input onclick="history.back()"type="button" class="noi" value="취소" />

                    <button class="yesi">제출</button>
                </form>

                <?php
            }
}
	}
	?>

        <div style="margin-bottom:30px;" class="sinup"><b>다른 이용자 평</b>
            <div style="color: <?php
			if($pougodd=="good"){
						echo'blue';
						}else{
							echo'black';
					}
						?>
					;" id="pop"onclick="location.href='choice.php?choice=<?=$choice?>&id=<?=$id?>&pougodd=good';">인기순 </div>
            <div style="color: <?php
					if($pougodd=="review_date"){
						echo'blue';
						}else{echo'black';}?>; " id="new"onclick="location.href='choice.php?choice=<?=$choice?>&id=<?=$id?>&pougodd=review_date';">최신순</div>
        </div>

<div class='v-line'></div>
<div class="line"></div>
<div style="margin-top:220px;"class="line"></div>
<div style="margin-top:440px;"class="line"></div>
<div style="margin-top:660px;"class="line"></div>
        <div class="review_table">
            <?php

	$numLines= 6;
	$numLinke= 3;

	$page = empty($_REQUEST["page"]) ? 1 : $_REQUEST["page"];
	$stat = ($page -1) * $numLines;

$count=0;
$good_img="good.png";
$query3 = $db->query("select *,DATE(review_date)as review_date  from review AS rv ,user AS us  WHERE rv.member_num=us.member_num and rv.content_id = $choice_id and rv.choice_content = '$choice_content' order by $pougodd desc LIMIT $stat , $numLines");
while ($row = $query3->fetch()) {


		$content = str_replace(" ", "&nbsp;", $row['review']);
			$row['review'] = str_replace("\n", "<br>", $content);
	$count=$count+1;
    if(	$_SESSION["userId"]!=""){
        $re=$db->query("select count(*) from good where id=$id and good_yes=$row[member_num] and member_num= $_SESSION[userNum]")->fetchColumn();

        if($re>0){
            $good_img="goodyes.png";
        }else{
            $good_img="good.png";
        }
}
?>
            <div class="other_review">
	  <span style="    cursor: default;    left: 120px;" class="star">
            ★★★★★
            <span style="width:<?=$row['star_rating']*10?>%" id="box">★★★★★</span>

        </span>

                <p class="date"><?=$row['review_date']?></p>
                <div class="review"><?=$row['review']?></div>
                <img class="review_user_img" src="user_img/<?=$row['img_link']?>">
                <div class="nickname"><?=$row['nickname']?></div>
			 <img  style="  cursor: pointer;  width: 20px;margin:0px 0 0 470px;"onclick="location.href='good.php?choice=<?=$choice?>&id=<?=$id?>&member_num=<?=$row['member_num']?>'"src="img/<?=$good_img?>">
			    <div style="color: #707070;margin:-24px 0 0 500px;"><?=$row['good'] ?></div>
            </div>

            <?php

}
?>

        </div>
        <?php
	$firstLink = floor(($page - 1)/$numLinke)*$numLinke+1;
	$lastLink = $firstLink + $numLinke -1;

	$numRecords = $db->query("select count(*) from  review  where choice_content='$choice_content' and content_id = $choice_id")->fetchColumn();
	$numPage = ceil($numRecords / $numLines);

	if($lastLink  >$numPage){
	   $lastLink = $numPage;
	}//올림은 ceil

?>
        <div class="page_num">
            <?php
		if($firstLink>1){
?>
            <a class="nones" href="choice.php?id=<?=$id?>&choice=<?=$choice?>&page=<?= $firstLink -1 ?>"> 이전 </a>
            <?php
	 }

		for ($i=$firstLink; $i <= $lastLink; $i++){
?>
            <a class="nones" href="choice.php?id=<?=$id?>&choice=<?=$choice?>&page=<?=$i?>"> <?=($i == $page) ? "<u>$i</u>" : $i?> </a>
            <?php
		}

		if($lastLink<$numPage){
?>
            <a class="nones" style='margin:0;' href="choice.php?id=<?=$id?>&choice=<?=$choice?>&page=<?=$lastLink +1?>"> 다음 </a>
            <?php
		}

?>

        </div>
    </div>
    </div>
       <!--footer-->
        <footer class="footer">
            <div class="footer_logos">
        <?php

$provider_logo_path = [];

// $sResponse에서 마지막 요소 가져오기
$footer_provider_array = end($sResponse);

for ($res_count=0; $res_count < count($footer_provider_array["results"]); $res_count++) {
    foreach ($providers_id as $prov_id) {
        if ($prov_id == $footer_provider_array["results"][$res_count]['provider_id']) {
            array_push($provider_logo_path, $footer_provider_array["results"][$res_count]['logo_path']);
        } else {
        }
    }
}

$prov_count = 0;
foreach($provider_logo_path as $prov_logo_path){
        ?>
                <div class="footer_img" onclick="location.href='movie.php?platform=<?=$providers_id[$prov_count]?>';"><img src="<?=$tmdb_img_base_url.$prov_logo_path?>"></div>
    <?php
    $prov_count++;
}
    ?>
    </div>
                <div class="footer_text">신구대학교 팀프로젝트 6조
                    <br>
                    권은진 강민지 천서정 시지원 김나영
                    <br><br>
                    성남시 중원구 광명로377(금광2동 2685) 신구대학교 산학관 110호 
                </div>
            </footer>
            <!--footer END-->
</body>

</html>
<script type="text/javascript" src="./js/header.js"></script>
