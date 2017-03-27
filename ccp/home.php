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
$sql = sprintf('SELECT id,day,title,event_type FROM news WHERE year="%s" AND month="%s"',$year,$month);
$record2 = mysqli_query($db,$sql) or die(mysqli_error($db));
$table = array();
while($rec = mysqli_fetch_assoc($record2)){
  $table[] = $rec;
}
$_SESSION['cal_event'] = $table;
//以上

$date_y = date('Y');
$date_m = date('m');

if($date_m >= '03' && $date_m < '09'){
  $sql_date = sprintf('SELECT * FROM `sirumoku_data` WHERE sirumoku_data.date >= "%d-03-01" AND sirumoku_data.date < "%d-09-01"', $date_y, $date_y);
  $record_date = mysqli_query($db, $sql_date);
  while($table = mysqli_fetch_assoc($record_date)){
    $datas[] = $table;
    foreach ($datas as $key => $value) {
      $date[$key] = $value['date'];
    }
    // array_multisortで'id'の列を昇順に並び替える
    array_multisort($date, SORT_ASC, $datas);
  }
}else{
  $sql_date = sprintf('SELECT * FROM `sirumoku_data` WHERE sirumoku_data.date >= "%d-09-01" AND sirumoku_data.date < "%d-03-01"', $date_y, $date_y+1);
  $record_date = mysqli_query($db, $sql_date);
  while($table = mysqli_fetch_assoc($record_date)){
    $datas[] = $table;
    foreach ($datas as $key => $value) {
      $date[$key] = $value['date'];
    }
    // array_multisortで'id'の列を昇順に並び替える
    array_multisort($date, SORT_ASC, $datas);
  }
}
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
                        echo "<a style='text-decoration:none;' href='allnews.php'>";
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
        </div>
        <div class="intro">
          <p>県内企業が自社の魅力・実力を学生に紹介する</p>
          <p>学内企業説明会</p>
          <p>富山県に関係のある企業の方にお越しいただき</p>
          <p>業務内容や自社製品について紹介していただきます。</p>
          <p class="sub">シルモクの受付はキャリアカフェで行なっています。</p>
        </div>
        <div class="sirumoku_datas">
          <table class="table table-bordered table-striped trhover">
            <tr class="s_data_list">
              <th class="s_data_day">開催日</th>
              <th class="s_data_time">時間帯</th>
              <th class="s_data_place">開催場所</th>
              <th class="s_data_name">企業名</th>
            </tr>
            <?php
            foreach($datas as $data):
              //開催日
              $array = explode("-", $data['date']);
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
              $table_st_data=$data['start-time'];
              $array = explode(":", $table_st_data);
              $data_start=$array[0].":".$array[1];
              //終了時間
              $table_ft_data=$data['finish-time'];
              $array = explode(":", $table_ft_data);
              $data_finish=$array[0].":".$array[1];
              //会社名
              $table_company_data=$data['name_company'];
              $array = explode(",", $table_company_data);
              //sirumoku_entryの各受付数を取得
              // $sql_entry=sprintf('SELECT COUNT(`event_date`) AS cnt FROM `sirumoku_entry` WHERE event_date = "%s"', $data['date']);
              // $record_entry=mysqli_query($db,$sql_entry);
              // $entry_number = mysqli_fetch_assoc($record_entry);
              // $cnt = $entry_number["cnt"];
              // $remain = $data['number_people'] - $cnt;
              $errors['entry'] = '';
              if($data['date'] < $deadline){
                $errors['entry'] = 'deadline';
              }
              // elseif($cnt == $data['number_people']){
              //   $errors['entry'] = 'over';
              // }elseif($remain <= 5){
              //   $errors['entry'] = 'warning';
              // }
              ?>
              <tr>
                <?php if(!empty($errors['entry'])): ?>
                  <th class="table_data_date"><p style="padding-top:8px;"><?php echo htmlspecialchars($date_time); ?></p></th>
                  <th class="table_data_time"><p style="padding-top:8px;"><?php echo htmlspecialchars($data_start.' ~ '.$data_finish); ?></p></th>
                  <th class="table_data_place"><p style="padding-top:8px;"><?php echo htmlspecialchars($data['place']) ?></p></th>
                <?php else: ?>
                  <th class="table_data_date"><p><?php echo htmlspecialchars($date_time); ?></p></th>
                  <th class="table_data_time"><p><?php echo htmlspecialchars($data_start.' ~ '.$data_finish); ?></p></th>
                  <th class="table_data_place"><p><?php echo htmlspecialchars($data['place']) ?></p></th>
                <?php endif; ?>
                <th>
                  <p style="margin:0; font-size:10px;"><?php echo  htmlspecialchars($data['recommend']); ?></p>
                  <?php echo htmlspecialchars($array[0])."<br>".htmlspecialchars($array[1]); ?>
                  <?php if (isset($errors['entry']) && $errors['entry'] == 'deadline' ) : ?>
                    <p class="error" style="color: red; font-size: 10px; margin: 0;">受付を終了しました</p>
                  <!-- <?php //elseif (isset($errors['entry']) && $errors['entry'] == 'over' ) : ?>
                    <p class="error" style="color: red; font-size: 10px; margin: 0;">定員に達しました</p>
                  <?php //elseif (isset($errors['entry']) && $errors['entry'] == 'warning') : ?>
                    <p class="error" style="color: red; font-size: 10px; margin: 0;">残り<?php echo $remain; ?>名で定員に達します</p> -->
                  <?php endif; ?>
                </th>
              </tr>
            <?php endforeach; ?>
          </table>
        </div>
      </div>
    </div>
    <?php include('footer.php'); ?>
  </body>
</html>
