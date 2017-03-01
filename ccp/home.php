<?php
require('dbconnect.php');

$record = mysqli_query($db, 'SELECT * FROM news ORDER BY id DESC LIMIT 5');
$recordSet=mysqli_query($db, 'SELECT * FROM calendar_datas');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Top</title>
    <link rel="shortcut icon" href="img/logo/tpu_logo.png">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/home.css">
    <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
  </head>
  <body>
    <header>
      <img class="logo" src="img/logo/tpu_logo_set.svg" alt="TPUのロゴ"/>
      <!-- ナビメニュー -->
      <div class="nav-menu">
        <ul id="menu">
          <li id="home"><a class="selected_tab" href="home.php">ホーム</a></li>
          <li id="info-career"><a class="unselected_tab" href="info_career.php">就職情報</a></li>
          <li id="intern"><a class="unselected_tab" href="recruitment.php">求人情報</a></li>
        </ul>
      </div>
      <div class="clear"></div>
    </header>
    <div class="contents">
      <div class="top">
        <div class="top-image">
          <div class="top-image-inner">
            <img src="img/pic/top-image.jpg" alt="">
          </div>
        </div>
        <div class="top-introduction-title">
          <h1>キャリアセンター所長挨拶</h1>
          <h4>キャリアセンター長 医薬品工学科 教授 中島 範行</h4>
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
      <div class="tabs">
        <ul>
          <li><a href="#information">新着情報</a></li>
          <li><a href="#calendar">スケジュール</a></li>
          <li><a href="#sirumoku">シルモク</a></li>
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
              <div class="info-tag"><p><?php echo htmlspecialchars($table['category']); ?></p></div>
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
          <p class="contentsTitle">スケジュール</p>
          <p class="b_contentsTitle">Schedule</p>
        </div>
        <div class="slider">
          <div class="slideSet">
            <?php
            for($x=-12;$x<13;$x++){
              $y = date('Y',strtotime(date('Y-n-1').' +'.$x.' month'));
              $m=date('n', strtotime(date('Y-n-1').' +'.$x.' month'));
              require_once('calendar.php');
            ?>
            <div class="slide">
              <h3>
              <button id="funcAdd1" type="buttun" class="btn btn-default b-left" aria-label="Left Align" name="sengetu" >
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
              </button>
              <span id="year"><?php echo $y ?>年</span><span id="month"><?php echo $m; ?>月</span>
              <button id="funcAdd2" type="buttun" class="btn btn-default b-right" aria-label="Left Align" name="raigetu" onClick="nex()">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
              </button>
              </h3>
              <table>
                <tr>
                  <th class="nitiyoubi">日</th>
                  <th>月</th>
                  <th>火</th>
                  <th>水</th>
                  <th>木</th>
                  <th>金</th>
                  <th class="doyoubi">土</th>
                </tr>
                <tr class="days">
                  <?php
                  $holidays = ssp_holiday();
                  ksort($holidays);//祝日の配列を降順にする
                  $holidays = devide_holiday_data($holidays);
                  $holidays = current_date($y,$m,$holidays);
                  $cnt = 0;
                  calendar($y,$m);
                  foreach ($_SESSION['calendar'] as $key => $value):
                  ?>
                  <td>
                    <?php
                    $cnt++;
                    $bool = false;
                    for($i=0;$i<count($holidays);$i++){
                      if($holidays[$i][2] == $value['day']){
                        echo '<span style="color:red;">' . $value['day'] . '</span>';
                        $bool = true;
                        break;
                      }
                    }
                    if($bool == false){
                      echo $value['day'];
                    }?>
                    <br>
                    <?php $recordSet=mysqli_query($db, 'SELECT * FROM calendar_datas ORDER BY id DESC');
                    while($table = mysqli_fetch_assoc($recordSet)){
                      if (htmlspecialchars($table['year']) == $y) {
                        if(htmlspecialchars($table['month']) == $m){
                          if(htmlspecialchars($table['day']) == $value['day']){
                    ?>
                    <a href="karendar_syousai.php?event=<?php echo htmlspecialchars($table['event']); ?> & detail=<?php echo htmlspecialchars($table['detail']);?>" onClick="document.kdetail.submit(); return false;">
                      <?php echo htmlspecialchars($table['event']);?>
                    </a>
                    <?php
                          }
                        }
                      }
                    }
                    ?>
                  </td>
                  <?php if ($cnt == 7): ?>
                </tr>
                <tr>
                <?php
                $cnt = 0;
                endif;
                endforeach;
                ?>
                </tr>
              </table>
            </div>
          <?php }?>
          </div>
        </div>
      </div>
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
              <a href="sirumoku-subscription.php"><span class="tab_link_inside">申し込みはこちら</span></a>
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
          <table>
            <tr class="s_data_list">
              <th class="s_data_day">開催日</th>
              <th class="s_data_time">時間</th>
              <th class="s_data_name">企業名</th>
            </tr>
          </table>
          <h1>本年度のシルモクは終了しました</h1>
        </div>
        <div class="s_past">
          <span><a href="sirumoku.php">過去のシルモクをみる</a></span>
        </div>
      </div>
    </div>
    <?php include('footer.php'); ?>
  </body>
</html>
