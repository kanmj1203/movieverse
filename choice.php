<?php

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
    // array(
    //     'api_key' => $api_key,
    //     'with_watch_providers' => 8,
    //     'with_watch_providers' => 337,
    //     'with_watch_providers' => 97,
    //     'with_watch_providers' => 356,
    //     'sort_by' => 'release_date.desc',
    //     'watch_region' => 'KR',
    //     'language' => 'ko',
    //     'page' => 1
    // ),
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
    // 최신순
    // $base_url . "/discover/movie?" . http_build_query($data[0], '', ),
    // $base_url . "/discover/tv?" . http_build_query($data[0], '', ),
    // 인기순
    // $base_url . "/discover/movie?" . http_build_query($data[1], '', ),
    // $base_url . "/discover/tv?" . http_build_query($data[1], '', ),
    // 플랫폼
    $base_url . "/watch/providers/tv?" . http_build_query($data[0], '', )
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
        

        <?php
$pougodd=empty($_REQUEST["pougodd"]) ? "good" : $_REQUEST["pougodd"];
	$pxs=0;
	$choice = $_GET['choice'];
	$id = $_GET['id'];
		#서비스 중인 플랫폼 링크
	$arr = array();
	$q = 3;
while($q!=0){   // q가 0일 때 까지

	$query3 = $db->query("select SUBSTRING_INDEX(SUBSTRING_INDEX($choice.site_path  ,'|', -$q), '|', 1) as substy FROM $choice WHERE $choice.id=$id;");
/*
 문자열 자르기 함수
SUBSTRING(문자열, 시작 위치)
SUBSTRING(문자열, 시작 위치, 시작 위치부터 가져올 문자수)
*/
while ($row = $query3->fetch()) {

		$arr[] = $row['substy'];

};
$q--;
		};
$count=count($arr);
if($arr[1]!==$arr[2] and $arr[1]===$arr[0]){
$count=2;
}else if($arr[1]===$arr[0]){
	$count=1;
}

    if($arr[0]==Null){
      $count=0;

    }

	#서비스 중인 플랫폼 이미지  링크
$plat_img = array();
	$q = 3;
while($q!=0){
	$query3 = $db->query("select SUBSTRING_INDEX(SUBSTRING_INDEX($choice.provider_id  ,'/', -$q), '/', 1) as substy FROM $choice WHERE $choice.id=$id");
while ($row = $query3->fetch()) {

		if( $row['substy']==337){
		$plat_img[] ="Disney_Plus.png";
		}else if($row['substy']==8){
		$plat_img[] ="Netflix.png";
		}else if($row['substy']==1){
			$plat_img[] ="wavve.png";
		}else if($row['substy']==97){
		$plat_img[] ="watcha.png";

		}
};
	$q--;
		};
if($count==2){
	$plat_img[1]=$plat_img[2];
}
//우리 별점 평균
$query3 = $db->query("select truncate(avg(star_rating),1) as star_avg from review where movie_id='$_GET[id]' or tv_id='$_GET[id]'");
while ($row = $query3->fetch()) {
$star_avg= $row['star_avg'];
}


	if($choice=='tv'){
		$tv_movie= 'tv_id';
		$query3 = $db->query("SELECT * ,COUNT(genres.genres_name) as count, GROUP_CONCAT(genres.genres_name) AS genrs_names  from tv left join genres ON tv.genre_id LIKE  CONCAT('%',genres.genre_id,'%')  where id='$id'  group by tv.id
");
	}else if($choice=='movie'){
		$tv_movie= 'movie_id';

$query3 = $db->query("SELECT * ,COUNT(genres.genres_name) as count , GROUP_CONCAT(genres.genres_name) AS genrs_names  from movie left join genres ON movie.genre_id LIKE  CONCAT('%',genres.genre_id,'%')  where id='$id'  group by movie.id
");
	}
$bookmark=$db->query("select count(*) from  bookmark  where member_num='$_SESSION[userNum]' and review_num=$id  ")->fetchColumn();

	while ($row = $query3->fetch()) {


	?>
        <div class="marin_img_gradation"></div>
        <img class="main_img" src="https://image.tmdb.org/t/p/original<?=$row['backdrop_path'];?>">
        <div class="round-div">
<div class="movie_bookmark">
            <img class="moive" style="margin-left: <?=$pxs?>px " class="movie" src="https://image.tmdb.org/t/p/w220_and_h330_face<?=$row['poster_path'];?>">
<?php
if(	$_SESSION["userId"]!=""){
	if($bookmark>0){
	?>
	<img class="bookmark_yes" src="img/bookmark_yes.png"onclick="location.href='join_bookmark.php?id=<?= $id?>&choice=<?= $choice?>';">
    <?php
	}else{
		?>
		 	<img class="bookmark_yes" src="img/bookmark_no.png"onclick="location.href='join_bookmark.php?id=<?= $id?>&choice=<?= $choice?>';">

<?php
	}
}

?>
</div> <!--bookmark END-->
  <ul class="ul-text">
                <li style="margin-bottom:20px;"><strong><?=$row['title'];?></strong>
                  <?php if($row['age']!=Null){
                    ?>
                    <img style="width: 32px;margin:0 0 -5px 14px;"src="img/<?=$row['age'];?>.png">
                  <?php
                                }
                    ?>
                </li>
                <li style="margin-bottom:15px;"><b>개봉년도 </b><?=$row['release_date'];?><b> 장르 </b><?=$row['genrs_names'];?></li>
                <li style="margin-bottom:5px;"><b>출연</b></li>
                <li style="font-size:16px;"><b>감독 </b>: <?=$row['director'];?></li>
                <li style="font-size:16px;margin-bottom:5px;    height: 40px;    width: 900px;  overflow:auto;    line-height: normal;"><b>배우 </b>: <?=$row['cast'];?></li>
                <li style="margin-bottom:7px;"><b>평점</b></li>
                <li style="margin-bottom:6px;">
                    <div style="float:left;" class="top_star_echo">
                        <p style="padding-left:10px;">TMDB평점 ★ <?=$row['vote_average']/2;?></p>
                    </div>
                    <div style="left:24px;" class="top_star_echo">
                        <p>무비버스 평점 ★<?=round($star_avg/2,1);?>
                    </div>
                </li>
                <li><b>서비스 중인 플랫폼</li>

                <li>
<?php
$i=0;

	while($i<$count)	{

?>

                    <img class="logo_imgs"onclick="window.open('<?=$arr[$i] ?>')" src="img/<?=$plat_img[$i] ?>">
<?php
		$i++;
	}
?>
                </li>
            </ul>

            <p style="left:62px;margin-top:90px;margin-bottom:30px;" class="sinup"><b>시놉시스</b>
                <p>
                    <p class="main_text"><?=$row['overview'];?><p>

        </div>

        <?php
	}
?>
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
	$myreiview=$db->query("select count(*) from  review  where member_num='$_SESSION[userNum]' and $tv_movie=$id  ")->fetchColumn();

	if(!$myreiview and $_SESSION["userNum"]!= " "){
?>
        <span class="star">
            ★★★★★
            <span id="box">★★★★★</span>
            <input id="star_value" type="range" oninput="drawStar(this)" value="" step="1" min="0" max="10">
        </span>
        <form class="" action="review_star.php?choice=<?=$choice?>" method="post">
      <input id="starvalue" name="starvalue" type="hidden" value="">
            <input name="id" type="hidden" value="<?=$id?>">
            <textarea id="text_crear" class="text_review" name="textreview" value=""></textarea>

            <input id="A" type="button" class="noi" value="취소" />

            <button class="yesi">제출</button>
        </form>
        <?php
	}else{
                #로그인시 작성글이 있을시
        $query3 = $db->query("select *,DATE(review_date)as review_date  from review AS rv ,user AS us  WHERE rv.member_num=us.member_num and us.member_num='$_SESSION[userNum]' and $tv_movie='$id'");


        while ($row = $query3->fetch()) {
            $ress=$db->query("select count(*) from good where id=$id and good_yes=$row[member_num] and member_num= $_SESSION[userNum]")->fetchColumn();

        $content = str_replace(" ", "&nbsp;", $row['review']);
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
$query3 = $db->query("select *,DATE(review_date)as review_date  from review AS rv ,user AS us  WHERE rv.member_num=us.member_num and $tv_movie='$id' order by $pougodd desc LIMIT $stat , $numLines");
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

	$numRecords = $db->query("select count(*) from  review  where movie_id='$id' or tv_id='$id'  ")->fetchColumn();
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