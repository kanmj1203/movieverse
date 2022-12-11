<?php
 
$search = isset($_REQUEST["search"]) ? $_REQUEST["search"] : "";

//  검색어 삭제 눌렀을 경우
if (isset($_REQUEST["cookie_del"])) {
    setcookie('search_cookie['.$_REQUEST["cookie_del"].']', '', 0, '/');
}

// 검색어 있다면
if ($search != "") {
    if(isset($_COOKIE["search_cookie"])) {
        // $search_cookie=$_COOKIE["search_cookie"]; // 쿠키 불러오기
            setcookie('search_cookie['.$search.']', $search, time() + 86400, '/');
        // } else {
        //     // array_pop($_COOKIE["search_cookie"]);
        //     setcookie('search_cookie['.$search.']', $search, time() + 86400, '/');
        // }
    } else {
        if ($search) {
            setcookie('search_cookie['.$search.']', $search, time() + 86400, '/');
        }
    }


    echo "
    <script>
		location.href = 'search_result.php?search=$search';
    </script>
    ";
} else {
// 검색어 없다면
    echo "
    <script>
         history.back();
    </script>
    ";
}

?>
