/**
 * Created by df on 2017/2/23.
 */

//dom加载完成后执行的js
;$(function() {

    //全选的实现
    $("#preview").click(function () {
        var param = {};
        if (window.localStorage) {
            localStorage.setItem('title', $('#submitform #title').val());
            localStorage.setItem('content', $('#submitform #content').val());
            localStorage.setItem('hot_img', $('#submitform #hot_img').val());
            localStorage.setItem('hot_link', $('#submitform #hot_link').val());
            localStorage.setItem('title_1', $('#submitform #title_1').val());
            localStorage.setItem('img_1', $('#submitform #img_1').val());
            localStorage.setItem('link_1', $('#submitform #link_1').val());
            localStorage.setItem('title_2', $('#submitform #title_2').val());
            localStorage.setItem('img_2', $('#submitform #img_2').val());
            localStorage.setItem('link_2', $('#submitform #link_2').val());
            localStorage.setItem('title_3', $('#submitform #title_3').val());
            localStorage.setItem('img_3', $('#submitform #img_3').val());
            localStorage.setItem('link_3', $('#submitform #link_3').val());
            localStorage.setItem('title_4', $('#submitform #title_4').val());
            localStorage.setItem('img_4', $('#submitform #img_4').val());
            localStorage.setItem('link_4', $('#submitform #link_4').val());
            localStorage.setItem('title_5', $('#submitform #title_5').val());
            localStorage.setItem('img_5', $('#submitform #img_5').val());
            localStorage.setItem('link_5', $('#submitform #link_5').val());
            localStorage.setItem('title_6', $('#submitform #title_6').val());
            localStorage.setItem('img_6', $('#submitform #img_6').val());
            localStorage.setItem('link_6', $('#submitform #link_6').val());


            window.open("/home/email/preview.html");
        }else{
            alert('您的浏览器暂不支持预览功能，建议使用更高版本的IE、Chrome、火狐等浏览器以获取更好的产品体验效果！~.~');
        }


    });

});

