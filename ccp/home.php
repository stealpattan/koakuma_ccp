<?php
session_start();
date_default_timezone_set("Asia/Tokyo");
require('dbconnect.php');
require('function.php');
$record = mysqli_query($db, 'SELECT * FROM news ORDER BY id DESC LIMIT 5');
require('calendar.php');
if(empty($_GET['calendar']) && !isset($_GET['calendar'])){
  $year = (int)date('Y');
  $month = (int)date('m');
}

// 以下カレンダー表示に必要な部分
if(!empty($_GET['calendar']) && isset($_GET['calendar'])){
  $year = (int)date('Y');
  $month = (int)date('m') - (int)$_GET['calendar'];
  while($month <= 0){
    $month = 12 + $month;
    $year = $year - 1;
  }
  while($month >= 13){
    $month = 0 + ($month - 12);
    $year = $year + 1;
  }
}
$calendar =  calendar($year, $month);
$sql = sprintf('SELECT id,day,title,event_type FROM news WHERE year="%s" AND month="%s" AND event_type != "報告書"',$year,$month);
$record2 = mysqli_query($db,$sql) or die(mysqli_error($db));
$table = array();
while($rec = mysqli_fetch_assoc($record2)){
  $table[] = $rec;
}
$_SESSION['cal_event'] = $table;
$sql = "SELECT * FROM `sirumoku_data` WHERE date > NOW()";
$record2 = mysqli_query($db,$sql) or die(mysqli_error($db));
$table = array();
while($rec = mysqli_fetch_assoc($record2)){
  $dd = explode("-",$rec['date']);
  if($dd[0] == $year){
    if($dd[1] == $month){
      $table[] = $rec;
    }
  }
}
$_SESSION['siru_event'] = $table;
//以上

//シルモクのデータを取得する部分
$sql = sprintf('SELECT * FROM `sirumoku_data` WHERE date>=NOW()');
$record2 = mysqli_query($db,$sql) or die(mysqli_error($db));
$sirumoku_table = array();
while($rec = mysqli_fetch_assoc($record2)){
  $sirumoku_table[] = $rec;
}

//シルモクの締め切り日を超えていないか確認するメソッド
function check_limit($str){
  $ex_str = explode("-",$str);
  if((int)$ex_str[0] < (int)date('Y')){
    return true;
  }
  else if((int)$ex_str[1] < (int)date('m')){
    return true;
  }
  else if((int)$ex_str[2] < (int)date("d")){
    return true;
  }
  else{
    return false;
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Top</title>
    <link rel="shortcut icon" href="img/logo/tpu_logo.png">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/sirumoku.css">
    <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
  </head>
  <body>
    <?php require("header.php"); ?>
    <div class="contents">
      <div class="top" style='width:70%;margin:0 auto;margin-bottom:20px;'>
        <img src="img/pic/tpu-image.jpg" alt="">
      </div>
      <div class='jump_tab'>
        <ul>
          <a href="#information"><div class='jump_button'style='font-size:100%;'>新着情報</div></a>
          <a href="#calendar"><div class='jump_button'style='font-size:100%;'>スケジュール</div></a>
          <a href="#sirumoku"><div class='jump_button'style='font-size:100%;'>シルモク</div></a>
        </ul>
      </div>
      <div id="information">
        <div class="center">
          <p class="contentsTitle">新着情報</p>
          <p class="b_contentsTitle">News</p>
        </div>
        <div class="info-content">
          <?php
          while ($table = mysqli_fetch_assoc($record)) {
          ?>
            <div class="info-topic">
              <div class="info-date"><p><?php echo $table['year'], "/", $table['month'], "/", $table['day']; ?></p></div>
              <div class="info-title"><p><?php echo htmlspecialchars($table['title']); ?></p></div>
            </div>
            <div class="info-tags">
              <div class="info-tag"><p><?php echo htmlspecialchars($table['target']); ?></p></div>
              <div class="info-tag"><p><?php echo htmlspecialchars($table['event_type']); ?></p></div>
            </div>
          <?php
          }
          ?>
        </div>
        <div class="allnews">
          <span><a href="allnews.php">新着情報一覧</a></span>
        </div>
      </div>
      <div id="calendar">
        <div class="center">
          <button class='cal_button' onclick='calendar_change(<?php
                                              if(!empty($_GET["calendar"]) && isset($_GET["calendar"])){
                                                echo $_GET["calendar"] + 1;
                                              }
                                              else{
                                                echo 1;
                                              }
                                          ?>)'><</button>
          <p class="contentsTitle">スケジュール</p>
          <button class='cal_button' onclick='calendar_change(<?php
                                              if(!empty($_GET["calendar"]) && isset($_GET["calendar"])){
                                                echo $_GET["calendar"] - 1;
                                              }
                                              else{
                                                echo -1;
                                              }
                                          ?>)'>></button>
          <p><?php echo $year; ?>年<?php echo $month; ?>月</p>
          <p class="b_contentsTitle">Schedule</p>
        </div>
        <table>
          <tr>
            <th style='color:red;'>日</th>
            <th>月</th>
            <th>火</th>
            <th>水</th>
            <th>木</th>
            <th>金</th>
            <th style='color:blue;'>土</th>
          </tr>
          <?php for($i=0;$i<count($calendar);$i++): ?>
            <tr>
              <?php for($j=0;$j<7;$j++): ?>
                <td class='calendar_content'>
                  <?php
                    if($i >= count($calendar)){
                      break;
                    }
                    echo $calendar[$i]['day'];
                    echo "<br>";
                    echo "<br>";
                    foreach($_SESSION['cal_event'] as $cal_event){
                      if($cal_event['day'] == $calendar[$i]['day']){
                        $str = sprintf("<a href='event_detail.php?id=%s'><div style='padding-bottom:3px;color:blue;'>%s</div></a>",
                                  $cal_event['id'],
                                  $cal_event['title']);
                        echo $str;
                      }
                    }
                    foreach($_SESSION['siru_event'] as $siru_event){
                      $dd = explode('-',$siru_event['date']);
                      if($dd[2] == $calendar[$i]['day']){
                        $str = sprintf("<a href='#sirumoku'><div style='padding-bottom:3px;color:blue;'>シルモク開催日</div></a>");
                        echo $str;
                      }
                    }
                    if($j < 6){
                      $i++;
                    }
                  ?>
                </td>
              <?php endfor; ?>
            </tr>
          <?php endfor; ?>
        </table>
      </div>
      <script type="text/javascript">
        function calendar_change(num){
          if(num == 0){
            var str = 'home.php';
          }
          else{
            var str = "home.php?calendar=" + num;
          }
          document.location = str;
        }
      </script>
      <!-- 以下シルモク -->
      <?php $day_what = array('Sun','Mon','Tue','Wed','Thr','Fri','Sut'); ?>
      <div id="sirumoku">
        <div class="center">
          <p class="contentsTitle">シルモク</p>
          <p class="b_contentsTitle">企業を知る木曜日</p>
        </div>
        <div class='sirumoku_introduction' style='width:70%;margin:0 auto;padding-bottom:20px;'>
          <div class="sirumoku-image" style='width:50%;float:left;margin-right:10px;margin-bottom:10px'>
            <img src="img/pic/sirumoku.jpg" alt=""/>
          </div>
          <div class="" style='text-align:left;'>
            <p>県内企業が自社の魅力・実力を学生に紹介する</p>
            <p>学内企業説明会</p>
            <p>富山県に関係のある企業の方にお越しいただき</p>
            <p>業務内容や自社製品について紹介していただきます。</p>
            <p class="sub">シルモクの受付はキャリアカフェで行なっています。</p>
          </div>
        </div>
        <div class="sirumoku_datas">
          <table class="table table-bordered table-striped trhover">
            <tr class="s_data_list">
              <th class="s_data_day" style='color:white;'>開催日</th>
              <th class="s_data_time" style='color:white;'>時間帯</th>
              <th class="s_data_place" style='color:white;'>開催場所</th>
              <th class="s_data_name" style='color:white;'>企業名</th>
              <th class="s_data_name" style='color:white;'>推薦学科</th>
            </tr>
            <?php foreach($sirumoku_table as $st): ?>
              <tr style='text-align:center;'>
                <?php 
                  $d = explode("-" , $st['date']);
                  $d = (int)$d[0] . "年" . (int)$d[1] . "月" . (int)$d[2] . "日" . "(" .date('l', mktime(0,0,0,(int)$d[0],(int)$d[1],(int)$d[2])) . ")";
                ?>
                <td>
                  <?php 
                    echo $d;  
                    if(check_limit($st['apply_limit']) == true){
                      echo "<br>" . "<span style='color:red;font-size:10px;'>" . "受付は終了しました" . "</span>";
                    }
                  ?>
                </td>
                <td><?php echo $st['start-time'] . "〜" . $st['finish-time']; ?></td>
                <td><?php echo $st['place']; ?></td>
                <td><?php echo $st['name_company']; ?></td>
                <td><?php echo $st['recommend']; ?></td>
              </tr>
            <?php endforeach; ?>


          </table>
        </div>
      </div>
    </div>
    <?php require('top_of_career_center.php'); ?>
    <?php include('footer.php'); ?>
  </body>
</html>
