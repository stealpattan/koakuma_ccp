<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>シルモク参加申込</title>
    <link rel="shortcut icon" href="./assets/img/logo/tpu_logo.png">
    <link rel="stylesheet" href="./assets/css/reset.css">
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/common.css">
    <link rel="stylesheet" href="./assets/css/progress_bar.css">
    <link rel="stylesheet" href="./assets/css/sirumoku.css">
    <script type="text/javascript" src="./assets/js/jquery-3.1.1.min.js"></script>
  </head>
  <body>
    <?php include_once("analyticstracking.php") ?>
    <header>
      <img class="logo" src="./assets/img/logo/tpu_logo_set.svg" alt="TPUのロゴ"/>
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
      <div class="progress thanks">
        <div class="one one-success-color"></div><div class="two two-success-color"></div><div class="three three-success-color"></div>
  			<div class="progress-bar progress-bar-success" style="width: 100%"></div>
		  </div>
      <div class="col-md-4 col-md-offset-4 content-margin">
        <div class="well">
          <p>申し込みありがとうございます。</p>
        </div>
        <div class="link-top">
          <a href="home.php" class="btn btn-default">TOPに戻る</a>
        </div>
      </div>
    </div>
    <?php include('footer.php'); ?>
  </body>
</html>
