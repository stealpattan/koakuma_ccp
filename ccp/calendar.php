<?php
function calendar($y,$m){
		 $year=$y;
		 $month=$m;
		 //今月の初めの曜日//
		 $start_youbi = date("N", mktime(0, 0, 0,$year,1,$month));
		 ///↑↑date(N)は1が月曜日 7が日曜日(PHP 5.1から)↑↑///

		 // 月末日を取得
		 $last_day = date('j', mktime(0, 0, 0, $month + 1, 0, $year));
		 $calendar = array();
		 $j = 0;
		 // 月末日までループ
		 for ($i = 1; $i < $last_day + 1; $i++) {
				 // 曜日を取得
				 $week = date('w', mktime(0, 0, 0, $month, $i, $year));
				 // 1日の場合
				 if ($i == 1) {
						 // 1日目の曜日までをループ
						 for ($s = 1; $s <= $week; $s++) {
								 // 前半に空文字をセット
								 $calendar[$j]['day'] = '';
								 $j++;
						 }
				 }
				 // 配列に日付をセット
				 $calendar[$j]['day'] = $i;
				 $j++;
				 // 月末日の場合
				 if ($i == $last_day) {
						 // 月末日から残りをループ
						 for ($e = 1; $e <= 6 - $week; $e++) {
								 // 後半に空文字をセット
								 $calendar[$j]['day'] = '';
								 $j++;
						 }
				 }
		 }
		 //echo "<pre>";
		 //var_dump($calendar);//データを全部表示する
		 //echo "</pre>";

		 $_SESSION['calendar'] = $calendar;
 }
function ssp_holiday(){
	$y = date('Y');
	require_once 'Date/Holidays.php';
	// インストール先のパスを指定
	$filename = '/usr/share/pear/data/Date_Holidays_Japan/lang/Japan/ja_JP.xml';
	$dh = array();
	for($i=0;$i<3;$i++){
 		$dh[$i] = &Date_Holidays::factory('Japan', $y-1+$i, 'ja_JP');
 		$dh[$i]->addTranslationFile($filename, 'ja_JP');
	}
	$holidays = array();
	for($j=0;$j<3;$j++){
		foreach ($dh[$j]->getHolidays() as $h) {
			$holidays[$h->getDate()->format('%Y-%m-%d')] = $h->getDate()->format('%Y_%m_%d');
		}
	}
	return $holidays;
}

function devide_holiday_data($hd){
	$hoge = array();
	foreach($hd as $hd){
			$hoge[]	 = explode('_', $hd);
	}
		// echo "<pre>";
		// var_dump($hoge);
		// echo "</pre>";
	return $hoge;
}

function current_date($y, $m, $hd){
	$hoe = array();
	foreach($hd as $hd){
		if($y == $hd[0]){
			if($m == $hd[1]){
				$hoe[] = $hd;
			}
		}
	}
	return $hoe;
}
?>
