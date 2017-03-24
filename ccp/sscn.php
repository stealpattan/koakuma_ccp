<?php
  //申し込み情報がDBに存在しない際のページ
  session_start();
  require('dbconnect.php');

  //年月日の取得
  $today=date('Y-m-d');

  //sirumoku-subscription.phpを正しく通って来なかった場合、強制的にsirumoku-subscription.phpに遷移
  if(!isset($_SESSION['join'])){
    header('Location: sirumoku-subscription.php');
    exit();
  }

  //会員登録ボタンが押された時の処理
  if(!empty($_POST)){
    $date = $_SESSION['join']['date'];
    $name = $_SESSION['join']['name'];
    $student_number = $_SESSION['join']['student_number'];
    $sex = $_SESSION['join']['sex'];
    $pref = $_SESSION['join']['pref'];
    $opinion = $_SESSION['join']['opinion'];
    $department = $_SESSION['join']['department'];
    $email = $_SESSION['join']['email'];

    //DBに会員情報を登録
    $sql_entry = sprintf('INSERT INTO `sirumoku_entry` SET `event_date` = "%s", `application_date` = "%s", `student_number` = "%s", `opinion` = "%s"',
    mysqli_real_escape_string($db, $date),
    mysqli_real_escape_string($db, $today),
    mysqli_real_escape_string($db, $student_number),
    mysqli_real_escape_string($db, $opinion)
    );
    mysqli_query($db, $sql_entry) or die(mysqli_error($db));
    $sql_student = sprintf('INSERT INTO `student_datas` SET `student_name` = "%s",`department_id` = "%d", `student_number` = "%s", `sex` = "%s", `mail` = "%s", `prefecture_id` = "%d"',
    mysqli_real_escape_string($db, $name),
    mysqli_real_escape_string($db, $department),
    mysqli_real_escape_string($db, $student_number),
    mysqli_real_escape_string($db, $sex),
    mysqli_real_escape_string($db, $email),
    mysqli_real_escape_string($db, $pref)
    );
    mysqli_query($db, $sql_student) or die(mysqli_error($db));
    //$_SESSIONの情報を削除
    unset($_SESSION['join']);
    //thanks.phpへ遷移
    header('Location: sirumoku-thanks.php');
    exit();
  }
  //都道府県情報の取得
  $sql_locate = sprintf('SELECT * FROM `prefectures` WHERE `prefecture_id` = "%d"', $_SESSION['join']['pref']);
  $record_locate = mysqli_query($db, $sql_locate);
  $locate = mysqli_fetch_assoc($record_locate);

  //シルモク開催情報の取得
  $sql_sirumoku = sprintf('SELECT * FROM `sirumoku_data` WHERE `date` = "%s"', $_SESSION['join']['date']);
  $record_sirumoku = mysqli_query($db, $sql_sirumoku);
  $sirumoku = mysqli_fetch_assoc($record_sirumoku);

  //開催日
  $array = explode("-", $sirumoku['date']);
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
  $table_st_data=$sirumoku['start-time'];
  $array = explode(":", $table_st_data);
  $data_start=$array[0].":".$array[1];

  //終了時間
  $table_ft_data=$sirumoku['finish-time'];
  $array = explode(":", $table_ft_data);
  $data_finish=$array[0].":".$array[1];

  //会社名
  $table_company_data=$sirumoku['name_company'];
  $array = explode(",", $table_company_data);

  //学科情報の取得
  $sql_department = sprintf('SELECT * FROM `departments` WHERE `department_id` = "%d"', $_SESSION['join']['department']);
  $record_department = mysqli_query($db, $sql_department);
  $departments = mysqli_fetch_assoc($record_department);
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
    <link rel="stylesheet" href="css/sirumoku.css">
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
      <div class="col-md-6 col-md-offset-3 content-margin">
        <form method="post" action="" class="form-horizontal" role="form">
          <input type="hidden" name="action" value="submit">
          <div class="well"><p>ご登録内容をご確認ください。</p></div>
          <div class="sirumoku_datas">
            <table class="table table-striped trhover">
              <tr class="s_data_list">
                <th class="s_data_day">開催日</th>
                <th class="s_data_time">時間</th>
                <th class="s_data_time">開催場所</th>
                <th class="s_data_name">企業名</th>
              </tr>
              <tr>
                <th class="table_data_date"><?php echo htmlspecialchars($date_time); ?></th>
                <th class="table_data_time"><?php echo htmlspecialchars($data_start.' ~ '.$data_finish); ?></th>
                <th class="table_data_place"><?php echo htmlspecialchars($sirumoku['place']) ?></th>
                <th><p style="margin:0; font-size:10px;"><?php echo  htmlspecialchars($sirumoku['recommend']); ?></p><?php echo htmlspecialchars($array[0])."<br>".htmlspecialchars($array[1]); ?></th>
              </tr>
            </table>
          </div>
          <table class="table table-striped table-condensed">
            <tbody>
              <!-- 登録内容を表示 -->
              <tr>
                <td><div class="text-center">氏名</div></td>
                <td><div class="text-center"><?php echo htmlspecialchars($_SESSION['join']['name']); ?></div></td>
              </tr>
              <tr>
                <td><div class="text-center">学籍番号</div></td>
                <td><div class="text-center"><?php echo htmlspecialchars($_SESSION['join']['student_number']); ?></div></td>
              </tr>
              <tr>
                <td><div class="text-center">学科</div></td>
                <td><div class="text-center"><?php echo htmlspecialchars($departments['department_name']); ?></div></td>
              </tr>
              <tr>
                <td><div class="text-center">性別</div></td>
                <td><div class="text-center"><?php echo htmlspecialchars($_SESSION['join']['sex']); ?></div></td>
              </tr>
              <tr>
                <td><div class="text-center">出身</div></td>
                <td><div class="text-center"><?php echo htmlspecialchars($locate['prefecture_name']); ?></div></td>
              </tr>
            </tbody>
          </table>
          <a href="sirumoku-subscription.php?action=rewrite">&laquo;&nbsp;修正</a> |
          <input type="submit" class="btn btn-default" value="参加申し込み">
        </form>
      </div>
    </div>
    <?php include('footer.php'); ?>
  </body>
</html>
