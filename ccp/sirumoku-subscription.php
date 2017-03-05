<?php
  require('dbconnect.php');
  $sql_date=sprintf('SELECT * FROM `sirumoku_data` WHERE 1');
  $record_date=mysqli_query($db,$sql_date);
  $record_data=mysqli_query($db,$sql_date);

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
    <link rel="stylesheet" href="css/home.css">
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
      <div class="progress sub">
        <div class="one one-success-color"></div><div class="two two-success-color"></div><div class="three three-success-color"></div>
  			<div class="progress-bar progress-bar-success" style="width: 0%"></div>
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
              $array = explode("-", $table_date['date']);
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
                  $date_time=$array[0]."/".$str1."/".$str2;
                }
              }
              $table_st_data=htmlspecialchars($table_date['start-time']);
              $array = explode(":", $table_st_data);
              $data_start=$array[0].":".$array[1];

              $table_ft_data=htmlspecialchars($table_date['finish-time']);
              $array = explode(":", $table_ft_data);
              $data_finish=$array[0].":".$array[1];

              $table_company_data=htmlspecialchars($table_date['name_company']);
              $array = explode(",", $table_company_data);
          ?>
              <tr>
                <th class="table_data_date"><?php echo htmlspecialchars($date_time); ?></th>
                <th class="table_data_time"><?php echo htmlspecialchars($data_start."~".$data_finish); ?></th>
                <th><?php echo htmlspecialchars($array[0])."<br>".htmlspecialchars($array[1]); ?></th>
              </tr>
          <?php
            }
          }
          ?>
        </table>
      </div>
      <form id="form" action="sirumoku-subscription-check.php" method="post">
        <select class="" name="date">
          <option value="none">選択してください</option>
          <?php
          while($table_data=mysqli_fetch_assoc($record_data)){
            if($table_data['date'] > $deadline){
          ?>
              <option name="date" value="<?php print(htmlspecialchars($table_data['date'])); ?>"><?php print(htmlspecialchars($table_data['date'])); ?></option>
          <?php
            }
          }
          ?>
        </select>
        <input type="text" name="name" placeholder="氏名">
        <input type="text" name="student_number" placeholder="学籍番号">
        <input type="submit" name="" value="参加申込する">
      </form>
    </div>
    <?php include('footer.php'); ?>
  </body>
</html>
