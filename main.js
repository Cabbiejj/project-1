$(document).ready(function(){
    $('.header').height($(window).height());
    })
    
    function playVideo() {
        window.location.href = 'https://www.youtube.com/watch?v=KxiczcSsTDw';
    }

    function scrollToGetInTouch() {
        var element = document.getElementById("get-in-touch");
        if (element) {
            element.scrollIntoView({ behavior: "smooth", block: "start" });
        }
    }