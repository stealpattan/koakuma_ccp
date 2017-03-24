<?php
//connect to databese
require('dbconnect.php');
require('function.php');

$dates = array();

if(empty($_POST['employment']) && empty($_POST['intern'])){
  $error['employment'] = 'blank';
  $sql = sprintf('SELECT * FROM `company_datas` WHERE 0');
}else{
  $error['employment'] = '';
}
if (isset($_POST['employment']) && empty($_POST['local']) && empty($_POST['industryType'])){
  $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 10 ORDER BY id DESC');
}elseif (isset($_POST['employment']) && isset($_POST['local']) && empty($_POST['industryType'])){
  $result_location = $_POST['local'];
  foreach ($result_location as $key => $local) {
    if ($local == 2){
      $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 10 ORDER BY id DESC');
    }else{
      $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 10 AND location_datas.location_id = "%d"', $local);
    }
  }
}elseif(isset($_POST['employment']) && empty($_POST['local']) && isset($_POST['industryType'])){
  $result_indust = $_POST['industryType'];
  foreach ($result_indust as $key => $indust) {
    if ($indust == 3){
      $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 10 ORDER BY id DESC');
    }else{
      if($_POST['industryType'][0] == 3000){
        $sql = sprintf("SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 10 AND industries.indust_id >= 300 ORDER BY id DESC");
      }else{
        $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 10 AND industries.indust_id = "%d" ORDER BY id DESC', $indust);
      }
    }
  }
}elseif(isset($_POST['employment']) && isset($_POST['local']) && isset($_POST['industryType'])){
  $result_indust = $_POST['industryType'];
  $result_location = $_POST['local'];
  foreach ($result_indust as $key => $indust) {
    foreach ($result_location as $key => $local) {
      if ($local == 2 && $indust == 3){
        $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 10 ORDER BY id DESC');
      }elseif ($local == 2 && $indust != 3){
        if($_POST['industryType'][0] == 3000){
          $sql = sprintf("SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 10 AND industries.indust_id >= 300 ORDER BY id DESC");
        }else{
          $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 10 AND industries.indust_id = "%d" ORDER BY id DESC', $indust);
        }
      }elseif ($local != 2 && $indust == 3) {
        $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 10 AND location_datas.location_id = "%d" ORDER BY id DESC', $local);
      }elseif ($local != 2 && $indust != 3) {
        if($_POST['industryType'][0] == 3000){
          $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 10 AND location_datas.location_id = "%d" AND industries.indust_id >= 300 ORDER BY id DESC', $local);
        }else{
          $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 10 AND location_datas.location_id = "%d" AND industries.indust_id = "%d" ORDER BY id DESC', $local, $indust);
        }
      }
    }
  }
}elseif (isset($_POST['intern']) && empty($_POST['local']) && empty($_POST['industryType'])){
  $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 11 ORDER BY id DESC');
}elseif (isset($_POST['intern']) && isset($_POST['local']) && empty($_POST['industryType'])){
  $result_location = $_POST['local'];
  foreach ($result_location as $key => $local) {
    if ($local == 2){
      $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 11 ORDER BY id DESC');
    }else{
      $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 11 AND location_datas.location_id = "%d"', $local);
    }
  }
}elseif(isset($_POST['intern']) && empty($_POST['local']) && isset($_POST['industryType'])){
  $result_indust = $_POST['industryType'];
  foreach ($result_indust as $key => $indust) {
    if ($indust == 3){
      $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 11 ORDER BY id DESC');
    }else{
      if($_POST['industryType'][0] == 3000){
        $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 11 AND industries.indust_id >= 300 ORDER BY id DESC');
      }else{
        $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 11 AND industries.indust_id = "%d" ORDER BY id DESC', $indust);
      }
    }
  }
}elseif (isset($_POST['intern']) && isset($_POST['local']) && isset($_POST['industryType'])) {
  $result_indust = $_POST['industryType'];
  $result_location = $_POST['local'];
  foreach ($result_indust as $key => $indust) {
    foreach ($result_location as $key => $local) {
      if ($local == 2 && $indust == 3){
        $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 11 ORDER BY id DESC');
      }elseif ($local == 2 && $indust != 3){
        if($_POST['industryType'][0] == 3000){
          $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 11 AND industries.indust_id >= 300 ORDER BY id DESC');
        }else{
          $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 11 AND industries.indust_id = "%d" ORDER BY id DESC', $indust);
        }
      }elseif ($local != 2 && $indust == 3) {
        $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 11 AND location_datas.location_id = "%d" ORDER BY id DESC', $local);
      }elseif ($local != 2 && $indust != 3) {
        if($_POST['industryType'][0] == 3000){
          $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 11 AND location_datas.location_id = "%d" AND industries.indust_id >= 300 ORDER BY id DESC', $local);
        }else{
          $sql = sprintf('SELECT * FROM company_datas, location_datas, industries WHERE company_datas.location_id = location_datas.location_id AND company_datas.indust_id = industries.indust_id AND `recruit_id` = 11 AND location_datas.location_id = "%d" AND industries.indust_id = "%d" ORDER BY id DESC', $local, $indust);
        }
      }
    }
  }
}
$recordSet = mysqli_query($db, $sql) or die(mysqli_error($db));
while($table = mysqli_fetch_assoc($recordSet)){
  $dates[] = $table;
  rsort($dates);
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>求人の検索結果</title>
    <link rel="shortcut icon" href="img/logo/tpu_logo.png">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/recruitment.css">
    <link rel="stylesheet" type="text/css" href="css/intern.css">
    <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
    <!-- .selectorのcheckboxを単一選択にする -->
    <script type="text/javascript">
        jQuery(function($){
            $(function(){
              $('.select').on('click', function() {
                if ($(this).prop('checked')){
                    // 一旦全てをクリアして再チェックする
                    $('.select').prop('checked', false);
                    $(this).prop('checked', true);
                }
              });
            });
        });
    </script>
  </head>
  <body>
    <?php require('header.php'); ?>
    <?php if(ip_tracer() == true): ?>
      <div class="searchform">
        <form class="searchform_re" action="recruitment.php#result" method="post">
          <ul class="selector">
            <li><input type="checkbox" name="employment" class="select" value="10" checked>就職求人情報</li>
            <li><input type="checkbox" name="intern" class="select" value="11">インターンシップ情報</li>
            <?php if($error['employment'] == 'blank'): ?>
              <li><p class="error">* どちらか選択してください</p></li>
            <?php else:
              endif; ?>
          </ul>
          <ul class="local_1">
            <li><input type="checkbox" name="local[]" value="2">すべて選択</li>
          </ul>
          <ul class="local_2">
            <li><input type="checkbox" name="local[]" value="20">北海道・東北</li>
            <li><input type="checkbox" name="local[]" value="21">関東</li>
            <li><input type="checkbox" name="local[]" value="22">甲信越</li>
            <li><input type="checkbox" name="local[]" value="23">富山</li>
            <li><input type="checkbox" name="local[]" value="24">石川・福井</li>
            <li><input type="checkbox" name="local[]" value="25">東海</li>
            <li><input type="checkbox" name="local[]" value="26">近畿</li>
            <li><input type="checkbox" name="local[]" value="27">四国・中国・九州</li>
          </ul>
          <ul class="indust_1">
            <li><input type="checkbox" name="industryType[]" value="3">すべて選択</li>
          </ul>
          <ul class="indust_2">
            <li><input type="checkbox" name="industryType[]" value="31">建設業</li>
            <li><input type="checkbox" name="industryType[]" value="32">製造業</li>
            <li><input type="checkbox" name="industryType[]" value="33">電気・ガス・熱供給・水道業</li>
          </ul>
          <ul class="indust_3">
            <li><input type="checkbox" name="industryType[]" value="34">情報通信業</li>
            <li><input type="checkbox" name="industryType[]" value="35">学術研究，専門・技術サービス</li>
            <li><input type="checkbox" name="industryType[]" value="3000">その他</li>
          </ul>
          <div class="clear"></div>
          <input class="submit btn btn-primary" type="submit" value="検索">
          <input class="reset btn btn-warning" type="reset" value="リセット">
        </form>
      </div>
      <div class="clear"></div>
      <section>
        <table>
          <tr>
            <th class="company_name">企業名</th>
            <th class="indust_type">業種</th>
            <th class="address">所在地</th>
          </tr>
          <?php   foreach($dates as $date): ?>
            <tr class='borders'>
              <td class="company_name"><?php print(htmlspecialchars($date['company_name'])); ?></td>
              <td class="indust_type"><?php print(htmlspecialchars($date['indust_name'])); ?></td>
              <td class="address"><?php print(htmlspecialchars($date['location_name'])); ?></td>
            </tr>
          <?php   endforeach; ?>
        </table>
      </section>
    <?php else: ?>
      <div style='text-align:center;'>
        <h1>学外からのアクセスを制限しています。申し訳有りません.</h1>
      </div>
    <?php endif; ?>
    <?php include('footer.php'); ?>
  </body>
</html>
