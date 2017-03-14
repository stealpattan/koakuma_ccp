<?php
  session_start();
  //DBに接続
  require('dbconnect.php');
  //$date_timeの配列を用意
  $date_time = array();
  //
  $data_date = array();
  //エラー格納用の配列を用意
  $errors = array();
  //書き直し処理
  if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'rewrite'){
    $_POST = $_SESSION['join'];
    $errors['rewrite'] = true;
  }
  //sirumoku_dataの全データを取得
  $sql_date=sprintf('SELECT * FROM `sirumoku_data` WHERE 1');
  $record_date=mysqli_query($db,$sql_date);
  $record_data=mysqli_query($db,$sql_date);
  //都道府県の全データを取得
  $sql_pref=sprintf('SELECT * FROM `prefectures` WHERE 1');
  $record_pref=mysqli_query($db,$sql_pref);
  //締め切り日の設定
  $deadline=date('Y-m-d', strtotime("+3 day"));
  //各入力値を保持する変数を用意
  $date = '';
  $name = '';
  $student_number = '';
  $sex = '';
  $pref = '';
  $opinion = '';
  //確認画面へのボタンが押された時
if(!empty($_POST)){
  $date = $_POST['date'];
  $name = $_POST['name'];
  $student_number = $_POST['student_number'];
  $sex = $_POST['sex'];
  $pref = $_POST['pref'];
  $opinion = $_POST['opinion'];
  //ページ内フォームにエラーがある場合のバリデーション処理
  if($name == ''){
    //ニックネームのフォームが方のため、画面にエラーを出力
    $errors['name'] = 'blank';
  }
  if($student_number == ''){
    $errors['student_number'] = 'blank';
  }elseif(strlen($student_number) != 7){
    $errors['student_number'] = 'length';
  }
  //学生情報のデータ取得
  $sql_student = sprintf('SELECT * FROM `student_datas`, `prefectures` WHERE student_datas.prefecture_id = prefectures.prefecture_id AND `student_number` = "%d"', $student_number);
  $record_student = mysqli_query($db, $sql_student);
  $student=mysqli_fetch_assoc($record_student);

  //student_numberからdepartment_idを生成する
  $departments = substr($student_number, 2, -3); //学科コードの取得
  switch($departments){
    case 13:
      $department_id = 1;
      break;
    case 14:
      $department_id = 2;
      break;
    case 15:
      $department_id = 3;
      break;
    case 16:
      $department_id = 4;
      break;
    case 17:
      $department_id = 5;
      break;
  }
  //student_numberからemailを生成する
  $emails = substr($student_number, -6);
  $email = 't'.$emails.'@st.pu-toyama.ac.jp';

  if($student['student_number'] == $student_number){
    if($student['student_name'] == $name && $student['sex'] == $sex && $student['prefecture_id'] == $pref){
      $errors['datas'] = 'correct';
      $_SESSION['join'] = $_POST;
      $_SESSION['join']['department'] = $department_id;
      $_SESSION['join']['email'] = $email;
      header('Location: ssca.php');
      exit();
    }else{
      $errors['datas'] = 'wrong';
    }
  }else{
    $errors['datas'] = 'correct';
    $_SESSION['join'] = $_POST;
    $_SESSION['join']['department'] = $department_id;
    $_SESSION['join']['email'] = $email;
    header('Location: sscn.php');
    exit();
  }
  //エラーがなかった場合の処理
  if(empty($errors)){
    //$_POSTの情報を$_SESSIONに入れる
    $_SESSION['join'] = $_POST;
    header('Location: sirumoku-subscription-check.php');
    exit();
  }
}
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
              //開催日
              $array = explode("-", $table_date['date']);
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
              $table_st_data=$table_date['start-time'];
              $array = explode(":", $table_st_data);
              $data_start=$array[0].":".$array[1];

              //終了時間
              $table_ft_data=$table_date['finish-time'];
              $array = explode(":", $table_ft_data);
              $data_finish=$array[0].":".$array[1];

              //会社名
              $table_company_data=$table_date['name_company'];
              $array = explode(",", $table_company_data);
          ?>
              <tr>
                <th class="table_data_date"><?php echo htmlspecialchars($date_time); ?></th>
                <th class="table_data_time"><?php echo htmlspecialchars($data_start.' ~ '.$data_finish); ?></th>
                <th><?php echo htmlspecialchars($array[0])."<br>".htmlspecialchars($array[1]); ?></th>
              </tr>
          <?php
            }
          }
          ?>
        </table>
      </div>
      <?php
        if(!empty($_POST)){
          if(isset($errors['datas']) && $errors['datas'] == 'wrong'){
      ?>
      <p style="text-align: center; color: red; font-size: 16px; margin-top: 10px;">*&nbsp;入力情報の確認をしてください</p>
      <?php
          }
        }
      ?>
      <div class="sub">
        <form id="form" action="sirumoku-subscription.php" method="post">
          <div class="date">
            <label for="">開催日:</label><span style="color: red; font-size: 10px; margin-top: 10px;">&nbsp;&nbsp;*&nbsp;必須</span><br>
            <select class="" name="date">
              <?php
              while($table_data=mysqli_fetch_assoc($record_data)){
                if($table_data['date'] > $deadline){
                  $array = explode("-", $table_data['date']);
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
                  //POST(submit)された値を$selectに取得
                  $select_date="";
                  if(isset($_POST['date']))$select_date=$_POST['date'];
                  ?>
                  <option name="date" value="<?php print(htmlspecialchars($table_data['date'])); ?>" <?php if($select_date==$table_data['date']) echo 'selected'; ?>><?php echo htmlspecialchars($date_time); ?></option>
              <?php
                }
              }
              ?>
            </select>
          </div>
          <div class="personal">
            <div class="name">
              <label for="">氏名:</label><span style="color: red; font-size: 10px; margin-top: 10px;">&nbsp;&nbsp;*&nbsp;必須</span><br>
              <input type="text" name="name" placeholder="氏名" value="<?php echo $name; ?>">
              <?php if (isset($errors['name']) && $errors['name'] == 'blank' ) : ?>
                <span style="color: red; font-size: 10px; margin-top: 10px;">*&nbsp;氏名を入力してください</span>
              <?php endif; ?>
            </div>
            <div class="student_number">
              <label for="">学籍番号:</label><span style="color: red; font-size: 10px; margin-top: 10px;">&nbsp;&nbsp;*&nbsp;必須</span><br>
              <input type="text" name="student_number" placeholder="学籍番号" value="<?php echo $student_number; ?>">
              <?php if (isset($errors['student_number']) && $errors['student_number'] == 'blank' ) : ?>
                <span style="color: red; font-size: 10px; margin-top: 10px;">*&nbsp;学籍番号を入力してください</span>
              <?php elseif (isset($errors['student_number']) && $errors['student_number'] == 'length') : ?>
                <span style="color: red; font-size: 10px; margin-top: 10px;">*&nbsp;学籍番号を正しく入力してください</span>
              <?php endif; ?>
            </div>
            <div class="sex">
              <label for="">性別:</label><span style="color: red; font-size: 10px; margin-top: 10px;">&nbsp;&nbsp;*&nbsp;必須</span><br>
              <select name="sex">
                <?php
                //POST(submit)された値を$selectに取得
                $select_sex="";
                if(isset($_POST['sex']))$select_sex=$_POST['sex']; ?>
                <option name="sex" value="男" <?php if($select_sex=='男') echo 'selected'; ?>>男</option>
                <option name="sex" value="女" <?php if($select_sex=='女') echo 'selected'; ?>>女</option>
              </select>
            </div>
            <div class="pref">
              <label for="">出身:</label><span style="color: red; font-size: 10px; margin-top: 10px;">&nbsp;&nbsp;*&nbsp;必須</span><br>
              <select class="prefecture" name="pref">
                <?php while($table_pref=mysqli_fetch_assoc($record_pref)){
                  //POST(submit)された値を$selectに取得
                  $select_pref="";
                  if(isset($_POST['pref']))$select_pref=$_POST['pref']; ?>
                  <option name="date" value="<?php print(htmlspecialchars($table_pref['prefecture_id'])); ?>" 
                    <?php if($select_pref==$table_pref['prefecture_name']) echo 'selected'; 
                    ?>>
                    <?php print(htmlspecialchars($table_pref['prefecture_name'])); 
                    ?>
                  </option>
                <?php } ?>
              </select>
            </div>
            <div class="opinion">
              <label for="">意見:</label><br>
              <textarea name="opinion" rows="8" cols="80"><?php echo $opinion; ?></textarea>
            </div>
          </div>
          <div class="submit">
            <input type="submit" class="btn btn-default" value="確認画面へ">
          </div>
        </form>
      </div>
    </div>
    <?php include('footer.php'); ?>
  </body>
</html>
