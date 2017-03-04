<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>シルモク参加申込</title>
    <link rel="shortcut icon" href="img/logo/tpu_logo.png">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/common.css">
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
      <h1>Coming Soon...</h1>
    </div>
    <?php incl<?php
  require('dbconnect.php');

  $data=$_POST['date'];
  $student_name_data=$_POST['name'];
  $student_number_data=$_POST['student_number'];

  $sql_data=sprintf('SELECT * FROM `sirumoku_data` WHERE sirumoku_data.date="%s"', $data);
  $record_data=mysqli_query($db,$sql_data);
  $table_data=mysqli_fetch_assoc($record_data);

  $sql_student_data=sprintf('SELECT * FROM sirumoku_student_data, prefectures WHERE sirumoku_student_data.prefecture_id = prefectures.prefecture_id AND sirumoku_student_data.student_number="%d"', $student_number_data);
  $record_student_data=mysqli_query($db,$sql_student_data);
  $table_student_data=mysqli_fetch_assoc($record_student_data);

  $date = date('Y-m-d');
  $deadline=date('Y-m-d', strtotime("+3 day"));
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>シルモク参加申込</title>
    <link rel="shortcut icon" href="img/logo/tpu_logo.png">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/progress_bar.css">
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
      <div class="progress check">
        <div class="one one-success-color"></div><div class="two two-success-color"></div><div class="three three-success-color"></div>
  			<div class="progress-bar progress-bar-success" style="width: 50%"></div>
		  </div>
      <?php

      $table_date_data=htmlspecialchars($table_data['date']);
      $array = explode("-", $table_date_data);
      $str1 = str_split($array[1]);
      $str2 = str_split($array[2]);
      for ($i=0; $i < 2; $i++) {
        if($str1[$i] == 0){
          $str1[$i] = '';
        }
        if($str2[$i] == 0){
          $str2[$i] = '';
        }
        if($i==1){
          $str1=$str1[$i-1].$str1[$i];
          $str2=$str2[$i-1].$str2[$i];
          echo $array[0]."/".$str1."/".$str2."<br>";
        }
      }
      //echo htmlspecialchars($table_data['date']);
      $table_st_data=htmlspecialchars($table_data['start-time']);
      $array = explode(":", $table_st_data);
      echo $array[0].":".$array[1]."<br>";
      // echo htmlspecialchars($table_data['start-time']);
      $table_ft_data=htmlspecialchars($table_data['finish-time']);
      $array = explode(":", $table_ft_data);
      echo $array[0].":".$array[1]."<br>";
      // echo htmlspecialchars($table_data['finish-time']);
      echo htmlspecialchars($table_data['place'])."<br>";
      $table_company_data=htmlspecialchars($table_data['name_company']);
      $array = explode(",", $table_company_data);
      echo $array[0]." ".$array[1]."<br>";

      echo htmlspecialchars($table_student_data['student_name'])."<br>";
      echo htmlspecialchars($table_student_data['student_number'])."<br>";
      echo htmlspecialchars($table_student_data['sex'])."<br>";
      echo htmlspecialchars($table_student_data['mail'])."<br>";
      echo htmlspecialchars($table_student_data['prefecture_name'])."<br>";
      ?>

    </div>
    <?php include('footer.php'); ?>
  </body>
</html>
ude('footer.php'); ?>
  </body>
</html>
