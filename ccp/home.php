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
  else if((int)$ex_str[1] == (int)date('m') && (int)$ex_str[2] < (int)date("d")){
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
    <link rel="shortcut icon" href="./assets/img/logo/tpu_logo.png">
    <link rel="stylesheet" href="./assets/css/reset.css">
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/common.css">
    <link rel="stylesheet" href="./assets/css/home.css">
    <link rel="stylesheet" href="./assets/css/sirumoku.css">
    <script type="text/javascript" src="./assets/js/jquery-3.1.1.min.js"></script>
  </head>
  <body>
    <?php include_once("analyticstracking.php") ?>
    <?php require("header.php"); ?>
    <div class="contents">
      <div class="top_image">
        <img src="./assets/img/pic/tpu-image.jpg" alt="">
      </div>
      <div class='jump_tab'>
        <ul>
          <li><a class="jump_button" href="#information">新着情報</a></li>
          <li><a class="jump_button" href="#calendar">スケジュール</a></li>
          <li><a class="jump_button" href="#sirumoku">シルモク</a></li>
        </ul>
      </div>
      <div id="information">
        <div class="title">
          <p>新着情報</p>
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
        <div class="title">
          <p>スケジュール</p>
        </div>
        <div class="center">
          <button class='cal_button' onclick='calendar_change(<?php
                                              if(!empty($_GET["calendar"]) && isset($_GET["calendar"])){
                                                echo $_GET["calendar"] + 1;
                                              }
                                              else{
                                                echo 1;
                                              }
                                          ?>)'><</button>
          <p><?php echo $year; ?>年<?php echo $month; ?>月</p>
          <button class='cal_button' onclick='calendar_change(<?php
                                              if(!empty($_GET["calendar"]) && isset($_GET["calendar"])){
                                                echo $_GET["calendar"] - 1;
                                              }
                                              else{
                                                echo -1;
                                              }
                                          ?>)'>></button>
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
                        $rep_str = mb_substr($cal_event['title'], 5);
                        $str = sprintf("<a href='event_detail.php?id=%s' title='%s'><div style='padding-bottom:3px;color:blue;'>%s</div></a>",
                                  $cal_event['id'],
                                  $cal_event['title'],
                                  str_replace($rep_str, "...", $cal_event['title']));
                        echo $str;
                      }
                    }
                    foreach($_SESSION['siru_event'] as $siru_event){
                      $dd = explode('-',$siru_event['date']);
                      if($dd[2] == $calendar[$i]['day']){
                        $str = sprintf("<a href='#sirumoku' title='シルモク開催日'><div style='padding-bottom:3px;color:blue;'>%s</div></a>", str_replace(mb_substr("シルモク開催日", 5), "...", "シルモク開催日"));
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
      <div id="sirumoku">
        <div class="title">
          <p>シルモク</p>
        </div>
        <div class="sirumoku_introduction">
          <div class="sirumoku-image">
            <img src="./assets/img/pic/sirumoku.jpg" alt=""/>
          </div>
          <div class="introduce_sirumoku">
            <p>県内企業が自社の魅力・実力を学生に紹介する</p>
            <p>学内企業説明会</p>
            <p>富山県に関係のある企業の方にお越しいただき、</p>
            <p>業務内容や自社製品について紹介していただきます。</p>
            <p>シルモクの受付はキャリアカフェで行なっています。</p>
          </div>
        </div>
        <div class="sirumoku_datas">
          <table class="table table-bordered table-striped trhover">
            <tr class="s_data_list">
              <th class="s_data_day">開催日</th>
              <th class="s_data_time">時間帯</th>
              <th class="s_data_place">開催場所</th>
              <th class="s_data_name">企業名</th>
              <th class="s_data_major">推薦学科</th>
            </tr>
            <?php foreach($sirumoku_table as $st): ?>
              <tr style='text-align:center;'>
                <?php
                  $jp_day = array("Monday" => '月', "Tuesday" => "火", "Wednesday" => "水", "Thursday" => "木", "Friday" => "金");
                  $d = explode("-" , $st['date']);
                  $d = (int)$d[0] . "年" . (int)$d[1] . "月" . (int)$d[2] . "日" . "(" . $jp_day[date('l', mktime(0,0,0,(int)$d[1],(int)$d[2],(int)$d[0]))] . ")";
                ?>
                <td>
                  <?php
                    echo $d;
                    if(check_limit($st['apply_limit']) == true){
                      echo "<br>" . "<span style='color:red;font-size:10px;'>" . "受付は終了しました" . "</span>";
                    }
                  ?>
                </td>
                <?php
                  $starttime = date('g:i',strtotime($st['start-time']));
                  $finishtime = date('g:i', strtotime($st['finish-time']));
                ?>
                <td><?php echo $starttime . "〜" . $finishtime; ?></td>
                <td><?php echo $st['place']; ?></td>
                <?php
                  $nc = str_replace(",", "<br>", $st['name_company']);
                ?>
                <td><?php echo $nc; ?></td>
                <td><?php echo $st['recommend']; ?></td>
              </tr>
            <?php endforeach; ?>
          </table>
        </div>
      </div>
    </div>
    <div style='width:70%;' class="introduce">
      <div class="introduce-image">
        <div class="introduce-image-inner">
          <img src="./assets/img/pic/introduce-image.jpg" alt="">
        </div>
      </div>
      <div class="introduce-introduction-title">
        <h1>キャリアセンター所長挨拶</h1>
        <h4>キャリアセンター長 知能デザイン工学科 教授 大島 徹</h4>
      </div>
      <p>　富山県立大学では、建学の理念と目的とする「視野の広い、人間性豊かな、創造力と実践力を兼ね備えた、地域及び社会に有為な人材」を育み、輩出するため、教職員が一丸となって学生および院生の指導に取り組んでいます。</p>
      <p>　キャリアセンターでは、特に、学生一人ひとりが、特性、適性を見出し、自分自身の適性や能力を理解しながら自分の生き方を考えるために必要な能力を身につけられる様、支援しています。キャリアセンターの具体的な主な支援事業として、</p>
      <p>1.　キャリア形成のために「キャリア形成科目（8科目）」を開設し、入学から卒業までの一貫したキャリア形成教育を行っています。</p>
      <p>2.　地域就職アドバイザーを配し、主に学部３年次生を対象としたインターンシップを正課として実施を通じ、適切な職業観の育成を支援しています。</p>
      <p>3.　就職、進学等、学生への支援のため、キャリアアドバイザーを配し、個別相談・面接対策セミナー等による、きめ細かい進路指導・助言を行っています。</p>
      <p>4.　学生の進路支援事業の情報提供やガイダンスとして、進路ガイダンス、模擬面接（集団・個人）及び面接指導研修会、企業との意見交換会、合同企業説明会、企業情報の提供、
                個別の就職指導、就職斡旋、面接指導、SPI試験対策講習を開催しています。また、シルモク（企業を知る木曜日）を開催し、企業および行政機関等からの求人・大学院入学情報を提供し、進路選択の支援を行っています。</p>
      <p>5.　学生の進路に係る調査および分析を行い、各種資料の電子化・データ化により経年的比較や分析データの提供を可能としています。</p>
      <p>　これらの組織的、体系的なキャリア形成支援により本学は、全国トップクラスの就職率を背景にした「就職に強い大学」としての評価を得るに至っています。上記趣旨をご理解いただき、本学の「キャリアセンター」を有効にご活用くださいますようお願いします。</p>
    </div>
    <?php include('footer.php'); ?>
  </body>
</html>
