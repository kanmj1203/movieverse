$( document ).ready(function(){
    // 스크롤 시 category fade-in
    $(document).on('scroll', function(){
        if($(window).scrollTop() > 100){
            $(".category_container").removeClass("category_scroll_top");
            $(".category_container").addClass("category_scroll_down");
        }else{
            $(".category_container").removeClass("category_scroll_down");
            $(".category_container").addClass("category_scroll_top");
        }
    });
});