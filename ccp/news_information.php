<?php
require('dbconnect.php');
/*　以下whereのidの値をhomeからくるidの値を変数として入れる*/
$id=$_REQUEST['id'];
$sql=sprintf('SELECT * FROM news WHERE id="%d"', $id);
$recordSet = mysqli_query($db, $sql);

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>新着情報</title>
    <link rel="shortcut icon" href="img/logo/tpu_logo.png">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/new_information.css">
  </head>
  <body>
    <!-- ヘッダー -->
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
      <?php while ($table = mysqli_fetch_assoc($recordSet)) {?>
      <ul class="path">
        <li><a href="home.php">ホーム</a></li>
        <li>></li>
        <li><a href="home.php">新着情報(<?php echo(htmlspecialchars($table['category']));?>)</a></li>
        <li>></li>
        <li><?php echo(htmlspecialchars($table['title']));?></li>
      </ul>
      <div class="clear"></div>
    </header>


    <!-- コンテンツ -->
    <div class="contents">
      <div class="contents-title">
        <div class="b_border center">
          <div class="ja_title">
            <p class="contentsTitle">新着情報</p>
          </div>
          <div class="en_title">
            <p class="b_contentsTitle">News</p>
          </div>
        </div>
      </div>
      <div class="content">
        <div class="content-title">
          <p><?php echo(htmlspecialchars($table['title']));?></p>
        </div>
        <div class="content-data">
          <p><?php echo(htmlspecialchars($table['target']));?></p>
          <p><?php echo(htmlspecialchars($table['year']));?>年<?php echo(htmlspecialchars($table['month']));?>月<?php echo(htmlspecialchars($table['day']));?>日</p>
        </div>
        <div class="main-content">
          <p><?php echo(htmlspecialchars($table['text']));?></p>
        </div>
      </div>
      <?php }?>
    </div>
    <!-- フッター -->
    <?php include('footer.php'); ?>
  </body>
</html>
