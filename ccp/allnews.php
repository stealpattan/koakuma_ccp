<?php
require('dbconnect.php');

$record = mysqli_query($db, 'SELECT * FROM news ORDER BY id DESC');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>新着情報一覧</title>
    <link rel="shortcut icon" href="./assets/img/logo/tpu_logo.png">
    <link rel="stylesheet" href="./assets/css/reset.css">
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/common.css">
    <link rel="stylesheet" href="./assets/css/home.css">
  </head>
  <body id="news">
    <?php include_once("analyticstracking.php") ?>
    <?php require("header.php"); ?>
    <div class="contents">
      <div id="information">
        <div class="breadcrumbs">
          <ul>
            <li><a href="home.php">Home</a></li>
            <li class="here">新着情報一覧</li>
          </ul>
        </div>
        <div class="title">
          <p>新着情報</p>
        </div>
        <div class="info-content">
          <?php
          while ($table = mysqli_fetch_assoc($record)) {
          ?>
            <div class="info-topic">
              <div class="info-date"><p><?php echo $table['year']. "/". $table['month']. "/". $table['day']; ?></p></div>
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
      </div>
    </div>
    <?php include('footer.php'); ?>
  </body>
</html>
