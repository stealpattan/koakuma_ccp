<?php
session_start();
date_default_timezone_set("Asia/Tokyo");
require('dbconnect.php');
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
$sql = sprintf('SELECT id,day,title,event_kind FROM news WHERE year="%s" AND month="%s"',$year,$month);
$record2 = mysqli_query($db,$sql) or die(mysqli_error($db));
$table = array();
while($rec = mysqli_fetch_assoc($record2)){
  $table[] = $rec;
}
$_SESSION['cal_event'] = $table;
//以上

$sql_date=sprintf('SELECT * FROM `sirumoku_data` WHERE 1');
$record_date=mysqli_query($db,$sql_date);
$deadline=date('Y-m-d', strtotime("+3 day"));
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
      <div class="top">
        <img src="img/pic/tpu-image.jpg" alt="">
      </div>
      <?php require('top_of_career_center.php'); ?>
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
              <div class="info-tag"><p><?php echo htmlspecialchars($table['event_kind']); ?></p></div>
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
                        echo "<a style='text-decoration:none;' href=''>";
                        echo $cal_event['title'];
                        echo "</a>";
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
      <script src="js/calendar-slide.js"></script>
      <div id="sirumoku">
        <div class="center">
          <p class="contentsTitle">シルモク</p>
          <p class="b_contentsTitle">企業を知る木曜日</p>
        </div>
        <div class="image">
          <div class="sirumoku-image">
            <img src="img/pic/sirumoku.jpg" alt="" />
          </div>
          <div class="tab_link">
            <div class="center">
              <a href="sirumoku-subscription.php">
                <span class="tab_link_inside">申し込みはこちら</span>
              </a>
            </div>
          </div>
        </div>
        <div class="intro">
          <p>
            県内企業が自社の魅力・実力を学生に紹介する
          </p>
          <p>
            学内企業説明会
          </p>
          <p class="intro_margin">
            富山県に関係のある企業の方にお越しいただき
          </p>
          <p>
            業務内容や自社製品について紹介していただきます。
          </p>
        </div>
        <div class="sirumoku_datas">
          <table class="table table-bordered table-striped trhover">
            <tr class="s_data_list">
              <th class="s_data_day">開催日</th>
              <th class="s_data_time">時間</th>
              <th class="s_data_name">企業名</th>
            </tr>
            <?php
              while($table_date=mysqli_fetch_assoc($record_date)){
                if($table_date['date'] > $deadline){
                  //開催日
                  $array = explode("-", $table_date['date']);
                  $str1 = str_split($array[1]);
                  $str2 = str_split($array[2]);
                  if($str1[0] == 0){
                    $str1[0] = '';
                  }
                  if($str2[0] == 0){
                    $str2[0] = '';
                  }
                  $str1=$str1[0].$str1[1];
                  $str2=$str2[0].$str2[1];
                  $date_time=$array[0]."/".$str1."/".$str2;

                  //開始時間
                  $table_st_data=$table_date['start-time'];
                  $array = explode(":", $table_st_data);
                  $data_start=$array[0].":".$array[1];

                  //終了時間
                  $table_ft_data=$table_date['finish-time'];
                  $array = explode(":", $table_ft_data);
                  $data_finish=$array[0].":".$array[1];

                  //会社名
                  $table_company_data=$table_date['name_company'];
                  $array = explode(",", $table_company_data);
            ?>
            <tr>
              <th class="table_data_date"><?php echo htmlspecialchars($date_time); ?></th>
              <th class="table_data_time"><?php echo htmlspecialchars($data_start.' ~ '.$data_finish); ?></th>
              <th><?php echo htmlspecialchars($array[0])."<br>".htmlspecialchars($array[1]); ?></th>
            </tr>
            <?php
              }
            }
            ?>
          </table>
        </div>
      </div>
    </div>
    <?php include('footer.php'); ?>
  </body>
</html>
