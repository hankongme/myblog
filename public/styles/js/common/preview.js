/**
 * Created by df on 2017/2/23.
 */

//dom加载完成后执行的js
;$(function() {

    $('#title').text(localStorage.getItem('title'));
    $('#content').text(localStorage.getItem('content'));
    var hot_img = localStorage.getItem('hot_img');
    $('#hot_img').css('background','url('+hot_img+') 0 0 no-repeat');
    $('#hot_link').attr('href', localStorage.getItem('hot_link'));
    $('#link_1').attr('href', localStorage.getItem('link_1'));
    $('#img_1').attr('src', localStorage.getItem('img_1'));
    $('#title_1').text(localStorage.getItem('title_1'));
    $('#link_2').attr('href', localStorage.getItem('link_2'));
    $('#img_2').attr('src', localStorage.getItem('img_2'));
    $('#title_2').text(localStorage.getItem('title_2'));
    $('#link_3').attr('href', localStorage.getItem('link_3'));
    $('#img_3').attr('src', localStorage.getItem('img_3'));
    $('#title_3').text(localStorage.getItem('title_3'));
    $('#link_4').attr('href', localStorage.getItem('link_4'));
    $('#img_4').attr('src', localStorage.getItem('img_4'));
    $('#title_4').text(localStorage.getItem('title_4'));
    $('#link_5').attr('href', localStorage.getItem('link_5'));
    $('#img_5').attr('src', localStorage.getItem('img_5'));
    $('#title_5').text(localStorage.getItem('title_5'));
    $('#link_6').attr('href', localStorage.getItem('link_6'));
    $('#img_6').attr('src', localStorage.getItem('img_6'));
    $('#title_6').text(localStorage.getItem('title_6'));

});

