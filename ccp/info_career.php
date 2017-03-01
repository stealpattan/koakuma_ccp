<?php
require('dbconnect.php');
require('function.php');

$department_name = array();
$selected_depart_name = array();
$people_number = array();
$area_number = array();
$job_lists = array();

// 学科の取得
$sql = sprintf('SELECT * FROM `departments`');
$record = mysqli_query($db, $sql) or die(mysqli_error($db));

while ($table = mysqli_fetch_assoc($record)) {
  $department_name[] = $table['department_name'];
}
// 数値の取得

// 職業リストの取得
$sql = sprintf('SELECT * FROM `job_lists`');
$record = mysqli_query($db, $sql) or die(mysqli_error($db));

while ($table = mysqli_fetch_assoc($record)) {
  $job_lists[] = $table;
}


if (isset($_POST['department_id']) && $_POST['department_id'] != 0) {
  $sql = sprintf('SELECT * FROM `departments` WHERE department_id = %d'
  , mysqlRes($db, $_POST['department_id'])
  );
  $record = mysqli_query($db, $sql) or die(mysqli_error($db));
  $table = mysqli_fetch_assoc($record);
  $selected_depart_name[] = $table['department_name'];
  $department_id = $_POST['department_id'];
  // 数値の取得
  $sql = sprintf('SELECT * FROM `area_numbers` WHERE department_id = %d'
  , mysqlRes($db, $_POST['department_id'])
  );
  $record = mysqli_query($db, $sql) or die(mysqli_error($db));
  // var_dump($record);
  while ($table = mysqli_fetch_assoc($record)) {
    $area_number = $table;
  }
  echo "<br>";
  // var_dump($area_number);
}else if(isset($_POST['department_id'])){ // 「すべて」を選択した場合
  $sql = sprintf('SELECT `department_name` FROM `departments`');
  $record = mysqli_query($db, $sql) or die(mysqli_error($db));
  while ($table = mysqli_fetch_assoc($record)) {
    $selected_depart_name[] = $table['department_name'];
  }
  // 全テーブル取得
  $sql = sprintf('SELECT * FROM `area_numbers`');
  $record = mysqli_query($db, $sql) or die(mysqli_error($db));
  while ($table = mysqli_fetch_assoc($record)) {
    $area_number = $table;
  }
}else {
  $sql = sprintf('SELECT `department_name` FROM `departments`');
  $record = mysqli_query($db, $sql) or die(mysqli_error($db));
  while ($table = mysqli_fetch_assoc($record)) {
    $selected_depart_name[] = $table['department_name'];
  }
  // 全テーブル取得
  $sql = sprintf('SELECT * FROM `area_numbers`');
  $record = mysqli_query($db, $sql) or die(mysqli_error($db));
  while ($table = mysqli_fetch_assoc($record)) {
    $area_number = $table;
  }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>就職情報</title>
    <link rel="shortcut icon" href="img/logo/tpu_logo.png">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/info-career.css">
  </head>
  <body>
    <!-- ヘッダー -->
    <header>
      <img class="logo" src="img/logo/tpu_logo_set.svg" alt="TPUのロゴ"/>
      <!-- ナビメニュー -->
      <div class="nav-menu">
        <ul id="menu">
          <li id="home"><a class="unselected_tab" href="home.php">ホーム</a></li>
          <li id="info-career"><a class="selected_tab" href="info_career.php">就職情報</a></li>
          <li id="intern"><a class="unselected_tab" href="recruitment.php">求人情報</a></li>
        </ul>
      </div>
      <div class="clear"></div>
    </header>
    <!-- コンテンツ -->
    <script type="text/javascript">
      function move_to_intern_page(job_alpha){
        var str = "intern.php?business_type=" + job_alpha;
        document.location = str;
      }
    </script>
    <div class="container contents">
      <div class="row">
        <div class="col-md4">
          <div class="position_table">
            <blockquote>
              <p>工学部の就職概況</p>
            </blockquote>
            <table class="table-striped trhover" cellpadding="0">
              <tr class="boldf">
                <th class="t_top boldf">年度</th>
                <th class="th_top" align="center">25年度</th>
                <th class="th_top" align="center">26年度</th>
                <th class="th_top" align="center">27年度</th>
              </tr>
              <tr class="align_cent">
                <th class="boldf">就職率(%)</th>
                <td>100.0</td>
                <td>100.0</td>
                <td>100.0</td>
              </tr>
            </table>
          </div>
          <div class="table_job" style="margin-top:10px;">
            <blockquote>
              <p>業種別求人・就職状況(平成27年度)</p>
            </blockquote>
            <table class="table table-bordered table-striped trhover" width="100%" cellspacing='1' cellpadding='0'>
              <tr>
                <th class="th_center th_jobtype" colspan="2" rowspan="2">業種</td>
                <th colspan="3" class="th_center"align="center">求人企業数(人)</td>
                <th colspan="6" class="th_center" align="">工学部・就職内定者数(人)</td>
              </tr>
              <tr>
                <th>県内</th>
                <th>県外</th>
                <th>計</th>
                <th>機械</th>
                <th>知能</th>
                <th>情報</th>
                <th>生物</th>
                <th>環境</th>
                <th>計</th>
              </tr>
              <?php foreach ($job_lists as $job_list): ?>
                  <tr onclick = "move_to_intern_page('<?php echo $job_list['job_alpha']; ?>')">
                    <td class="pos_center"><?php echo $job_list['job_alpha']; ?></td>
                    <td><?php echo $job_list['industy_type']; ?></td>
                    <td class="pos_right"><?php echo $job_list['in_prefec']; ?></td>
                    <td class="pos_right"><?php echo $job_list['out_prefec']; ?></td>
                    <td class="pos_right"><?php echo sum_num_recruit($job_list['in_prefec'], $job_list['out_prefec']); ?></td>
                    <td class="pos_right"><?php echo $job_list['machine']; ?></td>
                    <td class="pos_right"><?php echo $job_list['intellect']; ?></td>
                    <td class="pos_right"><?php echo $job_list['info']; ?></td>
                    <td class="pos_right"><?php echo $job_list['bio']; ?></td>
                    <td class="pos_right"><?php echo $job_list['environment']; ?></td>
                    <td class="pos_right">
                     <?php echo sum_num_private_decision($job_list['machine'], $job_list['intellect'], $job_list['info'], $job_list['bio'], $job_list['environment']); ?>
                    </td>
                  </tr>
              <?php endforeach; ?>
            </table>
          </div>
          <!-- 地域別就職情報 -->
          <div class="table_job_area">
            <blockquote>
              <p>地域別就職状況(平成27年度)</p>
            </blockquote>
            <table class="table-bordered table-hover" width="100%">
              <tr>
                <th>学科名</th>
                <th>北海道・東北</th>
                <th>関東</th>
                <th>甲信越</th>
                <th>富山</th>
                <th>石川・福井</th>
                <th>東海</th>
                <th>近畿</th>
                <th>中国・四国・九州</th>
                <th>合計</th>
              </tr>
              <?php if (isset($_POST['department_id']) && $_POST['department_id'] != 0): ?>
                <tr>
                  <td><?php echo $selected_depart_name[0]; ?></td>
                  <td class="pos_right"><?php echo $area_number['hokkaido']; ?></td>
                  <td class="pos_right"><?php echo $area_number['kanto']; ?></td>
                  <td class="pos_right"><?php echo $area_number['koshinetsu']; ?></td>
                  <td class="pos_right"><?php echo $area_number['toyama']; ?></td>
                  <td class="pos_right"><?php echo $area_number['fukui']; ?></td>
                  <td class="pos_right"><?php echo $area_number['tokai']; ?></td>
                  <td class="pos_right"><?php echo $area_number['kinki']; ?></td>
                  <td class="pos_right"><?php echo $area_number['shikoku']; ?></td>
                  <td class="pos_right">
                    <?php echo sum_pref($area_number['hokkaido'], $area_number['kanto'], $area_number['koshinetsu'], $area_number['toyama'], $area_number['fukui'], $area_number['tokai'], $area_number['kinki'], $area_number['shikoku']); ?>
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($record as $d_name): ?>
                  <tr>
                    <td><?php echo $department_name[$d_name['department_id']]; ?></td>
                    <td class="pos_right"><?php echo $d_name['hokkaido']; ?></td>
                    <td class="pos_right"><?php echo $d_name['kanto']; ?></td>
                    <td class="pos_right"><?php echo $d_name['koshinetsu']; ?></td>
                    <td class="pos_right"><?php echo $d_name['toyama']; ?></td>
                    <td class="pos_right"><?php echo $d_name['fukui']; ?></td>
                    <td class="pos_right"><?php echo $d_name['tokai']; ?></td>
                    <td class="pos_right"><?php echo $d_name['kinki']; ?></td>
                    <td class="pos_right"><?php echo $d_name['shikoku']; ?></td>
                    <td class="pos_right">
                      <?php echo sum_pref($d_name['hokkaido'], $d_name['kanto'], $d_name['koshinetsu'], $d_name['toyama'], $d_name['fukui'], $d_name['tokai'], $d_name['kinki'], $d_name['shikoku']);
                      ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- フッター -->
    <?php include('footer.php'); ?>

  </body>
</html>
