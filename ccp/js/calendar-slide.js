(function(){
var slideWidth = $('.slide').outerWidth(); // .slideの幅を取得して代入
var slideNum = $('.slide').length;  // .slideの数を取得して代入
var slideSetWidth = slideWidth * slideNum; // .slideの幅×数で求めた値を代入
$('.slideSet').css('width', slideSetWidth); // .slideSetのスタイルシートにwidth: slideSetWidthを指定
var slideCurrent = 12; // 現在地を示す変数
// アニメーションを実行する独自関数
var sliding = function(){

// slideCurrentが0以下だったら
if( slideCurrent < 0 ){
  slideCurrent = 0;
// slideCurrentがslideNumを超えたら
}else if( slideCurrent > slideNum - 1){ // slideCUrrent >= slideNumでも可
  slideCurrent = slideNum - 1;
}

$('.slideSet').stop().animate({
    left: slideCurrent * -slideWidth
  });

  $('.slideSet').animate({
    left: slideCurrent * -slideWidth
  });
}
// 前へボタンが押されたとき
$('.b-left').click(function(){
  slideCurrent--;
  sliding();
});
// 次へボタンが押されたとき
$('.b-right').click(function(){
  slideCurrent++;
  sliding();
});
}());
