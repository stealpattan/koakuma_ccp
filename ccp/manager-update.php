<?php
	session_start();
	date_default_timezone_set('Asia/Tokyo');
	require('dbconnect.php');
	$errors = array();
  $id = $_REQUEST['id'];

  //編集するsirumokuデータの取得
  $sql = sprintf('SELECT * FROM `sirumoku_data` WHERE id = %d', $id);
  $record = mysqli_query($db, $sql) or die(mysqli_error($db));
  $table = mysqli_fetch_assoc($record);
  $department = explode("、", $table['recommend']);
  $department_1 = substr($department[0],1);
  $department_2 = explode("推", $department[1]);
  $department_2 = substr($department_2[0],0);
  $company = explode(",", $table['name_company']);
  $com_1 = $company[0];
  $com_2 = $company[1];
  // echo $department_2;
	if(!empty($_POST)){
		if($_POST['date'] == '' || $_POST['date'] == null){
			$errors['data'] = 'blank';
		}
		if($_POST['place'] == '' || $_POST['place'] == null){
			$errors['place'] = 'blank';
		}
		if($_POST['com_1'] == '' || $_POST['com_1'] == null){
			$errors['com_1'] = 'blank';
		}
		if($_POST['com_2'] == '' || $_POST['com_2'] == null){
			$errors['com_2'] = 'blank';
		}
		if($_POST['number_people'] == '' || $_POST['number_people'] == null){
			$errors['number_people'] = 'blank';
		}
		else{
			$_SESSION['sirumoku'] = $_POST;
			$company_name = $_POST['com_1'].','.$_POST['com_2'];
			$recommend = '['.$_POST['department_1'].'、'.$_POST['department_2'].'推薦]';
			$time = explode("~", $_POST['time']);
			$start_time = $time[0];
			$finish_time = $time[1];
			//DBに会員情報を登録
			$sql_entry = sprintf('UPDATE `sirumoku_data` SET `date` = "%s", `start-time` = "%s", `finish-time` = "%s", `place` = "%s", `number_people` = "%d", `recommend` = "%s", `name_company` = "%s" WHERE `id` = "%d"',
			mysqli_real_escape_string($db, $_POST['date']),
			mysqli_real_escape_string($db, $start_time),
			mysqli_real_escape_string($db, $finish_time),
			mysqli_real_escape_string($db, $_POST['place']),
			mysqli_real_escape_string($db, $_POST['number_people']),
			mysqli_real_escape_string($db, $recommend),
			mysqli_real_escape_string($db, $company_name),
      mysqli_real_escape_string($db, $id)
			);
			mysqli_query($db, $sql_entry) or die(mysqli_error());
			header('location: manager.php?page_type=sirumoku');
			exit();
		}
	}
	$deadline=date('Y-m-d', strtotime("+3 day"));
?>

<!DOCTYPE html>
<html lang='ja'>
	<head>
		<meta charset='utf8'>
		<title>管理者ページ</title>
		<link rel="shortcut icon" href="img/logo/tpu_logo.png">
		<link rel="stylesheet" href="css/reset.css">
		<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" href="css/home.css">
		<link rel="stylesheet" href="css/intern.css">
		<link rel="stylesheet" href="css/sirumoku.css">
		<script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
	</head>
	<body>
		<?php include_once("analyticstracking.php") ?>
		<?php require("header.php"); ?>
		<div class="new_event">
			<div class="sirumoku_datas">
	      <table class="table table-bordered table-striped trhover">
	        <tr class="s_data_list">
	          <th class="s_data_day">開催日</th>
	          <th class="s_data_time">時間帯</th>
	          <th class="s_data_place">開催場所</th>
            <th class="s_data_name">定員</th>
	          <th class="s_data_name">企業名</th>
						<th class="s_data_name">編集・削除</th>
	        </tr>
	        <?php
	            //開催日
	            $array = explode("-", $table['date']);
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
	            $table_st_data=$table['start-time'];
	            $array = explode(":", $table_st_data);
	            $data_start=$array[0].":".$array[1];
	            //終了時間
	            $table_ft_data=$table['finish-time'];
	            $array = explode(":", $table_ft_data);
	            $data_finish=$array[0].":".$array[1];
	            //会社名
	            $table_company_data=$table['name_company'];
	            $array = explode(",", $table_company_data);
	            //sirumoku_entryの各受付数を取得
	            $sql_entry=sprintf('SELECT COUNT(`event_date`) AS cnt FROM `sirumoku_entry` WHERE event_date = "%s"', $table['date']);
	            $record_entry=mysqli_query($db,$sql_entry);
	            $entry_number = mysqli_fetch_assoc($record_entry);
	            $cnt = $entry_number["cnt"];
	            $remain = $table['number_people'] - $cnt;
	            $errors['entry'] = '';
	            if($table['date'] < $deadline){
	              $errors['entry'] = 'deadline';
	            }elseif($cnt == $table['number_people']){
	              $errors['entry'] = 'over';
	            }elseif($remain <= 5){
	              $errors['entry'] = 'warning';
	            }
	            ?>
	            <tr>
	              <?php if(!empty($errors['entry'])): ?>
	              <th class="table_data_date"><p style="padding-top:8px;"><?php echo htmlspecialchars($date_time); ?></p></th>
	              <th class="table_data_time"><p style="padding-top:8px;"><?php echo htmlspecialchars($data_start.' ~ '.$data_finish); ?></p></th>
	              <th class="table_data_place"><p style="padding-top:8px;"><?php echo htmlspecialchars($table['place']) ?></p></th>
                <th class="table_data_place"><p style="padding-top:8px;"><?php echo htmlspecialchars($table['number_people']) ?></p></th>
	              <?php else: ?>
	              <th class="table_data_date"><p><?php echo htmlspecialchars($date_time); ?></p></th>
	              <th class="table_data_time"><p><?php echo htmlspecialchars($data_start.' ~ '.$data_finish); ?></p></th>
	              <th class="table_data_place"><p><?php echo htmlspecialchars($table['place']) ?></p></th>
                <th class="table_data_place"><p><?php echo htmlspecialchars($table['number_people']) ?></p></th>
	              <?php endif; ?>
	              <th>
	                <p style="margin:0; font-size:10px;"><?php echo  htmlspecialchars($table['recommend']); ?></p>
	                <?php echo htmlspecialchars($array[0])."<br>".htmlspecialchars($array[1]); ?>
	                <?php if (isset($errors['entry']) && $errors['entry'] == 'deadline' ) : ?>
	                  <p class="error" style="color: red; font-size: 10px; margin: 0;">受付を終了しました</p>
                  <?php elseif (isset($errors['entry']) && $errors['entry'] == 'over' ) : ?>
	                  <p class="error" style="color: red; font-size: 10px; margin: 0;">定員に達しました</p>
	                <?php elseif (isset($errors['entry']) && $errors['entry'] == 'warning') : ?>
	                  <p class="error" style="color: red; font-size: 10px; margin: 0;">残り<?php echo $remain; ?>名で定員に達します</p>
	                <?php endif; ?>
	              </th>
								  <?php if(!empty($errors['entry'])): ?>
									<th class="table_data_place"><p style="padding-top:8px;">[&nbsp;<a href="manager-update.php?id=<?php echo $table['id'] ?>">編集</a>・<a href="manager-delete.php?id=<?php echo $table['id'] ?>">削除</a>&nbsp;]</p></th>
	                <?php else: ?>
	                <th class="table_data_place"><p>[&nbsp;<a href="manager-update.php?id=<?php echo $table['id'] ?>">編集</a>・<a href="manager-delete.php?id=<?php echo $table['id'] ?>">削除</a>&nbsp;]</p></th>
	                <?php endif; ?>
	              </tr>
	          </table>
						<div class="sirumoku_form">
							<form class="" action="" method="post">
								<p>開催日</p>
								<input type="text" name="date" value="<?php echo $table['date']; ?>">
								<p>開催時間</p>
								<select class="" name="time">
                  <?php $select_time=$table['start-time']; ?>
									<option value="09:00~10:30" <?php if($select_time=='09:00:00') echo 'selected'; ?>>9:00 ~ 10:30</option>
									<option value="10:40~12:10" <?php if($select_time=='10:40:00') echo 'selected'; ?>>10:40 ~ 12:10</option>
									<option value="13:10~14:40" <?php if($select_time=='13:10:00') echo 'selected'; ?>>13:10 ~ 14:40</option>
									<option value="14:50~16:20" <?php if($select_time=='14:50:00') echo 'selected'; ?>>14:50 ~ 16:20</option>
								  <option value="16:30~18:00" <?php if($select_time=='16:30:00') echo 'selected'; ?>>16:30 ~ 18:00</option>
								</select>
								<p開催場所></p>
								<input type="text" name="place" value="<?php echo $table['place']; ?>">
								<p>企業名</p>

								<input type="text" name="com_1" value="<?php echo $com_1; ?>">
								<input type="text" name="com_2" value="<?php echo $com_2; ?>">
								<p>定員</p>
								<input type="text" name="number_people" value="<?php echo $table['number_people']; ?>">
								<p>オススメの学科</p>
								<select class="" name="department_1">
                  <?php $select_department=$department_1; ?>
									<option value="機械" <?php if($select_department=='機械') echo 'selected'; ?>>機械システム工学科</option>
									<option value="知能" <?php if($select_department=='知能') echo 'selected'; ?>>知能デザイン工学科</option>
									<option value="情報" <?php if($select_department=='情報') echo 'selected'; ?>>電子・情報工学科</option>
									<option value="生物" <?php if($select_department=='生物') echo 'selected'; ?>>生物工学科</option>
									<option value="環境" <?php if($select_department=='環境') echo 'selected'; ?>>環境工学科</option>
									<option value="医薬品" <?php if($select_department=='医薬品') echo 'selected'; ?>>医薬品工学科</option>
								</select>
								<select class="" name="department_2">
                  <?php $select_department=$department_2; ?>
									<option value="機械" <?php if($select_department=='機械') echo 'selected'; ?>>機械システム工学科</option>
									<option value="知能" <?php if($select_department=='知能') echo 'selected'; ?>>知能デザイン工学科</option>
									<option value="情報" <?php if($select_department=='情報') echo 'selected'; ?>>電子・情報工学科</option>
									<option value="生物" <?php if($select_department=='生物') echo 'selected'; ?>>生物工学科</option>
									<option value="環境" <?php if($select_department=='環境') echo 'selected'; ?>>環境工学科</option>
									<option value="医薬品" <?php if($select_department=='医薬品') echo 'selected'; ?>>医薬品工学科</option>
								</select><br>
                <p class="btn btn-default">
                  <span><a href="manager.php?page_type=sirumoku">登録画面へ戻る</a></span>
                </p>
                <input type="submit" class="btn btn-warning" name="" value="編集">
              </form>

						</div>
	        </div>
				</div>
		<?php require("footer.php"); ?>
	</body>
</html>
