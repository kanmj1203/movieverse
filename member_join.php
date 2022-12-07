<?php

    require("db_connect.php");
    session_start();
    $_SESSION["userId"] = empty($_SESSION["userId"]) ? "" : $_SESSION["userId"];
    
    // $query3 = $db->query("SELECT title FROM tv UNION SELECT title  FROM movie "); 
    // while ($row = $query3->fetch()) {
        // <?=$row['title'];
?>
<script> // 영화 제목 리스트 추가 (자동완성 리스트)

    List = [];  // 배열 생성

    // List.push('');
    
</script>
<script>

    $(function() {
        $("#searchInput").autocomplete({
            source: List,
            focus: function(event, ui) {
                return false;
            },
            minLength: 1,
            delay: 100,


        });
    });
</script>
<script>
    function re_check_name() {
        $.ajax({
            url: "rechecke_name_ajax.php",
            type: "post",
            data: {
                name: $('#lo2').val(),
            }
        }).done(function(data) {
            $('#result2').text(data);
        });
    }
</script>
<script>
    function re_check_email() {
        $.ajax({
            url: "rechecke_mail_ajax.php",
            type: "post",
            data: {
                email: $('#lo1').val(),
            }
        }).done(function(data) {
            $('#result').text(data);
        });
    }
</script>
<script>
var new_pw,ps_ok;
$(document).ready(function(e) { 

$(".member_join_pw").on("keyup", function(){ //check라는 클래스에 입력을 감지
		var self = $(this); 
		
		if(self.attr("id") === "pw1"){ 
			new_pw = self.val(); 
		} 
		
		if(self.attr("id") === "pw2"){ 
			ps_ok = self.val(); 

		if(new_pw==ps_ok){
			ps_ok='ok';
		}else{
			ps_ok='no';
		}

		
			$.post( //post방식으로 id_check.php에 입력한 userid값을 넘깁니다
			"pw_check_ajax.php",
			{ ps_ok : ps_ok }, 
			function(data){ 
				if(data){ //만약 data값이 전송되면
					self.parent().parent().find("#pw_check").html(data); //div태그를 찾아 html방식으로 data를 뿌려줍니다.
					self.parent().parent().find("#pw_check").css("color", "#F00"); //div 태그를 찾아 css효과로 빨간색을 설정합니다
				}
			}
		);
}
	});


});
</script>

<?php

$method = "GET";
$api_key = '13e4eba426cd07a638195e968ac8cf19';

// 영화 데이터
$data = array( 
    // 플랫폼 가져오기
    array(
        'api_key' => $api_key
    )
);

// URL 지정
$base_url = 'https://api.themoviedb.org/3';

$url = array(
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
   
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>MovieVerse</title>
    <link rel="shortcut icon" href="./img/logo/logo_text_x.png">

    <!--css 링크-->
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <link rel="stylesheet" type="text/css" href="css/basic.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> <!--자동완성 기능 autocomplete-->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


</head>

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
                        placeholder="찾으시려는 드라마 또는 영화 제목을 입력해 주세요."
                         onfocus="this.placeholder=''" 
                         onblur="this.placeholder='찾으시려는 드라마 또는 영화 제목을 입력해 주세요.'"
                        size="70" required="required"/>
                        <input class="search_Img" name="button" type="image" src="img/search_img.png" />
                    </form>
            </div>
        </div>
        <!-- search_modal END -->

        <div id='wrap' class="main_container">
            <!-- wrap -->
            <div><img class="logo_text"src="./img/logo/logo_txt.png"><div>
            <div class="login_form_container">  
                <div class="join_form_wrap">
                    <!-- user 아이콘 -->
                    <div class="login_user_icon_wrap">
                        <div class="login_user_icon">
                            <img src="./img/login/userWhite.png" alt="user_icon_white">
                        </div>
                    </div>
                    <!-- 입력칸 -->

                        <form class="join_container" method="post" action="member_join_in.php" name="memform">
                            <div class="memberjoin_tle">
                                <div class="memberjoin">

                                    <p id="login_text1">회원가입</p>
                                </div>
                                <input style="margin-top: 146px; width: 263px;	" class="member_join_text" id="lo1" type="text" name="id" placeholder="아이디 입력">
                                <div onclick="re_check_email()"style="cursor: pointer;" class="re_check">중복확인</div>
                                <p id="result"></p>

                                <input style="margin-top: 224px;" class="member_join_pw" type="password" id="pw1"name="pw" placeholder="비밀번호 입력">
                                <input style="margin-top: 312px;" class="member_join_pw" type="password" id="pw2"name="pw" placeholder="비밀번호 확인">
                            <p class="pwrepl"id="pw_check"style="">비밀번호 중복확인</p>
                            <input style="margin-top: 410px;width: 263px;" class="member_join_text" id="lo2" type="text" name="name" placeholder="닉네임">
                            
                            <div onclick="re_check_name()" style="cursor: pointer;margin-top: 284px;" class="re_check">중복확인</div>
                                <p style="top:497px" id="result2"></p>

                                <input style="margin-top: 24px;" type="submit" class="memberjoin_btn" value="회원가입" />
                            </div>
                        </form>
                </div>
                </div>
                </div>
            </div>
        </div><!-- wrap END -->
        
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

